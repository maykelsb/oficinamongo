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

    public function actionPitaco(array $dados)
    {
        try {
            $filter = ['_id' => new MongoDb\BSON\ObjectID($dados['postid'])];
            unset($dados['postid']);

            $dados['when'] = $this->getNowUTCDateTime();

            $dados = [];

            $db = Db::getInstance();
            $db->update($filter, $dados);
            $mensagem = 'Pitaco adicionado com sucesso.';
            $metodo = 'success';
        } catch (Exception $e) {
            $mensagem = 'Não foi possível adicionar o novo pitaco. Motivo: ' . $e->getMessage();
            $metodo = 'error';
        }

        Util::getFm()->$metodo($mensagem);
        header("Location: /");
        die();
    }

    public function actionLike(array $dados)
    {
        try {
            $db = Db::getInstance();

            throw new Exception('Command não implementado');

            die(json_encode([
                'likes' => $dadosUpdate[0]->value->likes
            ]));
        } catch (Exception $e) {
            die(json_encode(['error' => $e->getMessage()]));
        }
    }

    public function actionTop10pitacados()
    {
        global $twig;

        try {
            $shouts = Util::getRepo()
                ->top10Pitacados();

            echo $twig->loadTemplate('partials/post.html.twig')
                ->render(['shouts' => $shouts, 'prefix' => 'toppitacados_']);

            die();
        } catch (Exception $e) {
            die('<p class="danger">Não foi possível completar sua requisição. Motivo: ' . $e->getMessage() . '</p>');
        }
    }

    public function actionTop10likes()
    {
        global $twig;

        try {

            $shouts = Util::getRepo()
                ->top10Likes();
            echo $twig->loadTemplate('partials/post.html.twig')
                ->render(['shouts' => $shouts, 'prefix' => 'toplikes_']);

            die();
        } catch (Exception $e) {
            die('<p class="danger">Não foi possível completar sua requisição. Motivo: ' . $e->getMessage() . '</p>');
        }
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
