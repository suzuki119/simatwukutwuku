# 島トゥク WordPress → 静的HTML 移行仕様書

作成日: 2026-04-16

---

## 1. 移行方針

| 項目 | 内容 |
|------|------|
| 移行種別 | WordPress テーマ → 静的HTML/CSS/JS |
| ホスティング | 現在: xrea（`simatwuku.s323.xrea.com`）→ そのまま利用可 |
| CMS | 撤廃（お知らせ機能の扱いは後述） |
| フォーム | 外部サービスへ代替（後述） |
| 既存CSS/JS | `css/style.css`・`script.js` をそのまま流用 |
| 削除 | お問い合わせ、お知らせ（News）の削除 |
---

## 2. ページ一覧・ファイル対応表

| 現在のWordPressテンプレート | 移行後のHTMLファイル | URL |
|---|---|---|
| `index.php` | `index.html` | `/` |
| `page-spot.php` | `spot/index.html` | `/spot/` |
| `page-root.php` | `root/index.html` | `/root/` |
| `page-appointment.php` | `appointment/index.html` | `/appointment/` |
| `page-qa.php` | `q&a/index.html` ※1 | `/q&a/` |
| `page-info.php` | `info/index.html` | `/info/` |
| `page-access.php` | `access/index.html` | `/access/` |
| `page-news-archive.php` | `news-archive/index.html` | `/news-archive/` |
| `single.php` | `news/[記事名]/index.html` | `/news/[記事名]/` |
| `404.php` | `404.html` | - |
| `header.php` | 各HTMLに直接記述 | - |
| `footer.php` | 各HTMLに直接記述 | - |

> ※1 `q&a` はURLに特殊文字を含むため `qa/index.html`（`/qa/`）へのリネームを推奨

---

## 3. WordPress 機能の代替対応表

| WordPress の記述 | 移行後の対応 |
|---|---|
| `get_template_directory_uri()` | 相対パス（例: `../img/` または `/img/`）に置換 |
| `home_url('/')` | `/` に固定 |
| `home_url('/spot')` | `/spot/` に固定 |
| `get_theme_mod('price_min', '2000')` | HTMLに直接記述（例: `2000`）|
| `do_shortcode('[mwform_formkey key="23"]')` | 外部フォームサービスへ置換（後述）|
| `wp_head()` | CSS・JS・metaタグを手動で記述 |
| `wp_footer()` | JS読み込みタグを手動で記述 |
| `get_header()` / `get_footer()` | 各HTMLにheader・footerを直接記述 |
| `WP_Query`（お知らせ一覧） | 静的HTMLページとして個別作成 |
| `the_title()` / `the_content()` | 静的HTMLとして記述 |
| `get_the_date()` | `<time datetime="YYYY-MM-DD">` で直書き |
| `the_post_thumbnail()` | `<img src="...">` で直書き |
| `paginate_links()` | ページ分割が必要な場合のみ手動でリンク作成 |
| `body_class()` | 各ページに対応するclassを手動付与（例: `class="page-spot"`）|
| `wp_json_encode()`（JSON-LD） | `<script type="application/ld+json">` に直書き |

---

## 4. 各ページの移行詳細

### 4-1. トップページ（`index.php` → `index.html`）

**動的要素:**
- お知らせ最新4件（`WP_Query`）→ 手動で最大4件のHTMLを記述。更新時は手動編集。

**`<head>` に必要なタグ（全ページ共通）:**
```html
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="日間賀島をトゥクトゥクで巡る観光フォトサービス「島トゥク」。...">
<meta property="og:title" content="日間賀島を巡る観光フォトサービス｜島トゥク">
<meta property="og:description" content="...">
<meta property="og:url" content="https://simatwuku.s323.xrea.com/">
<meta property="og:image" content="https://simatwuku.s323.xrea.com/img/ogp.jpg">
<meta property="og:type" content="website">
<meta property="og:site_name" content="島トゥク">
<meta name="twitter:card" content="summary_large_image">
<link rel="canonical" href="https://simatwuku.s323.xrea.com/">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/the-new-css-reset/css/reset.min.css">
<link rel="stylesheet" href="/css/style.css">
```

