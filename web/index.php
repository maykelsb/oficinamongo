<?php
require_once dirname(__FILE__) . '/../app/config.php';

if (Util::checaRequisicao()) {
    Util::processaRequisicao();
}

echo $twig->loadTemplate('posts.html.twig')->render([
    'fm' => Util::getFm(),
    'posts' => Db::getInstance()->query(['autor'=>'maykel'])->toArray()
]);
