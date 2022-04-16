<?php

use Oh\Model\NyelvvizsgaRepository;
use Oh\Model\Nyelvvizsga;
use PHPUnit\Framework\TestCase;

class nyelvvizsgaRepositoryTest extends TestCase
{
    public function testSearchPont()
    {
        $nyelvvizsgak = new NyelvvizsgaRepository();
        $nyelvvizsgak->add(new Nyelvvizsga('B2', 28));
        $nyelvvizsgak->add(new Nyelvvizsga('C1', 40));


        $eredmeny = $nyelvvizsgak->searchPont("C1");
        $this->assertEquals(40, $eredmeny);
    }
}
