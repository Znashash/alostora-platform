// Generates Elementor template-library export JSON for the Alostora theme.
// Output is importable via Elementor > Templates > Import Templates.
const fs = require('fs');
const path = require('path');

const OUT_TB = '/workspace/theme/elementor/theme-builder';
const OUT_KIT = '/workspace/theme/elementor/kits';

let idc = 0x1000;
const id = () => (idc++).toString(16).padStart(7, '0');

const widget = (type, settings = {}, extra = {}) => ({
	id: id(), elType: 'widget', widgetType: type, settings, elements: [], ...extra,
});
const col = (children, settings = {}) => ({
	id: id(), elType: 'column', settings: { _column_size: 100, ...settings }, elements: children, isInner: false,
});
const section = (columns, settings = {}) => ({
	id: id(), elType: 'section', settings, elements: columns, isInner: false,
});

const heading = (text, tag = 'h2', settings = {}) =>
	widget('heading', { title: text, header_size: tag, ...settings });
const textEditor = (html, settings = {}) => widget('text-editor', { editor: html, ...settings });
const button = (text, link, cls = '', settings = {}) =>
	widget('button', { text, link: { url: link, is_external: '', nofollow: '' }, css_classes: cls, ...settings });
const shortcode = (sc) => widget('shortcode', { shortcode: sc });
const image = (url, settings = {}) => widget('image', { image: { url }, ...settings });
const spacer = (size = 40) => widget('spacer', { space: { unit: 'px', size } });

function template(title, type, content, pageSettings = {}) {
	return {
		version: '0.4',
		title,
		type,
		content,
		page_settings: pageSettings,
		metadata: { template_type: type, wp_page_template: 'elementor_canvas' },
	};
}

// ---------- Header ----------
const header = template('Alostora Header', 'header', [
	section([
		col([ shortcode('[alostora_component slug="header"]') ], { _column_size: 100 }),
	], { layout: 'full_width', _css_classes: 'alostora-header-tb' }),
]);
// Header is primarily the theme header component; Elementor location is used so
// site editors can still customise around it.

// ---------- Footer ----------
const footer = template('Alostora Footer', 'footer', [
	section([
		col([
			shortcode('[alostora_button label="ابدأ الآن مجاناً" url="#" style="primary"]'),
		]),
	], { layout: 'full_width', _css_classes: 'alostora-footer-tb' }),
]);

// ---------- Homepage ----------
// Each homepage section is a single full-width, zero-padding Elementor section
// wrapping one theme shortcode widget. The theme owns the markup/styling and the
// content stays editable via the shortcode attributes. Imagery falls back to
// bundled placeholders so no widget is ever empty.
const fullSection = (sc) =>
	section(
		[ col([ shortcode(sc) ], { padding: { unit: 'px', top: '0', right: '0', bottom: '0', left: '0', isLinked: true } }) ],
		{ layout: 'full_width', gap: 'no', padding: { unit: 'px', top: '0', right: '0', bottom: '0', left: '0', isLinked: true } }
	);

const homepage = template('Alostora Homepage', 'page', [
	fullSection('[alostora_hero eyebrow="منصة الأسطورة التعليمية" title="التاريخ لم يعد يُقرأ..." title_accent="بل يُشاهد!" primary_label="ابدأ التعلم الآن" primary_url="/courses/" video_url="#" video_label="شاهد تجربة من أحد الدروس"]حوّلنا الكتاب المدرسي إلى تجربة تعليمية سينمائية تساعد الطالب على الفهم والذكر والتفوق.[/alostora_hero]'),
	fullSection('[alostora_statistics items="24500+|طالب وطالبة|users,1200+|درس متحرك|play,180+|دورة تعليمية|book,98%|نسبة رضا الطلاب|trophy"]'),
	fullSection('[alostora_courses_carousel]'),
	fullSection('[alostora_steps]'),
	fullSection('[alostora_features]'),
	fullSection('[alostora_video_showcase]'),
	fullSection('[alostora_cta_banner url="/courses/"]'),
], { template: 'elementor_header_footer' });

fs.mkdirSync(OUT_TB, { recursive: true });
fs.mkdirSync(OUT_KIT, { recursive: true });
fs.writeFileSync(path.join(OUT_TB, 'header.json'), JSON.stringify(header, null, '\t'));
fs.writeFileSync(path.join(OUT_TB, 'footer.json'), JSON.stringify(footer, null, '\t'));
fs.writeFileSync(path.join(OUT_KIT, 'homepage.json'), JSON.stringify(homepage, null, '\t'));

// ---------- Global styles kit ----------
const kit = {
	title: 'Alostora Global Styles',
	type: 'kit',
	version: '0.4',
	settings: {
		system_colors: [
			{ _id: 'primary', title: 'Primary', color: '#14315E' },
			{ _id: 'secondary', title: 'Secondary', color: '#2C6FBF' },
			{ _id: 'text', title: 'Text', color: '#111C30' },
			{ _id: 'accent', title: 'Accent', color: '#F39019' },
		],
		custom_colors: [
			{ _id: 'accentlight', title: 'Amber', color: '#F5A623' },
			{ _id: 'navydark', title: 'Deep Navy', color: '#0A1428' },
			{ _id: 'surface', title: 'Surface', color: '#F5F7FA' },
		],
		system_typography: [
			{ _id: 'primary', title: 'Primary', typography_typography: 'custom', typography_font_family: 'Tajawal', typography_font_weight: '800' },
			{ _id: 'secondary', title: 'Secondary', typography_typography: 'custom', typography_font_family: 'Tajawal', typography_font_weight: '700' },
			{ _id: 'text', title: 'Text', typography_typography: 'custom', typography_font_family: 'IBM Plex Sans Arabic', typography_font_weight: '400' },
			{ _id: 'accent', title: 'Accent', typography_typography: 'custom', typography_font_family: 'IBM Plex Sans Arabic', typography_font_weight: '600' },
		],
		default_generic_fonts: 'sans-serif',
		container_width: { unit: 'px', size: 1200 },
		space_between_widgets: { unit: 'px', size: 24 },
		viewport_md: 768,
		viewport_lg: 1025,
		button_typography_typography: 'custom',
		button_typography_font_family: 'Tajawal',
		button_typography_font_weight: '700',
		body_background_background: 'classic',
		body_background_color: '#FFFFFF',
	},
};
fs.writeFileSync(path.join(OUT_KIT, 'alostora-kit.json'), JSON.stringify(kit, null, '\t'));

console.log('Generated Elementor exports:');
for (const f of ['theme-builder/header.json', 'theme-builder/footer.json', 'kits/homepage.json', 'kits/alostora-kit.json']) {
	const p = path.join('/workspace/theme/elementor', f);
	console.log(' -', f, fs.statSync(p).size, 'bytes');
}
