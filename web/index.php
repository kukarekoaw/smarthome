<?php
/**
 * Created by PhpStorm.
 * User: kukareko
 * Date: 17.10.16
 * Time: 18:58
 */

require_once __DIR__.'/../vendor/autoload.php';


use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;

use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SwiftmailerServiceProvider;


$app = new Application();
$app['debug'] = true;

// Подключение роутов
$app['routes'] = $app->extend('routes', function (RouteCollection $routes) {
    $loader     = new YamlFileLoader(new FileLocator(__DIR__ . '/../config'));
    $collection = $loader->load('routes.yml');
    $routes->addCollection($collection);

    return $routes;
});

// Подключение БД
$app->register(new DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_mysql',
        'host'      => 'localhost',
        'dbname'    => 'smarthome',
        'user'      => 'root',
        'password'  => 'toor',
        'charset'   => 'utf8mb4',
    ),
));
// Подключение почтовика
$app->register(new SwiftmailerServiceProvider());
$app['swiftmailer.options'] = array(
    'host' => 'smtp.yandex.ru',
    'port' => '465',
    'username' => 'smarthome@aiiya.ru',
    'password' => '8878smart8878',
    'encryption' => 'ssl',
    'auth_mode' => null
);

/**
 * Отправка почты
 */
/*
$app->get('/feedback', function () use ($app) {
    $message = Swift_Message::newInstance()
        ->setSubject('[SmartHome] Mail')
        ->setFrom(array('smarthome@aiiya.ru'), "SmartHomeAIIYA")
        ->setTo(array('kukarekoaw@gmail.com'))
        ->setBody('First message from AIIYA SmartHome ))')
        ->addPart('<q>Here is the message itself</q>', 'text/html');

    $app['mailer']->send($message);

    return new Response('Thank you for your feedback!', 201);
});
*/
//echo "<pre>";print_r($app);echo "</pre>";
$app->run();