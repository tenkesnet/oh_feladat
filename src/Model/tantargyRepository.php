<?php

namespace Oh\Model;

class TantargyRepository
{
    private array $_tantargyak;


    function __construct()
    {
        $this->_tantargyak = [];
    }

    public function add(Tantargy $tantargy)
    {
        $this->_tantargyak[] = $tantargy;
    }

    public function searchNev(string $nev): ?Tantargy
    {
        foreach ($this->_tantargyak as $tantargy) {
            if ($nev == $tantargy->getNev()) {
                return $tantargy;
            }
        }
        return null;
    }

    public function getTantargyak() : array
    {
        return $this->_tantargyak;
    }
}
