#!/bin/sh
# Copies the shared site assets into the theme. The root css/, js/ and images/ stay the source of truth.
# Design comps listed in .gitignore are left out.
set -e
cd "$(dirname "$0")/.."
assets=wp/theme/lejwana/assets
mkdir -p "$assets"
rsync -a --delete css js "$assets"/
rsync -a --delete \
  --exclude mesh.png --exclude capabilities.png --exclude services.png \
  --exclude sky.png --exclude about.jpg --exclude diamond-pattern.svg \
  images "$assets"/
