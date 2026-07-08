# AGENTS.md

## Project

This repository contains **one deliverable: the `theme/` WordPress theme** ("Alostora"), a premium
RTL Arabic e-learning theme. It is a classic theme built on the Hello Elementor philosophy and
integrates with Elementor Pro, LifterLMS and VdoCipher. Do **not** add plugins or rebuild those
tools here.

- Full design: `docs/architecture.md`, `docs/elementor-integration.md`, `docs/lifterlms-vdocipher.md`.
- Styles are authored in `theme/assets/scss/` and **compiled** to `theme/assets/css/` (committed).
- PHP follows WordPress Coding Standards; text domain is `alostora`.

## Cursor Cloud specific instructions

### Build / lint (runs fully in this environment)

- Build CSS: from `theme/`, run `npm install` then `npm run build` (dart-sass + PostCSS/autoprefixer
  + rtlcss). Use `npm run build:dev` for readable output, `npm run watch` while iterating.
- **Always rebuild after editing `theme/assets/scss/`** — the compiled `theme/assets/css/*.css` is
  committed and served directly, so stale CSS ships if you forget.
- PHP lint (no external deps): `find theme -name '*.php' -not -path '*/node_modules/*' -print0 | xargs -0 -n1 php -l`.
  PHP is not preinstalled on a fresh VM — `sudo apt-get install -y php-cli` first if `php` is missing.
- Validate `theme/theme.json` and `theme/elementor/**/*.json` with `php -r 'json_decode(...)'` (they
  are plain JSON).
- Regeneration scripts (need network to Google Fonts): `node scripts/fetch-fonts.js` (self-hosted
  font subsets) and `node scripts/gen-elementor.js` (Elementor exports). Fonts/exports are committed,
  so you rarely need these.

### Running / previewing the theme

- There is **no standalone app** to run: the theme only renders inside a full WordPress install. A
  quick sanity check is possible with WordPress + the PHP built-in server (see below), but a faithful
  preview needs **Elementor Pro** and **VdoCipher**, which require paid licenses/keys and cannot be
  installed unattended. LifterLMS and (free) Elementor are installable from the wp.org registry.
- The homepage is composed in the **Elementor Pro Theme Builder**, not in PHP — import
  `theme/elementor/kits/homepage.json` + the `theme-builder/*.json` templates and apply the kit for
  global styles.
- LifterLMS auto-loads the overrides in `theme/lifterlms/`; VdoCipher videos use the
  `[alostora_vdocipher id="…"]` shortcode (theme provides only the responsive container).

### Gotchas

- RTL is handled by CSS logical properties plus generated `main-rtl.css`/`editor-rtl.css`; don't
  hardcode `left`/`right` — use logical properties so both directions stay in sync.
- Colours are consumed via semantic CSS custom properties (`--color-*`) for future dark mode; never
  use raw hex in component SCSS.
- `theme/node_modules/` is git-ignored; the compiled `assets/css/` is intentionally committed.
