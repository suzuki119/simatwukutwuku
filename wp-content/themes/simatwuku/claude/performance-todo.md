# 島トゥク パフォーマンス改善 — 残課題と対処法

対象レポート: PageSpeed Insights `o91raw0n5s`（2026-08-05 計測 / https://www.simatwuku.shop/）

---

## 1. 現状

### スコアの推移

| 計測 | デスクトップ | モバイル | 自ドメインの転送量 |
|---|---|---|---|
| 改善前（`miu0cw3o7p`） | 82 | 61 | 4,816 KiB |
| 1回目デプロイ後（`uf4t8ii71r`） | 91 | 61 | 1,482 KiB |
| **2回目デプロイ後（`o91raw0n5s`）** | **89** | **61** | **808 KiB** |

> デスクトップの 91 → 89 は計測ごとのブレの範囲です（LCP 1.5 → 1.6秒、Speed Index 1.5 → 1.8秒）。

### 現在の指標

| 指標 | デスクトップ | モバイル | 合格ライン |
|---|---|---|---|
| First Contentful Paint | 1.2 秒 | **5.4 秒** | 1.8 秒以下 |
| Largest Contentful Paint | 1.6 秒 | **7.6 秒** | 2.5 秒以下 |
| Total Blocking Time | 0 ミリ秒 ✅ | 0 ミリ秒 ✅ | 200ms以下 |
| Cumulative Layout Shift | 0 ✅ | 0 ✅ | 0.1以下 |
| Speed Index | 1.8 秒 | 5.9 秒 | 3.4 秒以下 |

**TBT と CLS は満点**です。残っているのは描画までの時間（FCP / LCP）だけで、しかもモバイルに集中しています。

---

## 2. 残課題の一覧

優先度は「効果 ÷ 手間」で付けています。

| # | 課題 | Lighthouse指摘 | 優先度 | 状態 |
|---|---|---|---|---|
| A | 圧縮版CSSが配信されていない | CSS の最小化 9 KiB | 高 | ✅ **対応済**（2026-08-05） |
| B | 死んだコードによる強制リフロー | 強制リフロー 81ms | 高 | ✅ **対応済**（2026-08-05） |
| C | Google Fonts の CSS が 86KB | 使用していない CSS 86 KiB | 中 | 未着手（1〜3時間） |
| D | モバイルの描画遅延（アニメーション負荷） | LCP レンダリング遅延 1,950ms | 中 | 未着手（1〜2時間） |
| E | 画像のさらなる圧縮 | 画像配信 260〜426 KiB | 低 | 🔸 **一部対応**（`28.webp`のみ） |
| F | キャッシュ保存期間 | 効率的なキャッシュ保存期間 16 KiB | 低 | 未着手（15分） |
| G | 広告バナーに width/height がない | width と height が未指定 | 対応不可 | — |

---

## A. 圧縮版CSSが配信されていない ✅ 対応済

> **2026-08-05 実施済み。** 下記「対処法」の方法1・方法2ではなく、
> 両者の欠点をなくす**ハッシュ照合方式**を採用しました。詳細は末尾の「実施内容」を参照。

### 症状

Lighthouse が「CSS の最小化 9 KiB」を指摘し続けている。レンダリングをブロックしているリクエストにも
`css/style.css` と表示される（`style.min.css` ではない）。

### 原因

本番サーバーのファイル更新時刻を見ると、**圧縮版が1秒だけ古い**：

```
css/style.css      last-modified: Tue, 04 Aug 2026 21:39:59 GMT  (39,158 B)
css/style.min.css  last-modified: Tue, 04 Aug 2026 21:39:58 GMT  (29,334 B)
```

`functions.php` の `simatwuku_styles()` は「圧縮版が通常版と同じか新しいときだけ圧縮版を使う」という
安全装置を入れてあるため、**設計通りに通常版へフォールバックしている**。
FTPのアップロード順で1秒ずれただけで、CSSの中身は正しい。

### 対処法（どちらか）

**方法1：判定をやめて常に圧縮版を使う（推奨・簡単）**

`functions.php` の該当箇所を次に置き換える。

```php
function simatwuku_styles()
{
    $dir  = get_template_directory();
    // 圧縮版があれば常にそちらを配信する。
    // ※ SCSS を編集したら必ず下記コマンドで style.min.css を作り直すこと（作り忘れると古いCSSが出る）
    //    npx esbuild css/style.css --minify --outfile=css/style.min.css
    $file = is_readable($dir . '/css/style.min.css') ? '/css/style.min.css' : '/css/style.css';

    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . $file,
        array(),
        filemtime($dir . $file)
    );
}
```

