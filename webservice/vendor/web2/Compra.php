<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 20/05/16
 * Time: 17:38
 */

namespace web2;

use \dynastyking\database\Base;

class Compra extends Base
{
    public function __construct()
    {
        $this->tabela = "compra";
        $this->campos_valores = array(
            "cliente_id" => NULL,
            "valor_total" => NULL,
            "status" => NULL
        );
        $this->campopk = "id";
    }
}