#!/bin/bash
#
# css/style.css から圧縮版 css/style.min.css を作る。
#
# SCSS を編集して css/style.css がコンパイルされたあと、デプロイ前に実行すること。
#   ./build-css.sh
#
# 先頭に生成元のMD5を /*!src:...*/ として埋め込む。
# functions.php はこのハッシュが style.css と一致するときだけ圧縮版を配信するので、
# 実行を忘れても古いCSSが表示されることはない（通常版にフォールバックするだけ）。
#
set -eu
cd "$(dirname "$0")"

SRC="css/style.css"
OUT="css/style.min.css"

if [ ! -f "$SRC" ]; then
    echo "エラー: $SRC が見つかりません。先にSCSSをコンパイルしてください。" >&2
    exit 1
fi

npx --yes esbuild "$SRC" --minify --outfile="$OUT.tmp" --log-level=warning

# 生成元のハッシュを先頭に埋め込む
if command -v md5 >/dev/null 2>&1; then
    HASH=$(md5 -q "$SRC")          # macOS
else
    HASH=$(md5sum "$SRC" | cut -d' ' -f1)   # Linux
fi
printf '/*!src:%s*/' "$HASH" | cat - "$OUT.tmp" > "$OUT"
rm -f "$OUT.tmp"

printf '%s (%d B) -> %s (%d B)  src:%s\n' \
    "$SRC" "$(wc -c < "$SRC")" "$OUT" "$(wc -c < "$OUT")" "$HASH"