- メリット: 確実に圧縮版が出る
- デメリット: `style.min.css` の作り直しを忘れると**古いデザインが表示される**

**方法2：圧縮版をやめる（最も安全）**

`style.min.css` を削除し、通常版だけを配信する。

- サーバーは既に gzip 圧縮しているため、実際の転送量は 39KB → **6.9KB**
- 最小化してもgzip後の差は 1〜2KB 程度で、体感差はほぼゼロ
- Lighthouse のスコアは数点下がるが、**作り忘れによる事故がなくなる**

> 判断材料：「9 KiB削減」は非圧縮換算の数字です。実際の通信量への影響は小さいので、
> SCSSを頻繁に触るなら**方法2**、スコアを取りにいくなら**方法1**をおすすめします。

---

## B. 死んだコードによる強制リフロー ✅ 対応済

> **2026-08-05 実施済み。** 下記のブロックを `script.js` から削除しました。

### 症状

「強制リフロー 合計81ミリ秒」— 発生源は `script.js` の 161行目と 164行目。

### 原因

`script.js` に、何もしていない空のブロックが残っている：

```js
window.addEventListener('DOMContentLoaded', () => {
    const mv = document.querySelector('.mainvisual');
    if (mv) {
        // 現在の画面の高さを取得して、pxで直接指定する
        const vh = window.innerHeight;   // ← 取得するだけで使っていない
    }
});
```

`window.innerHeight` の読み取りはブラウザにレイアウトの再計算を強制する。
しかも `vh` はどこにも使われていない**完全な死にコード**。

（`document` の DOMContentLoaded ハンドラの中で `window` に登録しているため、
イベントが document → window へ伝播する際に実際に発火してしまう。）

### 対処法

**このブロックを丸ごと削除するだけ。** 副作用はない。

削除対象は `script.js` の以下の部分（`//雲のアニメーション` コメントの直前）：

```js
        window.addEventListener('DOMContentLoaded', () => {
            const mv = document.querySelector('.mainvisual');
            if (mv) {
                // 現在の画面の高さを取得して、pxで直接指定する
                const vh = window.innerHeight;
            }

        });
```

---

## C. Google Fonts の CSS が 86KB 🟡

### 症状

「使用していない CSS の削減 86.4 KiB」。指摘対象は自社CSSではなく **`fonts.googleapis.com/css2`**。

### 原因

日本語Webフォントは文字数が多いため、Googleが `unicode-range` で約120個のサブセットに分割している。
現在3書体を読み込んでいるので：

```
M PLUS Rounded 1c        700       … 約120個
Zen Kaku Gothic Antique  400 / 700 … 約240個
────────────────────────────────────
合計 @font-face 368個 → CSS本体 337KB（gzip後 86KB）
```

実際にダウンロードされる woff2 は使う文字の分だけだが、**この86KBのCSSは毎回必ず落ちてくる**。

なお現在は `media="print" onload="this.media='all'"` を使っているので**描画はブロックしていない**。
影響するのは通信量と接続確立のコストのみ。

### 対処法（効果の大きい順）

**方法1：必要な @font-face だけ `<head>` にインラインで埋め込む（効果大・手間中）**

1. 下記URLをブラウザで開き、CSSを取得する（User-Agent はモダンブラウザのままでよい）
   ```
   https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@700&family=Zen+Kaku+Gothic+Antique:wght@400;700&display=swap
   ```
2. `unicode-range` が以下を含む @font-face だけ残す（残り約340個は捨てる）
   - `U+0000-00FF`（英数字・記号）
   - `U+3000-30FF`（ひらがな・カタカナ・句読点）
   - `U+FF00-FFEF`（全角英数）
   - よく使う漢字ブロック（`U+4E00` 付近から数ブロック）
3. 残したルールを `css/fonts.css` として保存し、`simatwuku_inline_reset_css()` と同じ要領で
   `<head>` に直接埋め込む。`src` の `fonts.gstatic.com` URL はそのまま使ってよい

- 効果: 86KB の CSS ダウンロードと `fonts.googleapis.com` への接続が丸ごと消える
- リスク: 残さなかったブロックの珍しい漢字が游ゴシック等のフォールバックで表示される

**方法2：書体を3つ→2つに減らす（効果中・手間小）**

`Zen Kaku Gothic Antique` の 700 をやめ、ブラウザの合成太字に任せる。

```php
// header.php の Google Fonts URL
family=M+PLUS+Rounded+1c:wght@700&family=Zen+Kaku+Gothic+Antique:wght@400
```

