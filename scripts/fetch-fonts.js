const fs = require('fs');
const https = require('https');
const path = require('path');

const FONT_DIR = '/workspace/theme/assets/fonts';
const UA = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120 Safari/537.36';

const families = [
	{ name: 'IBM Plex Sans Arabic', slug: 'ibm-plex-sans-arabic', weights: [400, 500, 600, 700], cssFamily: 'IBM+Plex+Sans+Arabic' },
	{ name: 'Tajawal', slug: 'tajawal', weights: [500, 700, 800], cssFamily: 'Tajawal' },
];

const wanted = new Set(['arabic', 'latin']);

function fetch(url, binary = false) {
	return new Promise((resolve, reject) => {
		https.get(url, { headers: { 'User-Agent': UA } }, (res) => {
			if (res.statusCode >= 300 && res.statusCode < 400 && res.headers.location) {
				return fetch(res.headers.location, binary).then(resolve, reject);
			}
			if (res.statusCode !== 200) { reject(new Error('HTTP ' + res.statusCode + ' for ' + url)); return; }
			const chunks = [];
			res.on('data', (c) => chunks.push(c));
			res.on('end', () => resolve(binary ? Buffer.concat(chunks) : Buffer.concat(chunks).toString('utf8')));
		}).on('error', reject);
	});
}

function parseBlocks(css) {
	const blocks = [];
	const re = /\/\*\s*([\w-]+)\s*\*\/\s*@font-face\s*{([^}]*)}/g;
	let m;
	while ((m = re.exec(css))) {
		const subset = m[1];
		const body = m[2];
		const weight = (body.match(/font-weight:\s*(\d+)/) || [])[1];
		const url = (body.match(/url\(([^)]+)\)/) || [])[1];
		const range = (body.match(/unicode-range:\s*([^;]+);/) || [])[1];
		if (subset && weight && url) blocks.push({ subset, weight: Number(weight), url, range: range && range.trim() });
	}
	return blocks;
}

(async () => {
	fs.mkdirSync(FONT_DIR, { recursive: true });
	let scss = `// =============================================================================\n`;
	scss += `// Self-hosted brand fonts (Arabic + Latin subsets). GENERATED — do not edit by\n`;
	scss += `// hand; regenerate with tools/fetch-fonts. Compiled to ../css/fonts.css and\n`;
	scss += `// preloaded from inc/performance.php. font-display:swap avoids invisible text.\n`;
	scss += `// =============================================================================\n\n`;
	scss += `$font-path: "../fonts";\n\n`;

	const ranges = {};

	for (const fam of families) {
		const url = `https://fonts.googleapis.com/css2?family=${fam.cssFamily}:wght@${fam.weights.join(';')}&display=swap`;
		const css = await fetch(url);
		const blocks = parseBlocks(css).filter((b) => wanted.has(b.subset) && fam.weights.includes(b.weight));
		scss += `// --- ${fam.name} ---\n`;
		for (const w of fam.weights) {
			for (const subset of ['arabic', 'latin']) {
				const b = blocks.find((x) => x.weight === w && x.subset === subset);
				if (!b) continue;
				const suffix = subset === 'arabic' ? '' : `-${subset}`;
				const file = `${fam.slug}-${w}${suffix}.woff2`;
				const buf = await fetch(b.url, true);
				fs.writeFileSync(path.join(FONT_DIR, file), buf);
				ranges[subset] = b.range;
				scss += `@font-face {\n`;
				scss += `\tfont-family: "${fam.name}";\n`;
				scss += `\tfont-style: normal;\n`;
				scss += `\tfont-weight: ${w};\n`;
				scss += `\tfont-display: swap;\n`;
				scss += `\tsrc: url("#{$font-path}/${file}") format("woff2");\n`;
				if (b.range) scss += `\tunicode-range: ${b.range};\n`;
				scss += `}\n`;
			}
		}
		scss += `\n`;
	}

	fs.writeFileSync('/workspace/theme/assets/scss/fonts.scss', scss);
	const files = fs.readdirSync(FONT_DIR).filter((f) => f.endsWith('.woff2'));
	console.log('Downloaded', files.length, 'woff2 files:');
	for (const f of files.sort()) console.log(' -', f, fs.statSync(path.join(FONT_DIR, f)).size, 'bytes');
})().catch((e) => { console.error('ERROR', e.message); process.exit(1); });
