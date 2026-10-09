# VOID — WordPress Portfolio Theme
### Installation & Customization Guide

---

## Quick Install

1. Upload `void-theme.zip` via **WP Admin → Appearance → Themes → Add New → Upload Theme**
2. Activate the theme
3. Go to **Settings → Reading** → set *"Your homepage displays"* to **A static page**, select any page as "Homepage"
4. Visit **Appearance → Customize** to fill in your content

---

## File Structure

```
void-theme/
├── style.css              ← WordPress theme header (metadata only)
├── functions.php          ← Theme setup, CPT, enqueues, customizer, helpers
├── header.php             ← Loader, topbar, toggles
├── footer.php             ← Bottom nav, mobile drawer, wp_footer()
├── front-page.php         ← One-page homepage (hero, portfolio, about, contact)
├── single.php             ← Portfolio project + blog post template
├── index.php              ← Archive / blog fallback
├── 404.php                ← Not found page
│
└── assets/
    ├── css/
    │   ├── variables.css  ← ALL design tokens (colors, fonts, spacing, motion)
    │   ├── base.css       ← Reset, typography classes, layout utilities
    │   ├── loader.css     ← Preloader component
    │   ├── cursor.css     ← Custom cursor
    │   ├── nav.css        ← Topbar + bottom nav + mobile drawer
    │   ├── hero.css       ← Hero section + video + grain canvas
    │   ├── portfolio.css  ← Grid view, list view, project cards
    │   ├── animations.css ← Scroll reveals, parallax, about, contact sections
    │   └── responsive.css ← Breakpoint overrides
    │
    └── js/
        ├── grain.js       ← Film grain canvas animation
        ├── cursor.js      ← Custom cursor with lerp ring
        ├── theme.js       ← Dark/light toggle + localStorage persist
        ├── view.js        ← Grid/list toggle + localStorage persist
        ├── scroll.js      ← Loader, scroll reveals, parallax, nav tracking
        └── main.js        ← Entry point, initialises all modules
```

---

## Customization Map

All replaceable spots are marked with `REPLACE:` comments in the code.

### LOGO
**File:** `header.php` → search `REPLACE: logo`

The header contains an SVG circle placeholder:
```html
<svg class="topbar__logo-mark" viewBox="0 0 32 32" ...>
    <!-- REPLACE: logo SVG -->
    <circle .../>
</svg>
```

**To replace:**
- Option A: Go to **Appearance → Customize → Site Identity → Logo** and upload an image logo
- Option B: Open `header.php` and swap the `<svg>` with your own SVG markup or `<img>` tag

The hero logo is a separate element in `front-page.php` → search `REPLACE: logo`:
```html
<div class="hero__logo-wrap">
    <!-- REPLACE: logo — swap with your animated SVG -->
    <div class="hero__logo-placeholder">← Replace →</div>
</div>
```
Replace the placeholder div with your SVG animation.

---

### HERO VIDEO
**File:** `front-page.php` → search `REPLACE: hero video`
**Via Customizer:** Appearance → Customize → Hero Section → Hero Video URL

1. Upload your `.mp4` to **Media Library**
2. Copy the file URL
3. Paste into **Customizer → Hero Section → Hero Video URL**

Recommended specs: `1920×1080`, H.264, under 15MB, no audio.

---

### FONTS
**File:** `assets/css/variables.css` → search `REPLACE: fonts`
**Also:** `functions.php` → search `REPLACE: fonts`

**Step 1** — Change the Google Fonts import in `functions.php`:
```php
// Replace this line:
wp_enqueue_style( 'void-fonts',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap', ...
);

// With e.g. Neue Haas Grotesk or any font you want
wp_enqueue_style( 'void-fonts',
    'https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500&display=swap', ...
);
```

**Step 2** — Update `variables.css`:
```css
/* REPLACE: fonts */
--font-sans: "DM Sans", sans-serif;
```

For **Alte Haas Grotesk** (self-hosted):
```css
@font-face {
    font-family: "Alte Haas Grotesk";
    src: url("../fonts/AlteHaasGroteskBold.woff2") format("woff2");
    font-weight: 700;
    font-display: swap;
}
```
Then: `--font-sans: "Alte Haas Grotesk", sans-serif;`