- 効果: CSS が 86KB → 約57KB（3分の1減）
- リスク: `font-weight: bold` の本文（SCSS内で18箇所）の見た目が少し変わる

**方法3：何もしない（許容案）**

描画はブロックしていないので、体感速度への影響は限定的。
「使用していない CSS」の指摘は残るが、実害は通信量86KBのみ。

---

## D. モバイルの描画遅延 🟡

### 症状

モバイルの LCP 内訳（LCP要素はメインコピー画像 `main-title.webp`）：

| サブパート | 時間 |
|---|---|
| サーバー応答（TTFB） | 0 ミリ秒 |
| リソース読み込みの遅延 | 220 ミリ秒 |
| リソースの読み込み時間 | 190 ミリ秒 |
| **要素のレンダリングの遅延** | **1,950 ミリ秒** |

**画像はダウンロードし終わっているのに、描画されるまで約2秒かかっている。**
TBTが0msなのでJavaScriptの実行時間ではなく、CPUをスロットリングしたモバイル環境での
**描画・合成コスト**が原因。

### 原因の候補（重い順の推測）

1. **波のSVG 6枚** — `.wave svg` は幅2400px、`will-change: transform` 付きで常時アニメーション。
   しかも `.mainvisual__circle`（`border-radius: 50%` + `overflow: hidden`）の中にあるため、
   **円形クリップをかけた合成を毎フレーム行っている**。モバイルGPUには重い
2. **背景グラデーションのアニメーション** — `.mainvisual` は画面全体を覆う `position: fixed` 要素。
   `background-size: 400% 400%` の `background-position` を15秒かけて動かしている。
   `background-position` は合成できないプロパティなので、**全画面を毎フレーム再描画**している
3. **雲レイヤー2枚** — `.cloud-layer` は幅140% × 高さ130vh の繰り返し背景が2枚

### 対処法（効果と見た目のバランス順）

**方法1：`will-change` を外す（効果中・手間小・見た目の変化なし）**

`sass/style.scss` の `.mainvisual__waves .wave svg` から `will-change: transform;` を削除する。

`will-change` は「このプロパティが変わるから先にレイヤーを作っておいて」という指示だが、
2400px × 6枚 をいきなりGPUメモリに載せるためモバイルでは逆効果になりやすい。
アニメーション自体は `transform` なので、指定を外してもブラウザが自動でレイヤー化する。

**方法2：グラデーションアニメーションを合成可能にする（効果大・手間中・見た目ほぼ同じ）**

`background-position` を動かす代わりに、疑似要素にグラデーションを持たせて `transform` で動かす。

```scss
.mainvisual {
    background: $blue-main;              // アニメーションしない下地
    position: fixed;
    overflow: hidden;

    &::before {
        content: "";
        position: absolute;
        inset: -50%;                      // 動かす余白を確保
        background: linear-gradient(-45deg, #ff8d6a, #ff428a, #90e1ff, #92ffe6);
        background-size: 100% 100%;
        animation: gradientSlide 15s ease infinite;
        will-change: transform;
        z-index: -1;
    }
}

@keyframes gradientSlide {
    0%, 100% { transform: translate(0, 0); }
    50%      { transform: translate(10%, 10%); }
}
```

`transform` は GPU で合成できるため、再描画が発生しなくなる。

> ⚠️ 見た目が完全に同じにはなりません。適用したら必ず目視確認してください。

**方法3：モバイルでは装飾を減らす（効果大・手間小・見た目が変わる）**

```scss
@media (max-width: 767px) {
    // 波を3組 → 1組に
    .mainvisual__waves > div:nth-child(n + 2) { display: none; }
}

// OSで「視差効果を減らす」をONにしている人向け（アクセシビリティ対応にもなる）
@media (prefers-reduced-motion: reduce) {
    .mainvisual,
    .mainvisual__waves .wave svg,
    .mainvisual__circle__image {
        animation: none !important;
    }
}
```

---

## E. 画像のさらなる圧縮 🟢

### 症状

「画像配信を改善する」モバイル 260 KiB / デスクトップ 426 KiB。

### 現状

自ドメインの画像は合計 **808 KiB**（改善前は 4,816 KiB）。指摘は多数の画像に薄く分散しており、
1枚あたり数十KBの削減提案。**すでに逓減領域**に入っている。

残っている主なもの：

