<?php

use \Cocur\Slugify\Slugify;
use \Cocur\Slugify\Bridge\Twig\SlugifyExtension;

$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig = new Twig_Environment($loader);
$twig->addExtension(new SlugifyExtension(Slugify::create()));
/*
$twig = new Twig_Environment($loader, array(
    'debug' => true
));
$twig->addExtension(new Twig_Extension_Debug());
*/
