#!/bin/sh
# Builds the files to upload to SiteGround into dist/ (gitignored):
#   lejwana-theme.zip     theme, with the shared css/js/images copied in
#   lejwana-core.zip      plugin
#   lejwana-migration.zip one-off content move, to run once on the new site and then delete
set -e
cd "$(dirname "$0")/.."
sh scripts/sync-theme-assets.sh
rm -rf dist && mkdir dist
(cd wp/theme && zip -qr ../../dist/lejwana-theme.zip lejwana -x '*.DS_Store')
(cd wp/plugin && zip -qr ../../dist/lejwana-core.zip lejwana-core -x '*.DS_Store')
zip -qr dist/lejwana-migration.zip scripts/migrate-content.php the-southern-cut/*.html -x 'the-southern-cut/.DS_Store'
ls -la dist
