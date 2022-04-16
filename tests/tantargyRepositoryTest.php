<?php

use Oh\Model\TantargyRepository;
use Oh\Model\Tantargy;
use PHPUnit\Framework\TestCase;

class tantargyRepositoryTest extends TestCase
{
    private TantargyRepository $_repo;

    public function testSearchNev()
    {
        $this->_repo = new TantargyRepository();
        $this->_repo->add(new Tantargy('magyar nyelv és irodalom'));
        $this->_repo->add(new Tantargy('történelem'));
        $this->_repo->add(new Tantargy('matematika'));


        $eredmeny = $this->_repo->searchNev("történelem");
        $this->assertEquals("történelem", $eredmeny->getNev());
    }
}