**JSON-LD（トップページのみ）:**
```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TouristAttraction",
  "name": "島トゥク",
  "telephone": "080-4223-3450",
  "address": {
    "@type": "PostalAddress",
    "addressLocality": "日間賀島",
    "addressRegion": "愛知県",
    "addressCountry": "JP"
  },
  "url": "https://simatwuku.s323.xrea.com/",
  "sameAs": [
    "https://www.instagram.com/ajihama.himakajima",
    "https://ameblo.jp/katu-sayo/"
  ]
}
</script>
```

---

### 4-2. スポット（`page-spot.php` → `spot/index.html`）

- 動的要素なし。全コンテンツを静的HTMLに変換。
- 画像パスを `/img/view/IMG_XXXX.jpg` 形式に統一。
- `<h1>観光スポット</h1>` を使用（内部ページのためロゴはh1にしない）。

---

### 4-3. コース（`page-root.php` → `root/index.html`）

- 動的要素なし。全コンテンツを静的HTMLに変換。
- `<picture>` タグの `srcset` パスを絶対パスに変更。

---

### 4-4. 予約（`page-appointment.php` → `appointment/index.html`）

- `get_theme_mod('price_min')` 等 → HTMLに料金を直書き。
- 料金変更の際は `appointment/index.html` と `index.html`（トップ）を手動編集。

---

### 4-5. Q&A（`page-qa.php` → `qa/index.html`）

- 動的要素なし。全コンテンツを静的HTMLに変換。

---

### 4-6. アクセス（`page-info.php` → `info/index.html`）

- 動的要素なし。GoogleマップのiframeをそのままHTMLに記述。

---

### 4-7. お問い合わせ（`page-access.php` → `access/index.html`）

**最重要変更点: フォームの代替**

現在: WordPressプラグイン `MW WP Form`（`[mwform_formkey key="23"]`）を使用

**推奨代替手段:**

| サービス | 費用 | 特徴 |
|---|---|---|
| **Formspree** | 無料プランあり | HTMLフォームにactionを指定するだけ。メール受信可能 |
| **Netlify Forms** | 無料プランあり | Netlifyホスティング時のみ使用可 |
| **Google Forms** | 無料 | iframeで埋め込み可。Googleスプレッドシートに集計 |

**Formspreeを使う場合の実装例:**
```html
<form action="https://formspree.io/f/[YOUR_FORM_ID]" method="POST">
  <input type="text" name="name" placeholder="お名前" required>
  <input type="email" name="email" placeholder="メールアドレス" required>
  <textarea name="message" placeholder="お問い合わせ内容" required></textarea>
  <button type="submit">送信</button>
</form>
```

---

### 4-8. お知らせ一覧（`page-news-archive.php` → `news-archive/index.html`）

**現在の機能:**
- 投稿を9件ずつ表示
- ページネーションあり

**移行後の対応:**
- 記事数が少ない（〜20件程度）場合 → 1ページに全件記載（ページネーション不要）
- 記事数が多い場合 → `news-archive/index.html`（1ページ目）、`news-archive/page2/index.html`（2ページ目）...と手動分割

**注意:** 今後の記事追加は手動でHTMLを編集する必要あり。更新頻度が高い場合はWordPressの継続を検討。

---

### 4-9. お知らせ個別記事（`single.php` → `news/[slug]/index.html`）

- 各記事を個別のHTMLファイルとして作成。
- 例: `news/2025-summer-info/index.html`

---

### 4-10. 404ページ（`404.php` → `404.html`）

- `.htaccess` に以下を追記してカスタム404を設定:
```apache
ErrorDocument 404 /404.html
```

---

## 5. 共通コンポーネント（header / footer）の扱い

WordPressの `get_header()` / `get_footer()` に相当する仕組みが静的HTMLにはないため、以下から選択する。

### 案A: 各HTMLに直接コピー（推奨・シンプル）
- ページ数が少ないため、各HTMLファイルにheader/footerを直接記述。
- 変更時は全ファイルを一括編集（エディタの「フォルダ内一括置換」機能を利用）。

