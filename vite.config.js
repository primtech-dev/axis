import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";
import fs from "fs";

/**
 * Rekursif baca folder dan kembalikan file dengan ekstensi tertentu.
 * Mengembalikan path relatif posix (contoh: resources/js/pages/foo.js)
 */
function walkDirCollect(dir, exts = []) {
    const base = path.resolve(process.cwd(), dir);
    const out = [];

    if (!fs.existsSync(base)) return out;

    const entries = fs.readdirSync(base);
    for (const name of entries) {
        const abs = path.join(base, name);
        const stat = fs.statSync(abs);
        if (stat.isDirectory()) {
            out.push(...walkDirCollect(path.join(dir, name), exts));
        } else {
            const ext = path.extname(name).toLowerCase();
            if (exts.length === 0 || exts.includes(ext)) {
                // make posix relative path
                const rel = path.relative(process.cwd(), abs).replace(/\\/g, "/");
                out.push(rel);
            }
        }
    }
    return out;
}

const pagesJs = walkDirCollect("resources/js/pages", [".js"]);
const mapsJs  = walkDirCollect("resources/js/maps", [".js"]);

// HATI-HATI: jangan masukkan semua partial scss sebagai entry.
// Hanya daftar file SCSS entry utama saja (yang @use / @import partial secara benar).
const scssEntries = [
    "resources/scss/app.scss",
];

const baseEntries = [
    "resources/js/app.js",
    "resources/js/config.js",
];

// vendor CSS / explicit files you still want included
const vendorCss = [
    "node_modules/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css",
    "node_modules/datatables.net-select-bs5/css/select.bootstrap5.min.css",
    "node_modules/datatables.net-bs5/css/dataTables.bootstrap5.min.css",
    "node_modules/dropzone/dist/dropzone.css",
    "node_modules/quill/dist/quill.bubble.css",
    "node_modules/quill/dist/quill.core.css",
    "node_modules/quill/dist/quill.snow.css",
    "node_modules/leaflet/dist/leaflet.css",
    "node_modules/jsvectormap/dist/jsvectormap.min.css",
];

// merge & dedupe
const inputList = Array.from(new Set([
    ...baseEntries,
    ...pagesJs,
    ...mapsJs,
    ...scssEntries,
    ...vendorCss,
]));

export default defineConfig({
    plugins: [
        laravel({
            input: inputList,
            refresh: true,
        }),
    ],
});
