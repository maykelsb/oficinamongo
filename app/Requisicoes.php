<?php
class Requisicoes
{
    public function actionShout(array $dados)
    {
        try {
            $db = Db::getInstance();

            $this->tags($dados['tags']);
            $dados['when'] = $this->getNowUTCDateTime();
            $db->insert($dados);

            $mensagem = 'Registro inserido com sucesso.';
            $metodo = 'success';
        } catch (Exception $e) {
            $mensagem = 'Não foi possível inserir o novo registro. Motivo: ' . $e->getMessage();
            $metodo = 'error';
        }

        Util::getFm()->$metodo($mensagem);
        header("Location: /");
        die();
    }

    protected function tags(&$tags)
    {
        $tags = explode(',', $tags);
        array_walk($tags, function(&$tag){
            $tag = trim($tag);
        });
    }

    protected function getNowUTCDateTime()
    {
        $utc = new MongoDB\BSON\UTCDateTime(
            (new DateTime())->getTimestamp()
        );

        return $utc;
    }
}