| 画像 | 現在 | 削減提案 | 用途 |
|---|---|---|---|
| `image/24.webp` | 191 KB | 最大 92 KB | 景色の例（松林と砂地で情報量が多い写真） |
| `image/28.webp` | 162 KB | 約 30 KB | 「島トゥクとは」の背景（`blur(3px)` がかかる） |
| `image/13.webp` | 127 KB | 約 23 KB | 景色の例 |
| メインビジュアルの写真 | 各 7〜42 KB | 各 5〜20 KB | 円の中を漂う装飾写真 |

### 対処法

**方法1：`28.webp` をもっと潰す（違和感なし・おすすめ）**

`blur(3px)` がかかる背景なので、画質を落としても見た目に出ない。

```bash
cd img
cwebp -q 40 -m 6 -resize 700 0 \
  "/Users/it252118/Local Sites/simatwuku/img-originals-backup/image/28.jpg" \
  -o image/28.webp
```

**方法2：景色の例をさらに圧縮**

```bash
for n in 24 13; do
  cwebp -q 58 -m 6 -resize 800 0 \
    "/Users/it252118/Local Sites/simatwuku/img-originals-backup/image/$n.jpg" \
    -o "image/$n.webp"
done
```

適用後は必ず実物を目視確認すること。`24.webp` は松の葉と砂地のディテールが多く、
品質を落とすとノイズが目立ちやすい。

> 元画像はすべて `/Users/it252118/Local Sites/simatwuku/img-originals-backup/` にあるので、
> 何度でもやり直せます。

---

## F. キャッシュ保存期間 🟢

### 症状

「効率的なキャッシュ保存期間を使用する 16 KiB」。

### 現状

```
css/style.css       cache-control: max-age=2592000  （30日）
script.js           cache-control: max-age=2592000  （30日）
img/*.webp          cache-control: max-age=31536000 （1年）
```

CSS と JS が30日、画像は1年。大きな問題はないが、Lighthouse は1年を推奨する。

### 対処法

サーバー（XREA / LiteSpeed）の設定なのでテーマからは変更できない。
WordPressルートの `.htaccess` に追記する：

```apache
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType text/css                "access plus 1 year"
    ExpiresByType application/javascript  "access plus 1 year"
    ExpiresByType image/webp              "access plus 1 year"
</IfModule>
```

CSS と JS は `?ver=` が付いて配信されるので、1年にしても更新が反映されなくなる心配はない。

> ⚠️ `.htaccess` を壊すとサイト全体が500エラーになります。編集前に必ずバックアップを取り、
> 変更後すぐに表示確認してください。

---

## G. 広告バナーに width/height がない ⚫ 対応不可

### 症状

「画像要素で width と height が明示的に指定されていません」

### 原因

指摘対象は `cache1.value-domain.com/vd_468x60.png`。
`footer.php` に埋め込まれている XREA（無料ホスティング）の広告スクリプトが挿入している画像で、
**こちらのHTMLではない**。

```php
<div class="ad">
<script type="text/javascript" src="https://cache1.value-domain.com/xa.j?site=..."></script>
</div>
```

### 対処法

- スクリプト自体は 223 バイトと軽量で、実害はほぼない
- 削除するとホスティングの利用規約に抵触する可能性があるため、**触らないことを推奨**
- 有料プランに移行すれば広告なしになり、この指摘も消える

---

## 3. おすすめの進め方

### ~~すぐやる（15分・リスクなし）~~ ✅ 2026-08-05 完了

1. ~~**B** 死にコードの削除~~ → 完了
2. ~~**A** CSS配信の修正~~ → 完了（ハッシュ照合方式）
3. ~~**E** `28.webp` の再圧縮~~ → 完了（162KB → 83KB）

**次のデプロイで反映されます。**

### 次にやる（1〜2時間・要目視確認）

4. **D-方法1** `will-change: transform` の削除
5. **D-方法3** `prefers-reduced-motion` 対応（アクセシビリティ改善にもなる）
6. **D-方法2** グラデーションアニメーションの作り直し

モバイルの「レンダリング遅延1,950ms」に効くのはここ。ただし見た目が変わるため、
1つずつ適用して都度スクリーンショットで確認すること。

### 判断が必要（要相談）

7. **C** Google Fonts の扱い — 見た目を優先するか、86KBを削るか
8. **F** `.htaccess` の編集 — サーバー設定を触ってよいか

---

## 4. モバイル61点をどう捉えるか

現時点でモバイルの **TBT は 0ms、CLS は 0** です。ユーザーの操作を妨げる問題や、
表示がガタつく問題は**すでに解消済み**です。

