<?php
ini_set('display_errors', 'on');

require_once '../vendor/autoload.php';
require_once '../lib/db.php';

$twigLoader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($twigLoader, array(
    'cache' => '../temp/'
));

$db = Db::getInstance();
var_dump($db->getCollection());

echo $twig->loadTemplate('posts.html.twig')->render([]);