<?php

require './vendor/autoload.php';
require './homework_input.php';

use Oh\Calc;
use Oh\Model\Kovetelmeny;
use Oh\Model\IntezmenySzak;
use Oh\Model\KovetelmenyRepository;
use Oh\Model\NyelvvizsgaRepository;
use Oh\Model\Nyelvvizsga;
use Oh\Model\Tantargy;
use Oh\Model\TantargyRepository;
use Oh\Model\Tobbletpont;

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

if ($alappont > 0) {
    $tobbletpont = $calc->getTobbletpont() > 100 ? 100 : $calc->getTobbletpont();
    echo $alappont + $tobbletpont . " ( " . $alappont . " alappont + " . $tobbletpont . " többletpont )";
} else {
    echo $calc->getErrormessage();
}
