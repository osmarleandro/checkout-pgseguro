<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 20/05/16
 * Time: 17:38
 */

namespace web2;

use \dynastyking\database\Base;

class Produto extends Base
{
    public function __construct()
    {
        $this->tabela = "produto";
        $this->campos_valores = array(
            "descricao" => NULL,
            "qtd" => NULL,
            "preco" => NULL
        );
        $this->campopk = "id";
    }
}