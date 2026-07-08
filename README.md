# Alostora Platform

Premium, production-ready **WordPress theme** for [alostorajo.com](https://alostorajo.com) — an
Arabic (RTL) animated-history learning platform.

The theme is built on the **Hello Elementor philosophy**: a thin, fast core that ships a complete
design system and hands page composition to **Elementor Pro**, with deep integrations for
**LifterLMS** (courses) and **VdoCipher** (secure video). It does **not** bundle or rebuild any of
those plugins.

## Repository layout

| Path           | Purpose                                                                    |
| -------------- | -------------------------------------------------------------------------- |
| `theme/`       | The Alostora WordPress theme (the deliverable).                            |
| `docs/`        | Architecture and integration documentation.                                |
| `design/`      | Design blueprint and design-token specification.                           |
| `references/`  | Brand identity references (logo, palette).                                 |
| `assets/`      | Shared source assets used outside the theme.                               |
| `prompts/`     | Build brief and specification history.                                     |
| `scripts/`     | One-off generators (self-hosted fonts, Elementor exports).                 |

## Quick start (theme development)

```bash
cd theme
npm install      # install the SCSS/build toolchain
npm run build    # compile SCSS -> CSS (+ autoprefix + RTL)
npm run watch    # recompile on change during development
```

Then symlink or copy `theme/` into `wp-content/themes/alostora` and activate it. See
[`docs/architecture.md`](docs/architecture.md) for the full picture and
[`docs/elementor-integration.md`](docs/elementor-integration.md) for importing the homepage.

## Requirements

- WordPress ≥ 6.4, PHP ≥ 8.0
- Elementor + Elementor Pro
- LifterLMS
- VdoCipher (plugin) for secure video
- Node.js ≥ 18 (build tooling only; not required at runtime)
