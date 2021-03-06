<?php
require_once dirname(__FILE__) . '/../app/config.php';

if (Util::checkRequest()) {
    Util::handleRequest();
}

echo $twig->loadTemplate('posts.html.twig')->render([
    'fm' => Util::getFm(),
    'timeline' => Util::getRepo()->listTimeline(),
    'filters' => filter_input_array(INPUT_GET),
    'shouts' => Util::getRepo()->listShouts(filter_input_array(INPUT_GET)),
    'tags' => Util::getRepo()->topTags(),
    'shouters' => Util::getRepo()->top5Shouters()
]);
