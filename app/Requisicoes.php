<?php
class Requisicoes
{
    public function acaoNovopost($dados)
    {
        try {
            $db = Db::getInstance();
            $db->inserir($dados);

            $mensagem = 'Registro inserido com sucesso.';
            $metodo = 'success';
        } catch (Exception $e) {
            $mensagem = 'Não foi possível inserir o novo registro. Motivo: ' . $e->getMessage();
            $metodo = 'error';
        }

        Util::getFm()->$metodo($mensagem);
        header("Location: {$_SERVER['REQUEST_URI']}");
        die();
    }
}
