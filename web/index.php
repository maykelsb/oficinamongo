<?php
require_once dirname(__FILE__) . '/../app/config.php';

if (Util::checaRequisicao()) {
    Util::processaRequisicao();
}

echo $twig->loadTemplate('posts.html.twig')->render([
    'fm' => Util::getFm(),
    'posts' => Db::getInstance()->query(
        [],
        ['sort' => ['autor' => 1]]
    )->toArray(),
    'tags' => Db::getInstance()->aggregate([
        [
            '$group' => [
                '_id' => '$tags',
                'qtd' => ['$sum' => 1]
            ],
        ],[
            '$project' => [
                '_id' => 0,
                'tags' => '$_id',
                'qtd' => 1
            ]
        ],[
            '$sort' => ['qtd' => -1]
        ]
    ])->toArray()
]);
