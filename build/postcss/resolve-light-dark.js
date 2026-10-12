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
 * This produces the LEGACY fallback path: browsers without native
 * `light-dark()` support get the light argument, while the guarded modern copy
 * keeps the real `light-dark()` behind `@supports`.
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
 * True when a rule directly declares a value containing `light-dark()`.
 */
function hasLightDarkDeclaration(rule) {
    return (rule.nodes || []).some(
        (node) => node.type === 'decl' && String(node.value).includes('light-dark('),
    );
}

/**
 * True when a rule already sits inside a `@supports (color: light-dark(...))`
 * guard, so the restructuring is idempotent and never re-wraps its own output.
 */
function isInsideLightDarkGuard(rule) {
    let parent = rule.parent;

    while (parent) {
        if (
            parent.type === 'atrule'
            && parent.name === 'supports'
            && String(parent.params).includes('light-dark')
        ) {
            return true;
        }

        parent = parent.parent;
    }

    return false;
}

/**
 * Rewrite `root` in place: every rule holding a `light-dark()` declaration
 * keeps a plain light-value copy (the legacy path) and gains a sibling
 * `@supports (color: light-dark(red, red))` block holding the original
 * declaration (the modern path).
 *
 * Only a rule's DIRECT declaration children are flattened. Nested rules are
 * left untouched here and, if they carry their own `light-dark()`, are guarded
 * separately when `walkRules` reaches them (their guard lands as a sibling
 * inside the parent rule). Flattening them here too would double-guard the
 * child and destroy its modern path inside the parent's legacy block — the
 * plugin runs before `postcss-preset-env`, so native nesting must survive.
 */
function guardRoot(root, postcss) {
    const targets = [];

    root.walkRules((rule) => {
        if (isInsideLightDarkGuard(rule)) return;
        if (hasLightDarkDeclaration(rule)) targets.push(rule);
    });

    targets.forEach((rule) => {
        const modernRule = rule.clone();

        rule.each((node) => {
            if (node.type === 'decl') {
                node.value = replaceLightDark(node.value);
            }
        });

        const guard = postcss.atRule({
            name: 'supports',
            params: '(color: light-dark(red, red))',
        });
        guard.append(modernRule);
        rule.parent.insertAfter(rule, guard);
    });
}

/**
 * Pure `string -> string` restructuring used by the tests. Keeps the pure
 * splitter testable without a PostCSS plugin harness.
 */
function guardLightDark(css) {
    const postcss = require('postcss');
    const root = postcss.parse(css);
    guardRoot(root, postcss);

    return root.toString();
}

/**
 * Pure flatten helper kept for the `resolveLightDark` unit tests: rewrites
 * every `light-dark(a, b)` to its first (light) argument.
 *
 * This is NO LONGER the build transform — the Vite build uses the
 * non-destructive guard via `resolveLightDark.plugin` (see `guardRoot`).
 * Parsing through PostCSS ensures only declarations are visited, so a
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

resolveLightDark.guard = guardLightDark;

/**
 * PostCSS 8 plugin object for the Vite build. `Once` runs the guard
 * restructuring across the whole stylesheet.
 */
resolveLightDark.plugin = {
    postcssPlugin: 'buku-masjid-guard-light-dark',
    Once(root, helpers) {
        guardRoot(root, helpers.postcss);
    },
};

module.exports = resolveLightDark;
