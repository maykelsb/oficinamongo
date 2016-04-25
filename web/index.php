<?php
ini_set('display_errors', 'on');

require_once '../vendor/autoload.php';
require_once '../lib/db.php';

$twigLoader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($twigLoader, array(
    //'cache' => '../temp/'
));

#$db = Db::getInstance();

#$query = new MongoDB\Driver\Query([]);
#echo '<pre>';
#foreach ($db->query($query, 'about') as $r) {
#	print_r($r);
#}
#echo '</pre>';

echo $twig->loadTemplate('posts.html.twig')->render([]);
