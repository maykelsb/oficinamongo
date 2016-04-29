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
