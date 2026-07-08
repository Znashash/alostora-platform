# Alostora — Elementor Import Kit

`alostora-elementor-kit.zip` is a production-ready **Elementor Import/Export Kit** for the Alostora
theme. Import it to load the brand global styles and the header, footer, homepage and course-archive
templates in one step.

## What's inside

| Item                | Type                | Notes                                                        |
| ------------------- | ------------------- | ------------------------------------------------------------ |
| Global Colors       | Site settings       | Primary `#14315E`, Secondary `#2C6FBF`, Text `#111C30`, Accent `#F39019` + Amber / Deep Navy / Surface |
| Global Fonts        | Site settings       | Tajawal (headings) + IBM Plex Sans Arabic (body/text)        |
| Site Settings       | Site settings       | 1200px container, 24px widget gap, RTL-ready breakpoints, button typography |
| Header Template     | Theme Builder       | `doc_type: header`                                            |
| Footer Template     | Theme Builder       | `doc_type: footer`                                            |
| Homepage Template   | Page content        | Imported as a page and set as the site front page            |
| Archive Template    | Theme Builder       | `doc_type: archive` (course catalog, Archive Posts widget)   |

> No popup templates are used by this kit, so none are included.

### Package structure

```
alostora-elementor-kit.zip
├── manifest.json            # kit metadata + document map (format version 2.0)
├── site-settings.json       # global colors, fonts and site settings
├── content/
│   └── page/2001.json       # homepage (set as front page on import)
└── templates/
    ├── 3001.json            # header
    ├── 3002.json            # footer
    └── 3003.json            # archive
```

## Requirements

- **Elementor** (free) — required for site settings + homepage content.
- **Elementor Pro** — required for the Header, Footer and Archive **Theme Builder** templates. Without
  Pro, Elementor safely skips those three templates and still imports the global styles + homepage.
- **Alostora theme** active (the header/footer templates render the theme's branded components; the
  homepage uses the theme's component shortcodes).
- **LifterLMS** — the homepage and archive list courses via LifterLMS.

## How to import

1. In WordPress admin go to **Elementor → Tools → Import / Export Kit** (tab **Import**).
2. Click **Start Import**, choose `alostora-elementor-kit.zip`, and upload.
3. On the selection screen keep **Global Settings**, **Content** and **Templates** checked, then run
   the import.
4. Wait for "Kit imported successfully".

### After importing (one-time, Pro only)

Elementor does not export Theme Builder **display conditions** (they are protected meta), so assign
them once under **Templates → Theme Builder**:

- **Header** → *Display Conditions* → Entire Site.
- **Footer** → *Display Conditions* → Entire Site.
- **Archive** → *Display Conditions* → Courses Archive (or All Archives).

The homepage is set as the front page automatically. Confirm under **Settings → Reading** if needed.

## Rebuilding the kit

The zip is generated from the JSON in `theme/elementor/` (`kits/homepage.json`,
`kits/alostora-kit.json`, `theme-builder/header.json`, `theme-builder/footer.json`). Regenerate with:

```bash
node scripts/build-elementor-kit.js
```

## Verified

This kit was import-tested against Elementor's own importer (`wp elementor kit import`). Global
colors, fonts, site settings and the homepage page imported successfully; the three Theme Builder
templates import additionally when Elementor Pro is active.
