<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'projekti nimi');
set('remote_user', ''); //virt...
set('http_user', '');
set('keep_releases', 2);

// Hosts
host('nimi.itmajakas.ee')
    ->setHostname('nimi.itmajakas.ee')
    ->set('http_user', '')
    ->set('deploy_path', '~/domeenid/www.nimi.itmajakas.ee/kataloog')
    ->set('branch', 'dev');

// Tasks
set('repository', 'repo nimi');
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
