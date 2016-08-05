<?php

$loader = new Twig_Loader_Filesystem('./templates');
$twig = new Twig_Environment($loader);
/*
$twig = new Twig_Environment($loader, array(
    'debug' => true
));
$twig->addExtension(new Twig_Extension_Debug());
*/
