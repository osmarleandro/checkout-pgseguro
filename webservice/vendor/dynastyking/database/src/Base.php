<?php

namespace dynastyking\database;


class Base
{
    public $tabela = "";
    public $campos_valores = array();
    public $campopk = NULL;
    public $valorpk = NULL;
    public $extras_select = "";

    function insert($conn = NULL)
    {
        if ($conn == NULL) {
            $conn = new \dynastyking\database\Connection();
        }
        if ($this->campopk == NULL || count($this->campos_valores) <= 0) {
            throw new \Exception("invalid fields");
        }
        $sql = "INSERT INTO " . $this->tabela . " SET ";
        $args = array();
        $size = count($this->campos_valores);
        $i = 1;
        foreach ($this->campos_valores as $key => $val) {
            $sql .= $key."=?";
            $args[] = $val;
            if ($i != $size) {
                $sql .= ", ";
            }
            $i++;
        }
        return $conn->execQuery($sql, $args);
    }

    function update($conn = NULL)
    {
        if ($conn == NULL) {
            $conn = new \dynastyking\database\Connection();
        }
        if ($this->campopk == NULL || count($this->campos_valores) <= 0) {
            throw new \Exception("invalid fields");
        }
        $sql = "UPDATE " . $this->tabela . " SET ";
        $args = array();
        $size = count($this->campos_valores);
        $i = 1;
        foreach ($this->campos_valores as $key => $val) {
            $sql .= $key."=?";
            $args[] = $val;
            if ($i != $size) {
                $sql .= ", ";
            }
            $i++;
        }
        $sql .= " WHERE " . $this->campopk . "=?";
        $args[] = $this->valorpk;
        return $conn->execQuery($sql, $args);
    }

    function delete($conn = NULL)
    {
        if ($conn == NULL) {
            $conn = new \dynastyking\database\Connection();
        }
        if ($this->campopk == NULL || count($this->campos_valores) <= 0) {
            throw new \Exception("invalid fields");
        }
        $sql = "DELETE FROM " . $this->tabela . " WHERE " . $this->campopk . "=?";
        $args = array($this->valorpk);
        return $conn->execQuery($sql, $args);
    }

    function select($conn = NULL)
    {
        if ($conn == NULL) {
            $conn = new \dynastyking\database\Connection();
        }
        if ($this->campopk == NULL || count($this->campos_valores) <= 0) {
            throw new \Exception("invalid fields");
        }
        $sql = "SELECT * FROM " . $this->tabela;
        if ($this->valorpk != NULL) {
            $sql .= " WHERE " . $this->campopk . "=?";
            $args = array($this->valorpk);
            return $conn->selectQuery($sql, $args);
        }
        return $conn->selectQuery($sql);
    }

    public function addCampo($campo, $valor = NULL)
    {
        $this->campos_valores[$campo] = $valor;
    }

    public function delCampo($campo)
    {
        if (array_key_exists($campo, $this->campos_valores)) {
            unset($this->campos_valores[$campo]);
        }
    }

    public function setValor($campo, $valor)
    {
        $this->campos_valores[$campo] = $valor;
    }

    public function getValor($campo)
    {
        if (array_key_exists($campo, $this->campos_valores)) {
            return $this->campos_valores[$campo];
        } else {
            return FALSE;
        }
    }
}