<?php

namespace Oh\Model;

class Tantargy {
    private string $_nev;
    private string $_tipus;
    private int $_eredmeny;

    function __construct(string $nev, string $tipus="", int $eredmeny=0)
    {
        $this->_nev = $nev;
        $this->_tipus = $tipus;
        $this->_eredmeny = $eredmeny;    
    }

    public function getNev() : string {
        return $this->_nev;
    }
    
    public function getTipus() : string {
        return $this->_tipus;
    }

    public function getEredmeny() : string {
        return $this->_eredmeny;
    }
}