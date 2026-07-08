# LifterLMS & VdoCipher Integration

## LifterLMS

The theme declares LifterLMS support and overrides **presentation only** — enrolment, access
control and quizzes remain owned by LifterLMS.

### Support & wrappers (`inc/lifterlms.php`)

- `add_theme_support( 'lifterlms' )`, `'lifterlms-sidebars'`, `'lifterlms-quizzes'`.
- Replaces the default LifterLMS content wrappers with theme-owned wrappers so LMS pages inherit the
  Alostora container/layout.
- Sets the template override path to `lifterlms/` and tunes catalog columns to 3.

### Template overrides (`theme/lifterlms/`)

LifterLMS automatically loads overrides from this directory (mirroring the plugin's `templates/`
tree). Shipped overrides re-class the stable leaf partials to the brand card design:

| File                               | Overrides                                   |
| ---------------------------------- | ------------------------------------------- |
| `loop/featured-image.php`          | Catalog card media (lazy, sized thumbnail)  |
| `loop/title.php`                   | Catalog card title                          |
| `loop/author.php`                  | Catalog card instructor row                 |
| `loop/pagination.php`              | Catalog pagination                          |
| `course/meta-wrapper-start.php`    | Single-course meta wrapper (open)           |
| `course/meta-wrapper-end.php`      | Single-course meta wrapper (close)          |
| `course/author.php`                | Single-course instructor block              |

Anything not overridden inherits brand styling from `assets/scss/components/_lifterlms.scss`
(buttons, progress bars, syllabus, notices, myaccount, checkout).

## VdoCipher (secure video)

VdoCipher owns DRM and OTP generation via its own plugin. The theme **never rebuilds the player**;
it only guarantees a responsive, brand-styled container.

### Shortcode passthrough

```
[alostora_vdocipher id="VIDEO_ID" ratio="16x9" title="Lesson intro"]
```

- Delegates to the official `[vdo_video_embed]` / `[vdocipher]` shortcode when the plugin is active.
- Renders an accessible placeholder in the editor/builder when it is not, so the slot is visible.
- Wraps output in `.alostora-video` with a fixed aspect ratio so embeds stay responsive.

### Using VdoCipher inside LifterLMS lessons

Put the shortcode (or the VdoCipher URL) in the lesson's **video embed** field or lesson content.
LifterLMS renders it inside `.llms-video-wrapper`, which the theme styles into a rounded, responsive
16:9 frame. The `alostora_has_vdocipher()` helper is available for conditional logic.

The hero and video-tile "play" triggers open an accessible lightbox (`assets/js/modules/
video-lightbox.js`) that hosts the same secure embed — again delegating DRM to VdoCipher.
