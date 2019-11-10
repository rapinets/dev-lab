<?php
/*
 *  Using Dependency Injection Container with auto wiring.
 *  Author: artday.
 *  See: https://github.com/artday/dev-notes in container-autowiring branch
 */

use App\Configuration\Configuration;
use App\Container\Container;
use App\Controllers\HomeController;
use App\Database\Database;

require_once __DIR__ . '/vendor/autoload.php';

$app = new Container;

/* Using as Container||Registry Pattern

$app->share('config', function () {
    return new Configuration;
});

$app->share('db', function ($app) {
    return new Database($app->get('config'));
});

$app->set('home', function ($app) {
    return new HomeController($app->get('config'), $app->get('db'));
});

dump(
    $app->get('home')
);

*/

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

/*$app->share(Configuration::class, function () {
    return new Configuration;
});

$app->share(Database::class, function ($app) {
    return new Database($app->get(Configuration::class));
});

*/

////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////

dump(
    $app->get(HomeController::class)
);


////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/*
 * Static Example
 *
 */

/*function hello($msg = '') {
    static $text = '';
    if(!$text){
        $text = $msg ? $msg : 'Guest';
    }
    dump('Hello, ' . $text);
}

hello('Rapinec');
hello();
hello();
hello();
hello();
*/
