<?php
ini_set('display_errors', 'on');

require_once '../vendor/autoload.php';

$twigLoader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($twigLoader, array(
    'cache' => '../temp/'
));

echo $twig->loadTemplate('posts.html.twig')->render([]);