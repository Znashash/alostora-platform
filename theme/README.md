# Alostora Theme

Premium RTL WordPress theme for the Alostora learning platform. Built on the Hello Elementor
philosophy with LifterLMS and VdoCipher integrations.

## Development

```bash
npm install        # build toolchain (dart-sass, postcss, autoprefixer, rtlcss)
npm run build      # compile SCSS -> CSS, autoprefix, generate RTL (production, minified)
npm run build:dev  # same, non-minified (readable output)
npm run watch      # recompile SCSS on change
```

Compiled CSS is committed under `assets/css/` so the theme runs without a build step in production.
Rebuild after editing anything in `assets/scss/`.

- **Styles:** author in `assets/scss/` (7-1 architecture). Never edit `assets/css/` by hand.
- **Fonts:** regenerate self-hosted subsets with `node ../scripts/fetch-fonts.js`.
- **Elementor exports:** regenerate with `node ../scripts/gen-elementor.js`.

## Structure & integrations

See the repository [`docs/`](../docs/): `architecture.md`, `elementor-integration.md`,
`lifterlms-vdocipher.md`.

## Standards

WordPress Coding Standards; text domain `alostora`; all output escaped; all strings translatable.
