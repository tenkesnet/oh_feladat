<?php

require './vendor/autoload.php';
require './homework_input.php';

use Oh\Calc;
use Oh\Model\Kovetelmeny;
use Oh\Model\IntezmenySzak;
use Oh\Model\Nyelvvizsga;
use Oh\Model\Tobbletpont;

$kovetelmenyek = [
    new Kovetelmeny(new IntezmenySzak('ELTE','IK','Programtervező informatikus'),'matematika',null,[
    'biológia','fizika','informatika','kémia'
    ]),
    new Kovetelmeny(new IntezmenySzak('PPKE','BTK','Anglisztika'),'angol','emelt',[
        'francia','német','olasz','orosz','spanyol','történelem'
        ]),
    ];
$nyelvvizsgak = [
    new Nyelvvizsga('B2',28),
    new Nyelvvizsga('C1',40)
];
$tobbletpont = new Tobbletpont(50,$nyelvvizsgak);

$calc = new Calc($exampleData , $kovetelmenyek , $tobbletpont);

