<?php
/**
 * Configurações gerais da aplicação.
 */

ini_set('display_errors', 'on');
session_start();

require_once dirname(__FILE__) . '/../vendor/autoload.php';
require_once dirname(__FILE__) . '/../app/lib/Db.php';
require_once dirname(__FILE__) . '/../app/lib/Util.php';
require_once dirname(__FILE__) . '/../app/Requisicoes.php';
require_once dirname(__FILE__) . '/../app/Repositorio.php';

$twigLoader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($twigLoader, array(
    'debug' => true
    //'cache' => '../temp/'
));

$twig->addFilter(
    new Twig_SimpleFilter('monthPtBr', ['Util', 'monthPtBr'])
);



$twig->addExtension(new Twig_Extension_Debug());
