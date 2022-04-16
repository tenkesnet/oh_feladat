<?php

namespace Oh\Model;

class Tobbletpont
{
    private int $_emeltszint;
    private NyelvvizsgaRepository $_nyelvvizsgak;

    function __construct(int $emeltszint, NyelvvizsgaRepository $nyelvvizsgak)
    {
        $this->_emeltszint = $emeltszint;
        $this->_nyelvvizsgak = $nyelvvizsgak;
    }

    function calc(): int
    {
        return 0;
    }

    public function getEmeltszint(): int
    {
        return $this->_emeltszint;
    }

    public function getNyelvizsgak(): NyelvvizsgaRepository
    {
        return $this->_nyelvvizsgak;
    }
}
