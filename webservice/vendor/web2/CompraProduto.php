<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 20/05/16
 * Time: 17:38
 */

namespace web2;

use \dynastyking\database\Base;

class CompraProduto extends Base
{
    public function __construct()
    {
        $this->tabela = "compraproduto";
        $this->campos_valores = array(
            "compra_id" => NULL,
            "produto_id" => NULL,
            "quantidade" => NULL
        );
        $this->campopk = "id";
    }
}