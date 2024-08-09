<?php
namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'invoicing');

// Project repository
set('repository', 'git@github.com:invoyer/invoicing.git');

set('keep_releases', 1);

// Shared files/dirs between deploys
add('shared_files', [
    '.env'
]);

add('shared_dirs', [
    'public/.well-known'
]);

// Writable dirs by web server
add('writable_dirs', []);
// Hosts+

set('composer_action', '');
set('composer_options', '');

host('live')
    ->set('hostname', '185.140.231.117')
    ->set('port', 2203)
    ->set('stage', 'live')
    ->set('remote_user', 'invoyer')
    ->set('branch', 'main')
    ->set('deploy_path', '/home/invoyer/invoicing');

task('deploy:build', function () {
    cd('{{release_path}}');
    run('php7.4 /usr/local/bin/composer install');
    run('npm install');

    run('yes | php7.4 artisan migrate');
    run('yes | php7.4 artisan optimize');
    run('yes | php7.4 artisan storage:link');
    run('npm run production');
    run('rm -rf ./node_modules');
    run('rm -rf ./.git');
    run('rm deploy.php');
//    run('sudo /bin/systemctl reload php8.1-fpm.service');
});

// Tasks
task('deploy', [
    'deploy:unlock',
    'deploy:info',
    'deploy:prepare',
    'deploy:update_code',
    'deploy:shared',
    'deploy:writable',
    'deploy:clear_paths',
    'deploy:build',
    'deploy:symlink',
    'deploy:unlock',
]);

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
