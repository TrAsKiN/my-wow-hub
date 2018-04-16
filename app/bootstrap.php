<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

Symfony\Component\Debug\ErrorHandler::register();
Symfony\Component\Debug\ExceptionHandler::register();

$curl = new Curl\Curl();
$curl->setOpt(CURLOPT_CONNECTTIMEOUT, 10);
$curl->setOpt(CURLOPT_RETURNTRANSFER, true);
$curl->setOpt(CURLOPT_HEADER, false);
$curl->setOpt(CURLOPT_SSL_VERIFYPEER, false);
$app['curl'] = $curl;

$app['locale'] = LOCALE;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));
$app['twig'] = $app->extend('twig', function (Twig_Environment $twig, $app) {
    $twig->addExtension(new Cocur\Slugify\Bridge\Twig\SlugifyExtension(Cocur\Slugify\Slugify::create()));
    if ($app['debug']) {
        $twig->addExtension(new Twig_Extension_Debug());
    }
    return $twig;
});
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\AssetServiceProvider());

if ($app['debug']) {
    $app->register(new Silex\Provider\VarDumperServiceProvider());
    $app->register(new Silex\Provider\MonologServiceProvider(), array(
        'monolog.logfile' => __DIR__.'/logs/silex_dev.log',
    ));
    $app->register(new Silex\Provider\HttpFragmentServiceProvider());
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__ . '/cache/profiler',
    ));
}

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    $templates = array(
        'Exception/'.$code.'.html.twig',
        'Exception/'.substr($code, 0, 2).'x.html.twig',
        'Exception/'.substr($code, 0, 1).'xx.html.twig',
        'Exception/error.html.twig',
    );
    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
