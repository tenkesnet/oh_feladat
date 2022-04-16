<?php

namespace Oh\Model;

class NyelvizsgaRepository
{
    private array $_nyelvizsgak;


    function __construct()
    {
        $this->_nyelvizsgak = [];
    }

    public function add(Nyelvvizsga $nyelvizsga)
    {
        $this->_nyelvizsgak[] = $nyelvizsga;
    }

    public function searchPont(string $tipus): int
    {
        $pont = 0;
        foreach ($this->_nyelvizsgak as $nyelvizsga) {
            if ($tipus == $nyelvizsga->getTipus()) {
                $pont = $nyelvizsga->getPont();
            }
        }
        return $pont;
    }
}
