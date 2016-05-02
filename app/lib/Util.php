<?php
/**
 * Implementação de uma classe de ferramentas gerais do projeto.
 *
 * @author Maykel S. Braz <maykelsb@yahoo.com.br>
 */

/**
 * Classe de ferramentas do sistema.
 */
class Util {
    /**
     * @var \Plasticbrain\FlashMessages\FlashMessages Gerenciador de mensagens entre requisições.
     */
    protected static $flashMessage = null;

    /**
     * Retorna referência para o gerenciador de mensagens.

     * @return \Plasticbrain\FlashMessages\FlashMessages
     */
    public static function getFm()
    {
        if (is_null(self::$flashMessage)) {
            self::$flashMessage = new \Plasticbrain\FlashMessages\FlashMessages();
        }

        return self::$flashMessage;
    }

    public static function checaRequisicao()
    {
        return isset($_REQUEST['acao']);
    }

    public static function processaRequisicao()
    {
        $acao = self::nomeAcao($_REQUEST['acao']);
        (new Requisicoes())->$acao($_REQUEST['dados']);
    }

    protected static function nomeAcao($acao)
    {
        return 'acao' . str_replace('-', '', ucfirst($acao));
    }
}