### 案B: JavaScript でインクルード
- `header.html` / `footer.html` を別ファイルとして作成。
- 各ページで `fetch()` を使って読み込む。
- **デメリット:** JavaScriptが無効の環境で表示されない。SEOへの影響あり（軽微）。

### 案C: 静的サイトジェネレーターを導入（11ty, Astroなど）
- テンプレート機能でheader/footerを共通化できる。
- ビルドステップが必要になるが保守性が高い。

**→ 現状のサイト規模（10ページ以下）であれば案Aが最もシンプルで推奨。**

---

## 6. アセット（CSS / JS / 画像）の扱い

| ファイル | 対応 |
|---|---|
| `css/style.css` | そのまま流用 |
| `script.js` | そのまま流用。`themeVars.themeUrl` の参照箇所を確認し、必要に応じて修正 |
| `img/` 以下全画像 | そのまま流用 |
| `css/reset.css` | CDN（`cdn.jsdelivr.net`）のまま流用 |
| Google Fonts | `<link>` タグをそのまま流用 |

**`script.js` の確認ポイント:**
```javascript
// WordPressで渡していた themeVars.themeUrl の利用箇所を探す
// → 静的HTMLでは window.themeUrl = '/'; のように直接定義するか、
//    パスをハードコードして wp_localize_script 相当の処理を削除する
```

---

## 7. SEO 対応

各ページに以下を手動で設定する。

| 要素 | 設定方法 |
|---|---|
| `<title>` | `[ページ名] &#124; 島トゥク` 形式で各ページに記述 |
| `meta description` | 各ページのコンテンツに合わせて記述 |
| OGP タグ | 各ページのURLと内容に合わせて記述 |
| canonical | `<link rel="canonical" href="https://simatwuku.s323.xrea.com/[path]/">` |
| JSON-LD | トップページのみ `<script type="application/ld+json">` で記述 |

---

## 8. 移行作業手順

```
Step 1: 現在のWordPressサイトのファイル・コンテンツをエクスポート
   └ 画像 (`img/` フォルダ全体)
   └ CSS (`css/style.css`)
   └ JS (`script.js`)
   └ お知らせ記事の本文・日付・画像を一覧化

Step 2: トップページ (index.html) を作成
   └ header.php + index.php + footer.php を統合
   └ PHP記述をHTMLに置換
   └ ブラウザで表示確認・JavaScript動作確認

Step 3: 各固定ページを作成（order: spot → root → appointment → qa → info → access）

Step 4: お問い合わせフォームを外部サービスで設置・テスト送信確認

Step 5: お知らせ記事をHTMLファイルとして作成
   └ news-archive/index.html（一覧）
   └ news/[slug]/index.html（個別記事）

Step 6: 404.html を作成・.htaccess に ErrorDocument 設定

Step 7: 全ページのSEOタグ（title / description / OGP / canonical）を確認

Step 8: サーバーへアップロード・動作確認
   └ リンク切れチェック
   └ フォーム送信テスト
   └ スマートフォン表示確認
   └ Google Search Console でURL検査
```

---

## 9. 移行後に失われる機能と対応方針

| 機能 | 現状 | 移行後 |
|---|---|---|
| お知らせ投稿・管理 | WordPress管理画面から追加 | HTMLファイルを手動作成・編集 |
| 料金のカスタマイザー管理 | WordPress管理画面から変更 | HTMLファイルを直接編集 |
| お問い合わせフォーム | MW WP Form プラグイン | Formspree 等の外部サービス |
| ページネーション | WP_Query で自動 | 手動でページ分割 |
| アクセス解析 | プラグイン等 | Google Analytics タグを手動設置 |

---

## 10. 推奨事項・注意点

- **`q&a` URL問題**: `&` はURLに使用不推奨。`/qa/` へのリネームを推奨し、旧URLから`.htaccess`でリダイレクト設定する。
- **xrea広告スクリプト**: `footer.php` にある xrea の広告スクリプトはサーバー側の条件次第。静的HTMLでもそのまま動作する可能性が高い。
- **WordPress を残す選択肢**: お知らせの更新頻度が高い場合は、WordPressをヘッドレスCMSとして利用し、静的HTMLと共存させる構成も検討する。