---

### COLORS / ACCENT
**File:** `assets/css/variables.css`

```css
[data-theme="dark"] {
    --accent: #FFD600;     /* ← Yellow on black. Change this. */
}

[data-theme="light"] {
    --accent: #0057FF;     /* ← Blue on white. Change this. */
}
```

For pure black & white with NO accent:
```css
--accent: var(--fg);   /* Both modes: accent = text color */
```

---

### NAV LINKS
**File:** `footer.php` → edit the `<ul class="nav__items">` list

Default items: Home / Work / About / Contact.
Each `href` must match the `id` of a section in `front-page.php`.

---

## Portfolio System

### Adding Projects

1. **WP Admin → Portfolio → Add New**
2. Fill in:
   - **Title** — project name
   - **Content** — description (shown on single project page)
   - **Featured Image** — main thumbnail
   - **Project Type** (taxonomy) — e.g. `web`, `video`, `photo`, `identity`
   - **Custom Fields** (in the sidebar or below the editor):
     - `void_project_url` — live project URL
     - `void_project_year` — e.g. `2024`
     - `void_project_client` — client name
     - `void_project_role` — e.g. `Design, Development`
     - `void_project_tools` — e.g. `Figma, GSAP, WordPress`
3. Set **Order** (Page Attributes → Order) to control display sequence
4. Publish

> **Tip:** Enable Gutenberg custom fields by opening the three-dot menu (⋮) in the top right → Preferences → Panels → Custom Fields.

### Project Type Slugs

Use these slugs when creating Project Type terms (they drive the nav filter labels):
- `web` → Web Design
- `video` → Video
- `photo` → Photography  
- `identity` → Identity / Branding
- Add any others you need

### Grid Layout

The grid uses a 12-column CSS Grid with asymmetric sizing:
- Item 1: 7 cols (landscape hero)
- Item 2: 5 cols (portrait)
- Items 3–5: 4 cols each (square)
- Item 6: 12 cols (full-width cinema)
- Pattern repeats every 6

---

## Toggles

| Toggle | Location | Persists |
|--------|----------|----------|
| Dark / Light mode | Top bar, right | ✓ localStorage |
| Grid / List view | Top bar, right | ✓ localStorage |

Both respect `prefers-color-scheme` and `prefers-reduced-motion`.

---

## Scroll Animations

Apply to any element with the `data-reveal` attribute:

```html
<div data-reveal="up">Fades up from below</div>
<div data-reveal="left">Slides in from right</div>
<div data-reveal="right">Slides in from left</div>
<div data-reveal="scale">Scales in</div>
<div data-reveal="none">Fades only, no translate</div>

<!-- Stagger delays (0.1s increments) -->
<div data-reveal="up" data-delay="3">Delayed 0.3s</div>
```

---

## Film Grain

Intensity is controlled by `--grain-opacity` in `variables.css`:
```css
[data-theme="dark"]  { --grain-opacity: 0.055; }
[data-theme="light"] { --grain-opacity: 0.03; }
```

To disable grain entirely, remove `VoidGrain.init()` from `assets/js/main.js`.

---

## Recommended Image Sizes

| Use | Size | Ratio |
|-----|------|-------|
| Portfolio hero (large items) | 1600 × 900 | 16:9 |
| Portfolio portrait | 800 × 1067 | 3:4 |
| Portfolio square | 800 × 800 | 1:1 |
| Project detail sticky | 800 × 800 | 1:1 |

---

## Contact Form

The theme doesn't bundle a mailer. Recommended options:

1. **Contact Form 7** (free) — replace the contact section content in `front-page.php` with `[contact-form-7 id="X"]`
2. **WPForms Lite** (free) — same approach, paste shortcode
3. **Netlify Forms / Formspree** — if hosting statically, replace form action

---

## Performance Tips

- Compress your hero video with HandBrake: H.264, CRF 28, no audio
- Use `.webp` for portfolio images; WordPress auto-generates them
- Install **Smush** or **ShortPixel** for automatic image compression
- Enable object caching (Redis/Memcached) on your host

---

## Browser Support

Chrome 90+, Firefox 88+, Safari 14+, Edge 90+.
Custom cursor is hidden automatically on touch devices (`hover: none` media query).

---
