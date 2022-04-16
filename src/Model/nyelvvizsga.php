<?php

namespace Oh\Model;

class Nyelvvizsga
{
    private string $_tipus;
    private int $_pont;

    function __construct( string $tipus , int $pont )
    {
        $this->_tipus = $tipus;
        $this->_pont = $pont;
    }

    public function getTipus() : string
    {
        return $this->_tipus;
    }

    public function getpont() : int
    {
        return $this->_pont;
    }
}
