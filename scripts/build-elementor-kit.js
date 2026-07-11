/**
 * Build a production-ready Elementor Import/Export Kit (.zip) for Alostora.
 *
 * Output structure matches Elementor's own kit format (verified against the
 * Elementor core import/export runners):
 *
 *   manifest.json
 *   site-settings.json           { settings: { global colors/fonts/site settings } }
 *   content/page/<id>.json       { content, settings, metadata }   (homepage)
 *   templates/<id>.json          { content, settings, metadata }   (header/footer/archive)
 *
 * Files are read by Elementor with a ".json" suffix; the manifest maps every
 * document by id -> { title, doc_type, thumbnail }.
 *
 * This script only READS the existing theme Elementor JSON and WRITES the kit
 * into theme/elementor/export/. It does not modify any existing theme file.
 *
 * Usage: node scripts/build-elementor-kit.js
 */

const fs = require('fs');
const path = require('path');
const { execSync } = require('child_process');

const ROOT = path.resolve(__dirname, '..');
const ELEMENTOR_DIR = path.join(ROOT, 'theme/elementor');
const EXPORT_DIR = path.join(ELEMENTOR_DIR, 'export');
const BUILD_DIR = path.join(EXPORT_DIR, '.build');
const ZIP_NAME = 'alostora-elementor-kit.zip';

const KIT_FORMAT_VERSION = '2.0';       // Elementor Module::FORMAT_VERSION.
const ELEMENTOR_VERSION = '3.25.0';     // Informational compatibility hint.

const readJson = (p) => JSON.parse(fs.readFileSync(p, 'utf8'));
const writeJson = (p, data) => {
	fs.mkdirSync(path.dirname(p), { recursive: true });
	fs.writeFileSync(p, JSON.stringify(data, null, '\t'));
};

let rid = 0xa10000;
const eid = () => (rid++).toString(16).padStart(7, '0');

// Transform an existing single-template export ({content, page_settings}) into
// the kit per-document shape ({content, settings, metadata}).
const toDocument = (src) => ({
	content: Array.isArray(src.content) ? src.content : [],
	settings: src.page_settings && typeof src.page_settings === 'object' ? src.page_settings : {},
	metadata: {},
});

// ---- Load existing sources (read-only) ----
const homepageSrc = readJson(path.join(ELEMENTOR_DIR, 'kits/homepage.json'));
const headerSrc = readJson(path.join(ELEMENTOR_DIR, 'theme-builder/header.json'));
const footerSrc = readJson(path.join(ELEMENTOR_DIR, 'theme-builder/footer.json'));
const kitSrc = readJson(path.join(ELEMENTOR_DIR, 'kits/alostora-kit.json'));

// ---- Build an Archive theme-builder template (course catalog) ----
const archiveDoc = {
	content: [
		{
			id: eid(),
			elType: 'section',
			settings: { layout: 'boxed', _css_classes: 'alostora-section' },
			elements: [
				{
					id: eid(),
					elType: 'column',
					settings: { _column_size: 100 },
					elements: [
						{
							id: eid(),
							elType: 'widget',
							widgetType: 'archive-title',
							settings: { _css_classes: 'alostora-section__title', align: 'center' },
							elements: [],
						},
						{
							id: eid(),
							elType: 'widget',
							widgetType: 'archive-posts',
							settings: { columns: '3', _css_classes: 'alostora-archive' },
							elements: [],
						},
					],
					isInner: false,
				},
			],
			isInner: false,
		},
	],
	settings: {},
	metadata: {},
};

// ---- Assign document IDs ----
const docs = {
	content: {
		page: {
			2001: { title: 'Alostora Homepage', doc_type: 'wp-page', data: toDocument(homepageSrc), show_on_front: true },
		},
	},
	templates: {
		3001: { title: 'Alostora Header', doc_type: 'header', data: toDocument(headerSrc) },
		3002: { title: 'Alostora Footer', doc_type: 'footer', data: toDocument(footerSrc) },
		3003: { title: 'Alostora Archive', doc_type: 'archive', data: archiveDoc },
	},
};

// ---- Clean + prepare build dir ----
fs.rmSync(BUILD_DIR, { recursive: true, force: true });
fs.mkdirSync(BUILD_DIR, { recursive: true });

// ---- Write content documents + manifest content map ----
const manifestContent = {};
for (const [postType, items] of Object.entries(docs.content)) {
	manifestContent[postType] = {};
	for (const [id, doc] of Object.entries(items)) {
		writeJson(path.join(BUILD_DIR, 'content', postType, `${id}.json`), doc.data);
		const entry = { title: doc.title, doc_type: doc.doc_type, thumbnail: false, url: '', terms: [] };
		if (doc.show_on_front) entry.show_on_front = true;
		manifestContent[postType][id] = entry;
	}
}

// ---- Write template documents + manifest templates map ----
const manifestTemplates = {};
for (const [id, doc] of Object.entries(docs.templates)) {
	writeJson(path.join(BUILD_DIR, 'templates', `${id}.json`), doc.data);
	manifestTemplates[id] = { title: doc.title, doc_type: doc.doc_type, thumbnail: false };
}

// ---- Write site-settings.json (global colors, fonts, site settings) ----
writeJson(path.join(BUILD_DIR, 'site-settings.json'), { settings: kitSrc.settings });

// ---- Write manifest.json ----
const manifest = {
	name: 'alostora',
	title: 'Alostora',
	description: 'Alostora premium RTL e-learning kit: global colors & fonts, site settings, header, footer, homepage and course archive templates.',
	author: 'Alostora Platform',
	version: KIT_FORMAT_VERSION,
	elementor_version: ELEMENTOR_VERSION,
	created: new Date().toISOString().replace('T', ' ').slice(0, 19),
	thumbnail: false,
	site: 'https://alostorajo.com',
	'site-settings': [
		'global-colors',
		'global-typography',
		'theme-style-typography',
		'theme-style-buttons',
		'settings-layout',
		'settings-background',
	],
	templates: manifestTemplates,
	content: manifestContent,
};
writeJson(path.join(BUILD_DIR, 'manifest.json'), manifest);

// ---- Package as zip (files at archive root) ----
const zipPath = path.join(EXPORT_DIR, ZIP_NAME);
fs.rmSync(zipPath, { force: true });
execSync(`cd "${BUILD_DIR}" && zip -r -X "${zipPath}" manifest.json site-settings.json content templates`, { stdio: 'pipe' });

// ---- Clean build dir ----
fs.rmSync(BUILD_DIR, { recursive: true, force: true });

console.log('Built kit:', path.relative(ROOT, zipPath));
console.log(' templates:', Object.keys(manifestTemplates).length, '| content pages:', Object.keys(manifestContent.page || {}).length);
