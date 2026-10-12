const test = require('node:test');
const assert = require('node:assert/strict');

const resolveLightDark = require('../../../build/postcss/resolve-light-dark');

test('resolves a light-dark() token definition to its light value', () => {
    assert.equal(
        resolveLightDark('--heading-color:light-dark(var(--gray-900),var(--white))'),
        '--heading-color:var(--gray-900)',
    );
});

test('resolves light-dark() with nested commas in the light value', () => {
    assert.equal(
        resolveLightDark('color:light-dark(oklch(43.86% 0 0deg),rgb(1,2,3))'),
        'color:oklch(43.86% 0 0deg)',
    );
});

test('resolves every light-dark() inside a rule block', () => {
    assert.equal(
        resolveLightDark('a{color:light-dark(#111,#eee);background:light-dark(#fff,#000)}'),
        'a{color:#111;background:#fff}',
    );
});

test('leaves light-dark() in an at-rule prelude untouched but resolves declarations', () => {
    assert.equal(
        resolveLightDark('@supports (color:light-dark(red,red)){:root{--x:light-dark(var(--a),var(--b))}}'),
        '@supports (color:light-dark(red,red)){:root{--x:var(--a)}}',
    );
});

test('is a no-op when there is no light-dark()', () => {
    assert.equal(resolveLightDark('a{color:red}'), 'a{color:red}');
});

test('tolerates arbitrary whitespace inside light-dark()', () => {
    assert.equal(
        resolveLightDark('color:light-dark( var(--a) , var(--b) )'),
        'color:var(--a)',
    );
});
