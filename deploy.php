<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'hajus2023');
set('remote_user', 'virt106862'); //virt...
set('http_user', 'virt106862');
set('keep_releases', 2);

// Hosts
host('ta21ounaid.itmajakas.ee')
    ->setHostname('ta21ounaid.itmajakas.ee')
    ->set('http_user', 'virt106862')
    ->set('deploy_path', '~/domeenid/www.ta21ounaid.itmajakas.ee/hajus')
    ->set('branch', 'main');

// Tasks
set('repository', 'https://github.com/oliverounaid/hajus2023.git');
//Restart opcache
task('opcache:clear', function () {
    run('killall php81-cgi || true');
})->desc('Clear opcache');

task('build:node', function () {
    cd('{{release_path}}');
    run('npm i');
    run('npx vite build');
    run('rm -rf node_modules');
});
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:view:cache',
    'artisan:config:cache',
    'build:node',
    'deploy:publish',
    'opcache:clear',
    'artisan:cache:clear'
]);
after('deploy:failed', 'deploy:unlock');
