# Alostora Design Tokens

Source of truth: `theme/theme.json` (build-time) → emitted as CSS custom properties in
`theme/assets/scss/base/_root.scss` (runtime, semantic).

## Color

| Token          | Hex        | Role                         |
| -------------- | ---------- | ---------------------------- |
| Navy (primary) | `#14315E`  | Headings, primary brand      |
| Deep navy      | `#0A1428`  | Hero / dark surfaces         |
| Blue           | `#2C6FBF`  | Secondary, links             |
| Orange         | `#F39019`  | Accent, primary CTA          |
| Amber          | `#F5A623`  | Accent gradient end          |
| Surface        | `#F5F7FA`  | Alt backgrounds              |
| Ink            | `#111C30`  | Body text                    |
| Muted          | `#6B7280`  | Secondary text               |
| Border         | `#E2E8F0`  | Hairlines, card borders      |

Gradients: hero `linear-gradient(135deg, #0A1428, #14315E 55%, #1C4A86)`; accent
`linear-gradient(135deg, #F39019, #F5A623)`.

## Typography

- **Body:** IBM Plex Sans Arabic (400/500/600/700), self-hosted.
- **Headings/Display:** Tajawal (500/700/800), self-hosted.
- Fluid sizes via `clamp()`; heading scale h1 → 4.75rem display, body ~1rem.

## Spacing scale

`xs .5rem · sm 1rem · md 1.5rem · lg 2.5rem · xl 4rem · 2xl 6rem`

## Radii / elevation / motion

- Radius: `sm 8px · md 14px · lg 22px · pill 999px`
- Shadow (card): `0 12px 30px rgba(20,49,94,.10)`; raised `0 20px 45px rgba(20,49,94,.16)`
- Transition: `.25s cubic-bezier(.4,0,.2,1)`

## Breakpoints (min-width, aligned with Elementor)

`sm 480 · md 768 · lg 1025 · xl 1200 · xxl 1440`; Elementor max: mobile 767, tablet 1024.

See `homepage-blueprint.png` for the visual reference this system reproduces.
