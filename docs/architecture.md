# Alostora Theme — Architecture

## Philosophy

Alostora is a **standalone premium classic theme** built on the **Hello Elementor philosophy**: a
minimal, unopinionated core that provides the *design system* and integrations, while **Elementor
Pro** owns page composition. It is not a Hello child theme and not a multipurpose theme.

Separation of concerns:

| Layer                     | Owner                                                                 |
| ------------------------- | --------------------------------------------------------------------- |
| Design tokens & styles    | `theme.json` + compiled CSS from `assets/scss/`                       |
| Page composition          | Elementor Pro (Theme Builder locations + templates)                   |
| LMS logic & flows         | LifterLMS (theme only overrides presentation)                         |
| Secure video / DRM        | VdoCipher (theme only provides a responsive, styled container)        |

## Folder structure (`theme/`)

```
style.css            Theme header only (styles live in assets/scss)
functions.php        Bootstrap; requires inc/* modules
theme.json           v3 design tokens: palette, typography, spacing, layout
screenshot.png       1200×900 preview
header.php footer.php index.php page.php single.php archive.php search.php 404.php
                     Classic templates; each defers to an Elementor location, then a fallback
assets/
  scss/              7-1 SCSS: settings, tools, base, layout, components, utilities
  css/               Compiled output (main, main-rtl, fonts, critical, editor, editor-rtl)
  js/                ES modules (app.js + modules/*)
  fonts/             Self-hosted IBM Plex Sans Arabic + Tajawal (woff2, arabic+latin subsets)
  images/            logo.svg + icons/
inc/
  setup.php          Theme supports, menus, image sizes, i18n
  enqueue.php        Versioned, conditional asset loading + editor styles
  template-functions.php  Render helpers (logo, SVG, rating, body classes)
  components.php     Component loader + registry + Elementor shortcodes
  theme-options.php  Customizer: brand CTAs, socials, motion/perf toggles
  performance.php    Font preload, critical CSS, lazyload, WebP/AVIF, head cleanup
  elementor.php      Theme Builder locations + fonts + kit import notice
  lifterlms.php      LifterLMS support + VdoCipher shortcode/wrapper
components/          Reusable design-system components (see below)
templates/           Content partials loaded via get_template_part
lifterlms/           LifterLMS template overrides (auto-loaded by LifterLMS)
elementor/           Importable Elementor exports (kits/ + theme-builder/)
languages/           Translation template target
```

## Reusable components architecture

`template-parts/content` was replaced by a component system. Each component is a self-contained
folder `components/<slug>/<slug>.php` that renders one design-system block from a normalised
`$args` array, and is paired with `assets/scss/components/_<slug>.scss` (the single source of truth
for its markup + BEM classes).

Components: `header`, `footer`, `hero`, `buttons`, `course-card`, `statistics`, `video-card`,
`step-card`, `testimonial`.

Render from PHP with `alostora_component( 'hero', $args )` or as a string with
`alostora_get_component()`. Content-driven components are also exposed as **shortcodes**
(`[alostora_hero]`, `[alostora_statistics]`, `[alostora_step_card]`, `[alostora_video_card]`,
`[alostora_testimonial]`, `[alostora_button]`) so they can be dropped into Elementor while staying
fully editable.

## Design system & tokens

`theme.json` (v3) defines the palette, fluid typography (IBM Plex Sans Arabic body / Tajawal
headings), spacing scale, radii, shadows and layout sizes. At runtime the SCSS `base/_root.scss`
emits these as **semantic CSS custom properties** (`--color-bg`, `--color-text`, `--color-primary`,
…) mapped from raw brand tokens. This indirection is what makes dark mode a drop-in later (see
below).

## Dark mode readiness (not enabled)

All colours are consumed via semantic custom properties, never raw hex. A commented
`[data-theme="dark"]` scaffold in `base/_root.scss` lists exactly which semantic tokens to override.
Shipping dark mode later means: set `data-theme="dark"` on `<html>` (a small JS toggle) and fill in
those overrides — no component refactor required.

## Performance & Core Web Vitals

- Self-hosted `woff2` with `font-display: swap`; above-the-fold weights preloaded.
- Critical CSS inlined on first paint (`assets/css/critical.css`).
- `loading="lazy"` + `decoding="async"` defaults; hero LCP image is eager + `fetchpriority=high`.
- WebP/AVIF upload support and modern output-format negotiation.
- Deferred ES-module JS; emoji/oEmbed/head bloat removed for guests.
- Compiled CSS is minified and autoprefixed.

## RTL & responsiveness

- Authored mobile-first with **CSS logical properties** so LTR/RTL share one codebase.
- `main-rtl.css` / `editor-rtl.css` are generated with `rtlcss` and loaded automatically for RTL
  locales via `wp_style_add_data( …, 'rtl', 'replace' )`.
- Breakpoints align with Elementor's (mobile ≤767, tablet ≤1024, desktop ≥1025) so theme CSS and
  Elementor controls agree.

See [`elementor-integration.md`](elementor-integration.md) and
[`lifterlms-vdocipher.md`](lifterlms-vdocipher.md) for the integration specifics.
