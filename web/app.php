<?php

error_reporting(-1);
ini_set('display_errors', 1);

if (file_exists('deploy')) {
    exit('Site updating.. Please try again in 30 seconds');
}

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

if (getenv("SYMFONY_ENV")) {


    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require __DIR__ . '/../app/autoload.php';
    include_once __DIR__ . '/../var/bootstrap.php.cache';

    $kernel = new AppKernel(getenv("SYMFONY_ENV"), false);
    $kernel->loadClassCache();
    $kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
    $request = Request::createFromGlobals();
    //https://stackoverflow.com/questions/11957811/symfony2-behind-amazon-elb-always-trust-proxy-data
    if(getenv("SYMFONY_ENV") == 'heroku') {
        Request::setTrustedProxies(array($request->server->get('REMOTE_ADDR')));
//        Request::setTrustedHeaderName(Request::HEADER_FORWARDED, null);
//        Request::setTrustedHeaderName(Request::HEADER_CLIENT_HOST, null);
    }
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} elseif (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !(in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) || php_sapi_name() === 'cli-server')
) {
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require __DIR__ . '/../app/autoload.php';
    include_once __DIR__ . '/../var/bootstrap.php.cache';

    $kernel = new AppKernel("prod", true);
    $kernel->loadClassCache();
    $kernel = new AppCache($kernel);

// When using the HttpCache, you need to call the method in your front controller instead of relying on the configuration parameter
//Request::enableHttpMethodParameterOverride();
    $request = Request::createFromGlobals();
    //https://stackoverflow.com/questions/11957811/symfony2-behind-amazon-elb-always-trust-proxy-data
    Request::setTrustedProxies(array($request->server->get('REMOTE_ADDR')));
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} else {
    /** @var \Composer\Autoload\ClassLoader $loader */
    $loader = require __DIR__ . '/../app/autoload.php';
    Debug::enable();
    $kernel = new AppKernel('dev', true);
    $kernel->loadClassCache();
    $kernel = new AppCache($kernel);
    $request = Request::createFromGlobals();
    //https://stackoverflow.com/questions/11957811/symfony2-behind-amazon-elb-always-trust-proxy-data
    Request::setTrustedProxies(array($request->server->get('REMOTE_ADDR')));
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
}




