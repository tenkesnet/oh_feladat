<?php

use PHPUnit\Framework\TestCase;
use Oh\Calc;
use Oh\Model\Kovetelmeny;
use Oh\Model\IntezmenySzak;
use Oh\Model\KovetelmenyRepository;
use Oh\Model\NyelvvizsgaRepository;
use Oh\Model\Nyelvvizsga;
use Oh\Model\Tantargy;
use Oh\Model\TantargyRepository;
use Oh\Model\Tobbletpont;

class CalcTest extends TestCase
{

    public function testCalc1()
    {
        $exampleData = [
            'valasztott-szak' => [
                'egyetem' => 'ELTE',
                'kar' => 'IK',
                'szak' => 'Programtervező informatikus',
            ],
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'magyar nyelv és irodalom',
                    'tipus' => 'közép',
                    'eredmeny' => '70%',
                ],
                [
                    'nev' => 'történelem',
                    'tipus' => 'közép',
                    'eredmeny' => '80%',
                ],
                [
                    'nev' => 'matematika',
                    'tipus' => 'emelt',
                    'eredmeny' => '90%',
                ],
                [
                    'nev' => 'angol nyelv',
                    'tipus' => 'közép',
                    'eredmeny' => '94%',
                ],
                [
                    'nev' => 'informatika',
                    'tipus' => 'közép',
                    'eredmeny' => '95%',
                ],
            ],
            'tobbletpontok' => [
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'B2',
                    'nyelv' => 'angol',
                ],
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'C1',
                    'nyelv' => 'német',
                ],
            ],
        ];

        $kotelezotantargyak = new TantargyRepository();
        $kotelezotantargyak->add(new Tantargy('magyar nyelv és irodalom'));
        $kotelezotantargyak->add(new Tantargy('történelem'));
        $kotelezotantargyak->add(new Tantargy('matematika'));

        $kovetelmenyek = new KovetelmenyRepository();
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('ELTE', 'IK', 'Programtervező informatikus'), 'matematika', 'emelt', [
            'biológia', 'fizika', 'informatika', 'kémia'
        ]));
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('PPKE', 'BTK', 'Anglisztika'), 'angol', 'emelt', [
            'francia', 'német', 'olasz', 'orosz', 'spanyol', 'történelem'
        ]));

        $nyelvvizsgak = new NyelvvizsgaRepository();
        $nyelvvizsgak->add(new Nyelvvizsga('B2', 28));
        $nyelvvizsgak->add(new Nyelvvizsga('C1', 40));

        $tobbletpont = new Tobbletpont(50, $nyelvvizsgak);

        $calc = new Calc($exampleData, $kotelezotantargyak, $kovetelmenyek, $tobbletpont);

        $alappont = $calc->calculate();
        $tobbletpont = $calc->getTobbletpont() > 100 ? 100 : $calc->getTobbletpont();
        $this->assertEquals(370, $alappont);
        $this->assertEquals(100, $tobbletpont);
    }

    public function testCalc2()
    {
        $exampleData = [
            'valasztott-szak' => [
                'egyetem' => 'ELTE',
                'kar' => 'IK',
                'szak' => 'Programtervező informatikus',
            ],
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'matematika',
                    'tipus' => 'emelt',
                    'eredmeny' => '90%',
                ],
                [
                    'nev' => 'angol nyelv',
                    'tipus' => 'közép',
                    'eredmeny' => '94%',
                ],
                [
                    'nev' => 'informatika',
                    'tipus' => 'közép',
                    'eredmeny' => '95%',
                ],
            ],
            'tobbletpontok' => [
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'B2',
                    'nyelv' => 'angol',
                ],
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'C1',
                    'nyelv' => 'német',
                ],
            ],
        ];

        $kotelezotantargyak = new TantargyRepository();
        $kotelezotantargyak->add(new Tantargy('magyar nyelv és irodalom'));
        $kotelezotantargyak->add(new Tantargy('történelem'));
        $kotelezotantargyak->add(new Tantargy('matematika'));

        $kovetelmenyek = new KovetelmenyRepository();
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('ELTE', 'IK', 'Programtervező informatikus'), 'matematika', 'emelt', [
            'biológia', 'fizika', 'informatika', 'kémia'
        ]));
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('PPKE', 'BTK', 'Anglisztika'), 'angol', 'emelt', [
            'francia', 'német', 'olasz', 'orosz', 'spanyol', 'történelem'
        ]));

        $nyelvvizsgak = new NyelvvizsgaRepository();
        $nyelvvizsgak->add(new Nyelvvizsga('B2', 28));
        $nyelvvizsgak->add(new Nyelvvizsga('C1', 40));

        $tobbletpont = new Tobbletpont(50, $nyelvvizsgak);

        $calc = new Calc($exampleData, $kotelezotantargyak, $kovetelmenyek, $tobbletpont);

        $alappont = $calc->calculate();
        $tobbletpont = $calc->getTobbletpont() > 100 ? 100 : $calc->getTobbletpont();
        $this->assertEquals(0, $alappont);
        $this->assertEquals(100, $tobbletpont);
        $this->assertEquals("hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt", $calc->getErrormessage());
    }

    public function testCalc3()
    {
        $exampleData = [
            'valasztott-szak' => [
                'egyetem' => 'ELTE',
                'kar' => 'IK',
                'szak' => 'Programtervező informatikus',
            ],
            'erettsegi-eredmenyek' => [
                [
                    'nev' => 'magyar nyelv és irodalom',
                    'tipus' => 'közép',
                    'eredmeny' => '15%',
                ],
                [
                    'nev' => 'történelem',
                    'tipus' => 'közép',
                    'eredmeny' => '80%',
                ],
                [
                    'nev' => 'matematika',
                    'tipus' => 'emelt',
                    'eredmeny' => '90%',
                ],
                [
                    'nev' => 'angol nyelv',
                    'tipus' => 'közép',
                    'eredmeny' => '94%',
                ],
                [
                    'nev' => 'informatika',
                    'tipus' => 'közép',
                    'eredmeny' => '95%',
                ],
            ],
            'tobbletpontok' => [
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'B2',
                    'nyelv' => 'angol',
                ],
                [
                    'kategoria' => 'Nyelvvizsga',
                    'tipus' => 'C1',
                    'nyelv' => 'német',
                ],
            ],
        ];

        $kotelezotantargyak = new TantargyRepository();
        $kotelezotantargyak->add(new Tantargy('magyar nyelv és irodalom'));
        $kotelezotantargyak->add(new Tantargy('történelem'));
        $kotelezotantargyak->add(new Tantargy('matematika'));

        $kovetelmenyek = new KovetelmenyRepository();
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('ELTE', 'IK', 'Programtervező informatikus'), 'matematika', 'emelt', [
            'biológia', 'fizika', 'informatika', 'kémia'
        ]));
        $kovetelmenyek->add(new Kovetelmeny(new IntezmenySzak('PPKE', 'BTK', 'Anglisztika'), 'angol', 'emelt', [
            'francia', 'német', 'olasz', 'orosz', 'spanyol', 'történelem'
        ]));

        $nyelvvizsgak = new NyelvvizsgaRepository();
        $nyelvvizsgak->add(new Nyelvvizsga('B2', 28));
        $nyelvvizsgak->add(new Nyelvvizsga('C1', 40));

        $tobbletpont = new Tobbletpont(50, $nyelvvizsgak);

        $calc = new Calc($exampleData, $kotelezotantargyak, $kovetelmenyek, $tobbletpont);

        $alappont = $calc->calculate();
        $tobbletpont = $calc->getTobbletpont() > 100 ? 100 : $calc->getTobbletpont();
        $this->assertEquals(0, $alappont);
        $this->assertEquals(100, $tobbletpont);
        $this->assertEquals("hiba, nem lehetséges a pontszámítás a magyar nyelv és irodalom tárgy(ak)ból elért 20% alatti eredmény miatt", $calc->getErrormessage());
    }
}
