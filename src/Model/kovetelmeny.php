<?php

namespace Oh\Model;

use Oh\Model\IntezmenySzak;

class Kovetelmeny
{
    private string $_kotelezo;
    private array $_valasztahtok;
    private ?string $_kotelezoszint;
    private IntezmenySzak $_intezmenyszak;

    function __construct(IntezmenySzak $intezmenyszak, string $kotelezo, ?string $kotelezoszint, array $valaszthatok)
    {
        $this->_kotelezo = $kotelezo;
        $this->_valasztahtok = $valaszthatok;
        $this->_intezmenyszak = $intezmenyszak;
        $this->_kotelezoszint = $kotelezoszint;
    }

    public function getIntezmenyszak(): IntezmenySzak
    {
        return $this->_intezmenyszak;
    }

    public function getKotelezo(): string
    {
        return $this->_kotelezo;
    }

    public function getKotelezoszint(): ?string
    {
        return $this->_kotelezoszint;
    }

    /**
     * Valasztható tantárgyak listája. 
     *
     * @return array
     * 
     * Visszatérési érték egy string tömb.
     */
    public function getValaszthatok(): array
    {
        return $this->_valasztahtok;
    }
}
