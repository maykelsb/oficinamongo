<?php
require_once dirname(__FILE__) . '/../app/config.php';

if (Util::checaRequisicao()) {
    Util::processaRequisicao();
}

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
