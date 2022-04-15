<?php

namespace Oh\Model;

class IntezmenySzak {
    private string $_egyetem;
    private string $_kar;
    private string $_szak;

    function __construct(string $egyetem, string $kar, string $szak)
    {
        $this->_egyetem = $egyetem;
        $this->_kar = $kar;
        $this->_szak = $szak;
    }

    function getEgyetem() : string
    {
        return $this->_egyetem;
    }
    function getKar() : string
    {
        return $this->_kar;
    }
    function getSzak() : string
    {
        return $this->_szak;
    }
}