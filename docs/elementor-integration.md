# Elementor Integration

The theme follows Hello Elementor: it registers Theme Builder **locations** and lets Elementor Pro
compose the header, footer, single, archive and page layouts. The homepage is built **entirely in
the Elementor Pro Theme Builder** — there is no `front-page.php`.

## What the theme registers

- `add_theme_support( 'elementor' )` (in `inc/setup.php`).
- All core Theme Builder locations via `elementor/theme/register_locations`
  (`register_all_core_location()`), so header/footer/single/archive/single-page are Elementor-owned
  (`inc/elementor.php`).
- Brand fonts (`IBM Plex Sans Arabic`, `Tajawal`) added to Elementor's font picker as `system`
  fonts (no Google Fonts request; the theme self-hosts them).
- An admin notice on the Themes screen pointing to the bundled kit.

Each classic template calls `elementor_theme_do_location( '…' )` and only renders the theme's
component fallback when no Elementor template is assigned. This means the site works before any
Elementor template exists, and is fully overridden once they are.

## Bundled exports (`theme/elementor/`)

| File                                  | Import via                                             |
| ------------------------------------- | ------------------------------------------------------ |
| `kits/alostora-kit.json`              | Elementor global styles (colors/fonts/typography)      |
| `kits/homepage.json`                  | Templates → Import Templates (page)                    |
| `theme-builder/header.json`           | Templates → Theme Builder → Import (header)             |
| `theme-builder/footer.json`           | Templates → Theme Builder → Import (footer)             |

### Homepage sections (blueprint mapping)

The `homepage.json` template reproduces the approved blueprint using native Elementor widgets
(headings, text, buttons, images) for editable content, plus theme shortcodes for dynamic blocks:

1. **Hero** — headline + accent line, paragraph, primary/ghost CTAs, character media (`.alostora-hero`).
2. **Statistics** — `[alostora_statistics]` floating strip with count-up.
3. **Featured courses** — `[lifterlms_courses]` rendered as brand course cards.
4. **How we teach** — four `[alostora_step_card]` items.
5. **CTA banner + footer** — Elementor footer template.

Because sections use native widgets, all copy stays editable in Elementor. The theme only supplies
styling (via global classes) and the dynamic shortcodes.

## Global styles

`theme.json` and the Elementor kit intentionally share the same tokens (navy `#14315E`, blue
`#2C6FBF`, orange `#F39019`/`#F5A623`). After importing the kit, Elementor's global colors/fonts map
1:1 to the design system, so editors pick "Primary/Accent" rather than hex values.

> Regenerate the exports with `node scripts/gen-elementor.js` after changing the blueprint.