残っている FCP 5.4秒 / LCP 7.6秒 は、Lighthouse が
「低速4G（1.6Mbps）＋ CPU 4倍遅い端末」という**かなり厳しい条件をシミュレート**した結果です。
実機の 4G/5G ではこれよりずっと速く表示されます。

このサイトはメインビジュアルの常時アニメーション（波・グラデーション・雲・漂う写真）が
デザインの核になっており、**その描画コストが61点の主因**です。
上記 D を実施すれば70点台は狙えますが、80点台を目指すとアニメーションの削減が避けられません。

> **判断のポイント:** サイトの魅力とスコアのどちらを優先するか。
> 個人的には D-方法1（`will-change` 削除）と D-方法3（`prefers-reduced-motion`）まで実施し、
> 見た目を変える D-方法2 以降は様子を見る、というバランスをおすすめします。

---

## 5. 実施内容（2026-08-05）

### A. CSS配信 — ハッシュ照合方式に変更

「常に圧縮版を使う（作り忘れで古いCSSが出る）」「圧縮版をやめる（スコアを捨てる）」の
どちらも欠点があったため、**両方の欠点をなくす方式**にしました。

**仕組み**

1. `build-css.sh`（新規）が `style.min.css` を生成し、その先頭に生成元のMD5を埋め込む
   ```
   /*!src:16c672c1216f7f79329f97eefcca9ed5*/.mainvisual{...}
   ```
2. `functions.php` は配信時にこのハッシュを読み、現在の `style.css` のMD5と照合する
3. 一致すれば圧縮版、不一致なら通常版を配信する

**これで解決すること**

| 状況 | 結果 |
|---|---|
| 正常時 | 圧縮版を配信 ✅ |
| FTPで更新日時が逆転した | ハッシュは変わらないので**圧縮版のまま** ✅ |
| `build-css.sh` の実行を忘れた | 通常版に自動フォールバック（**古いCSSは絶対に出ない**）✅ |

ローカルで上記4パターンすべて動作確認済みです。

**運用手順（SCSSを編集したとき）**

```bash
# 1. SCSSをコンパイル（Live Sass Compiler が css/style.css を更新）
# 2. 圧縮版を作り直す
cd /Users/it252118/Local\ Sites/simatwuku/app/public/wp-content/themes/simatwuku
./build-css.sh
# 3. css/style.css と css/style.min.css の両方をアップロード
```

> 忘れても表示は壊れません。通常版が配信されるだけで、Lighthouseの
> 「CSS の最小化」指摘が戻るのみです。

### B. 死にコードの削除

`script.js` から、使われていない `window.innerHeight` の読み取りブロックを削除。
→ 強制リフロー 81ms が解消。

### E. 背景画像の再圧縮

`img/image/28.webp`（「島トゥクとは」の背景）を **162KB → 83KB** に。
`blur(3px)` がかかる背景なので、品質40・幅700pxまで落としても見た目は変わりません。
ブラウザで目視確認済み。

### 検証結果

- 全ページ（トップ / spot / root / q&a）で **404ゼロ・画像崩れゼロ**
- 配信CSSが `style.min.css` になっていることを確認
- ページ内のリンク・スライダー・アニメーションの動作に変化なし

### 変更ファイル

```
M  functions.php            CSS配信をハッシュ照合方式に
M  script.js                死にコード削除
M  img/image/28.webp        162KB -> 83KB
M  css/style.min.css        ハッシュコメント付きで再生成
A  build-css.sh             圧縮版CSSのビルドスクリプト（新規）
```

---

## 参考：これまでの改善内容

| 実施内容 | 効果 |
|---|---|
| 全画像を WebP 化＋表示サイズに合わせてリサイズ | 4,816 KiB → 808 KiB |
| LCP画像（メインコピー）を `srcset` でスマホ用に出し分け | 読み込み 300ms → 190ms |
| メインビジュアルの写真12枚に `fetchpriority="low"` | LCP画像との帯域競合を解消 |
| リセットCSSをCDNから `<head>` インラインへ | 外部ドメインへの接続を1つ削減 |
| WordPress の絵文字スクリプトを停止 | リクエスト1件削減 |
| `puka-puka` を `margin-top` → `translate` に変更 | 毎フレームのレイアウト再計算を解消 |
| スクロール処理を `requestAnimationFrame` でまとめる | スクロール中の強制リフローを解消 |
| 全 `<img>` に `width` / `height` を付与 | CLS 0 を達成 |
| 未参照画像104件を削除 | テーマ 58MB → 3.5MB |
| OGP画像（404だった `ogp.jpg`）を生成 | SNSシェア時のサムネイル表示を修復 |
