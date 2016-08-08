<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 20/05/16
 * Time: 17:38
 */

namespace web2;

use \dynastyking\database\Base;

class Cliente extends Base
{
    public function __construct()
    {
        $this->tabela = "cliente";
        $this->campos_valores = array(
            "lvl" => NULL,
            "nome" => NULL,
            "sobrenome" => NULL,
            "email" => NULL,
            "senha" => NULL
        );
        $this->campopk = "id";
    }
}