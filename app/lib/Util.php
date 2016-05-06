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
     *
     * @var Repositorio
     */
    protected static $repositorio = null;

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

    /**
     * Retorna uma referência para o repositório de consultas.
     *
     * @return Repositorio
     */
    public static function getRepo()
    {
        if (is_null(self::$repositorio)) {
            self::$repositorio = new Repositorio();
        }

        return self::$repositorio;
    }


    public static function checkRequest()
    {
        return isset($_REQUEST['action']);
    }

    public static function handleRequest()
    {
        $acao = self::nomeAcao($_REQUEST['action']);
        (new Requisicoes())
            ->$acao(filter_input(
                INPUT_POST,
                'data',
                FILTER_DEFAULT,
                FILTER_REQUIRE_ARRAY
            ));
    }

    protected static function nomeAcao($acao)
    {
        return 'action' . str_replace('-', '', ucfirst($acao));
    }

    public static function monthPtBr($month)
    {
        switch ($month)
        {
            case 1: return 'Jan';
            case 2: return 'Feb';
            case 3: return 'Mar';
            case 4: return 'Abr';
            case 5: return 'Mai';
            case 6: return 'Jun';
            case 7: return 'Jul';
            case 8: return 'Ago';
            case 9: return 'Set';
            case 10: return 'Out';
            case 11: return 'Nov';
            case 12: return 'Dez';
        }
    }
}
