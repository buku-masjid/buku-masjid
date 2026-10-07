const { spawnSync } = require('node:child_process');
const { readdirSync, statSync } = require('node:fs');
const { join } = require('node:path');

function collectTestFiles(directory) {
    return readdirSync(directory).flatMap((entry) => {
        const fullPath = join(directory, entry);

        if (statSync(fullPath).isDirectory()) {
            return collectTestFiles(fullPath);
        }

        return entry.endsWith('.test.js') ? [fullPath] : [];
    });
}

const testFiles = collectTestFiles(__dirname);

if (testFiles.length === 0) {
    console.error('No JavaScript test files found under tests/javascript.');
    process.exit(1);
}

const result = spawnSync(process.execPath, ['--test', ...testFiles], { stdio: 'inherit' });

process.exit(result.status ?? 1);
