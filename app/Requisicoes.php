<?php
class Requisicoes
{
    public function acaoNovopost($dados)
    {
        $db = Db::getInstance();

        $db->inserir(
            $db->criarCommand(['insert' => 'blog', 'field' => $dados])
        );
    }
}
