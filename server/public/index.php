<?php

session_start();

use core\Logger\LoggerInterface;
use core\Logger\FileLogger;
use core\FileSystem\FileSystemInterface;
use core\FileSystem\FileSystem;
use core\Router;
use core\DependencyInjection\Container;
use core\Auth\AuthenticateInterface;
use core\Auth\Authenticate;
use core\Auth\AuthorizeInterface;
use core\Auth\Authorize;
use core\User\UserProviderInterface;
use core\User\UserProvider;
use core\Auth\AuthInterface;
use core\Auth\Auth;
use core\Registration\RegistrationInterface;
use core\Registration\Registration;

define('WWW', __DIR__);
define('CORE', dirname(__DIR__) . '/core');
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
define('TIME_ZONE', 'Europe/Kiev');

spl_autoload_register(function ($class) {
    $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

$query = rtrim($_SERVER['QUERY_STRING'], '/');

$dependencyContainer = Container::getInstance();

$dependencyContainer
    ->set(FileSystemInterface::class, FileSystem::class)
    ->set(LoggerInterface::class, FileLogger::class);
    
try {
    $dependencyContainer
        ->set(UserProviderInterface::class, UserProvider::class)
        ->set(AuthInterface::class, Auth::class)
        ->set(AuthenticateInterface::class, Authenticate::class)
        ->set(AuthorizeInterface::class, Authorize::class)
        ->set(RegistrationInterface::class, Registration::class);

    Router::setLogger($dependencyContainer->get(LoggerInterface::class));

    Router::add('^$', [
        'controller' => 'main',
        'action'     => 'index',
    ]);

    Router::add('^sign-in$', [
        'controller' => 'security',
        'action'     => 'signIn',
    ]);

    Router::add('^sign-up$', [
        'controller' => 'security',
        'action'     => 'signUp',
    ]);

    Router::add('^info/sources/store$', [
        'controller' => 'info',
        'action'     => 'store',
    ]);

    Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

    Router::dispatch($query);
} catch (\Exception $e) {
    $dependencyContainer->get(LoggerInterface::class)->error($e->__toString());
    throw $e;
}
