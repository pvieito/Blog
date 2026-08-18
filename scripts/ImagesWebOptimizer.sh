#!/bin/bash
set -euo pipefail

SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
REPO_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

SRGB_PROFILE="$SCRIPT_DIR/Resources/sRGB.icc"
AVIF_QUALITY=60
WEBP_QUALITY=80

while IFS= read -r -d '' raw_path; do
    dir="$(dirname "$raw_path")"
    name="$(basename "$raw_path" .raw.png)"

    web_png_path="$dir/$name.web.png"
    web_avif_path="$dir/$name.web.avif"
    web_webp_path="$dir/$name.web.webp"

    echo "Optimizing ${raw_path#"$REPO_DIR/"}…"

    magick "$raw_path" \
        -profile "$SRGB_PROFILE" \
        -strip -depth 8 \
        "$web_png_path"
    echo "  Output: ${web_png_path#"$REPO_DIR/"}"

    magick "$web_png_path" \
        -quality "$AVIF_QUALITY" \
        "$web_avif_path"
    echo "  Output: ${web_avif_path#"$REPO_DIR/"}"

    magick "$web_png_path" \
        -quality "$WEBP_QUALITY" \
        "$web_webp_path"
    echo "  Output: ${web_webp_path#"$REPO_DIR/"}"
done < <(find "$REPO_DIR" -name "*.raw.png" -not -path "*/_site*/*" -print0)

echo "Done."
