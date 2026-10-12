'use strict';

/**
 * Splits a comma-separated argument list at the top level only, so nested
 * function calls or quoted strings do not split a value early.
 */
function splitTopLevel(args) {
    const parts = [];
    let depth = 0;
    let quote = null;
    let current = '';

    for (const ch of args) {
        if (quote) {
            current += ch;
            if (ch === quote) quote = null;
            continue;
        }

        if (ch === '"' || ch === "'") {
            quote = ch;
            current += ch;
            continue;
        }

        if (ch === '(') depth++;
        if (ch === ')') depth--;

        if (ch === ',' && depth === 0) {
            parts.push(current);
            current = '';
            continue;
        }

        current += ch;
    }

    parts.push(current);

    return parts;
}

/**
 * Rewrites every `light-dark(a, b)` inside a single declaration value to `a`.
 * The app is light-only, so the light value is always the correct one.
 */
function replaceLightDark(value) {
    const marker = 'light-dark(';
    let result = '';
    let index = 0;

    while (true) {
        const start = value.indexOf(marker, index);

        if (start === -1) {
            result += value.slice(index);
            break;
        }

        result += value.slice(index, start);

        let depth = 1;
        let quote = null;
        let cursor = start + marker.length;

        for (; cursor < value.length; cursor++) {
            const ch = value[cursor];

            if (quote) {
                if (ch === quote) quote = null;
                continue;
            }

            if (ch === '"' || ch === "'") {
                quote = ch;
                continue;
            }

            if (ch === '(') depth++;
            else if (ch === ')') {
                depth--;
                if (depth === 0) break;
            }
        }

        const args = value.slice(start + marker.length, cursor);

        result += splitTopLevel(args)[0].trim();

        index = cursor + 1;
    }

    return result;
}

/**
 * Pure `string -> string` transform used by the tests and the build.
 *
 * Parsing through PostCSS guarantees only declarations are visited, so a
 * `light-dark()` inside an at-rule prelude (e.g.
 * `@supports (color: light-dark(red, red))`) is never rewritten.
 */
function resolveLightDark(css) {
    const postcss = require('postcss');
    const root = postcss.parse(css);

    root.walkDecls((decl) => {
        decl.value = replaceLightDark(decl.value);
    });

    return root.toString();
}

/**
 * PostCSS 8 plugin object for the Vite build. Registering only the
 * `Declaration` visitor keeps the transform off selectors and at-rule preludes.
 */
resolveLightDark.plugin = {
    postcssPlugin: 'buku-masjid-resolve-light-dark',
    Declaration(decl) {
        decl.value = replaceLightDark(decl.value);
    },
};

module.exports = resolveLightDark;
