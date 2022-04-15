<?php

namespace Oh;

use Oh\Model\Kovetelmeny;
use Oh\Model\Tobbletpont;

class Calc
{
    private array $_szak;
    private array $_eretsegi;
    private array $_tobblet;

    private Kovetelmeny $_kovetelmenyek;
    private Tobbletpont $_tobbletpontok;

    function __construct(array $exampleData, Kovetelmeny $kovetelmenyek , Tobbletpont $tobbletpontok)
    {
        $this->_szak = $exampleData['valasztott-szak'];
        $this->_eretsegi = $exampleData['erettsegi-eredmenyek'];
        $this->_tobblet = $exampleData['tobbletpontok'];

        $this->_kovetelmenyek = $kovetelmenyek;
        $this->_tobbletpontok = $tobbletpontok;
    }
}
