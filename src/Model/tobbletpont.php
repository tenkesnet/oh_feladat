<?php

namespace Oh\Model;

class Tobbletpont {
    private int $_emeltszint;
    private array $_nyelvvizsgak;

    function __construct($emeltszint,$nyelvvizsgak)
    {
        $this->_emeltszint = $emeltszint;
        $this->_nyelvvizsgak = $nyelvvizsgak;
    }

    function calc() : int {
        return 0;
    }
}