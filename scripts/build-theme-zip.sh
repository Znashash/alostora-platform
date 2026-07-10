#!/usr/bin/env bash
#
# Build a clean, production-ready theme ZIP from theme/ only.
#
# The archive contains a single top-level `alostora/` directory (the theme slug)
# and EXCLUDES development-only tooling and sources:
#   - node_modules/           (build dependencies)
#   - assets/scss/            (SCSS sources; compiled CSS is shipped)
#   - package.json, package-lock.json, postcss.config.js
#   - .gitignore
#
# Repo-root dev folders (scripts/, docs/, prompts/, references/) are never part
# of theme/, so they are excluded by construction.
#
# Output: dist/alostora-theme.zip
#
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
SRC="$ROOT/theme"
DIST="$ROOT/dist"
STAGE="$(mktemp -d)"
DEST="$STAGE/alostora"

mkdir -p "$DEST" "$DIST"

# Copy the theme, skipping heavy/dev-only trees, using tar with excludes so
# node_modules is never even read.
tar \
  --exclude='./node_modules' \
  --exclude='./assets/scss' \
  --exclude='./package.json' \
  --exclude='./package-lock.json' \
  --exclude='./postcss.config.js' \
  --exclude='./.gitignore' \
  -C "$SRC" -cf - . | tar -C "$DEST" -xf -

rm -f "$DIST/alostora-theme.zip"
( cd "$STAGE" && zip -r -q -X "$DIST/alostora-theme.zip" alostora )
rm -rf "$STAGE"

echo "Built $DIST/alostora-theme.zip"
unzip -l "$DIST/alostora-theme.zip" | tail -n +2 | awk '{print $4}' | grep -E '^alostora/(node_modules|assets/scss|package\.json|postcss)' >/dev/null \
  && { echo "ERROR: dev files leaked into ZIP"; exit 1; } || echo "Verified: no dev tooling in ZIP"
