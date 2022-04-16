<?php

use Oh\Model\KovetelmenyRepository;
use Oh\Model\Kovetelmeny;
use Oh\Model\IntezmenySzak;
use PHPUnit\Framework\TestCase;

class KovetelmenyRepositoryTest extends TestCase
{
    public function testSearchPont()
    {
        $kovetelmenyek = new KovetelmenyRepository();
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('ELTE', 'IK', 'Programtervező informatikus'), 'matematika', 'emelt', [
            'biológia', 'fizika', 'informatika', 'kémia'
        ]));
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('PPKE', 'BTK', 'Anglisztika'), 'angol', 'emelt', [
            'francia', 'német', 'olasz', 'orosz', 'spanyol', 'történelem'
        ]));

        $szak = new IntezmenySzak('ELTE', 'IK', 'Programtervező informatikus');
        $eredmeny = $kovetelmenyek->searchByIntezmenySzak($szak);
        $this->assertEquals("matematika", $eredmeny->getKotelezo());
    }
}
