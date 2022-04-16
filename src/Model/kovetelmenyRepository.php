<?php

namespace Oh\Model;

class KovetelmenyRepository
{
    private array $_kovetelmenyek;

    function __construct()
    {
        $this->_kovetelmenyek = [];
    }

    public function add(Kovetelmeny $kovetelmeny)
    {
        $this->_kovetelmenyek[] = $kovetelmeny;
    }

    public function searchByIntezmenySzak(IntezmenySzak $intezmenySzak): ?Kovetelmeny
    {
        $eredmeny = null;
        foreach ($this->_kovetelmenyek as $kovetelmeny) {
            if ($kovetelmeny->getIntezmenySzak()->isEqual($intezmenySzak)) {
                $eredmeny = $kovetelmeny;
            }
        }
        return $eredmeny;
    }
}
