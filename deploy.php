<?php

namespace Deployer;

// Include the Laravel & rsync recipes
require 'recipe/laravel.php';
require 'recipe/rsync.php';

set('application', 'dep-demo');
set('ssh_multiplexing', true); // Speed up deployment

set('rsync_src', function () {
    return __DIR__; // If your project isn't in the root, you'll need to change this.
});

// Configuring the rsync exclusions.
// You'll want to exclude anything that you don't want on the production server.
add('rsync', [
    'exclude' => [
        '.git',
        '/.env',
        '/storage/',
        '/vendor/',
        '/node_modules/',
        '.github',
        'deploy.php',
    ],
]);

// Set up a deployer task to copy secrets from directory env to /var/www/nama-laravel-project in server.
task('deploy:secrets', function () {
    run('cp $HOME/env/dompet/production/.env {{deploy_path}}/shared');
});

// Hosts
host('dompetku') // Name of the server
    ->hostname('178.128.104.220') // Hostname or IP address
    ->stage('production') // Deployment stage (production, staging, etc)
    ->user('deployer') // SSH user
    ->set('deploy_path', '/var/www/dompet') // Deploy path
    ->set('http_user', 'www-data');

after('deploy:failed', 'deploy:unlock'); // Unlock after failed deploy

desc('Deploy the application');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'rsync', // Deploy code & built assets
    'deploy:secrets', // Deploy secrets
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
    'artisan:storage:link', // |
    'artisan:view:cache',   // |
    'artisan:config:cache', // | Laravel specific steps
    'artisan:optimize',     // |
    'artisan:migrate',      // | Run artisan migrate if you need it, if not then just comment it!
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
]);
