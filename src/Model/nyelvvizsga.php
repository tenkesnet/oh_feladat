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
}
