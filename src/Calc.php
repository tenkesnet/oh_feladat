<?php

namespace Oh;

use Oh\Model\IntezmenySzak;
use Oh\Model\Kovetelmeny;
use Oh\Model\Tantargy;
use Oh\Model\TantargyRepository;
use Oh\Model\Tobbletpont;

class Calc
{
    private array $_szak;
    private array $_eretsegi;
    private array $_tobblet;

    private array $_kovetelmenyek;
    private Kovetelmeny $_kovetelmeny;
    private IntezmenySzak $_intezmenyszak;
    private Tobbletpont $_tobbletpontok;
    private TantargyRepository $_tantargyrepo;
    private TantargyRepository $_kotelezotantargyak;
    private Tantargy $_kotelezo;
    private string $_errormessage;

    function __construct(array $exampleData, TantargyRepository $kotelezotantargyak , array $kovetelmenyek, Tobbletpont $tobbletpontok)
    {
        $this->_szak = $exampleData['valasztott-szak'];
        $this->_eretsegi = $exampleData['erettsegi-eredmenyek'];
        $this->_tobblet = $exampleData['tobbletpontok'];
        $this->_kotelezotantargyak = $kotelezotantargyak;
        $this->_errormessage = "";

        $this->_kovetelmenyek = $kovetelmenyek;
        $this->_tobbletpontok = $tobbletpontok;
        $this->_tantargyrepo = new TantargyRepository();

        $this->_intezmenyszak = new IntezmenySzak($this->_szak['egyetem'], $this->_szak['kar'], $this->_szak['szak']);
        foreach ($kovetelmenyek as $kovetelmeny) {
            if ($this->_intezmenyszak->isEqual($kovetelmeny->getIntezmenyszak())) {
                $this->_kovetelmeny = $kovetelmeny;
            }
        }
    }
    public function calculate(): int
    {
        $eredmeny = 0;
        $kotelezo = 0;
        $valaszthato = 0;

        $this->valaszthatokFeltoltese();
        $this->checkKotelezotantargyak();
        
        
        if($this->_errormessage=="") {
            $kotelezo = $this->getKotelezo();
            $valaszthato = $this->getValaszthatoMax();
            $eredmeny = ($kotelezo + $valaszthato) * 2;
        }
        
        return $eredmeny;
    }

    public function valaszthatokFeltoltese() : bool
    {
        $eredmeny = true;
        $pontszamalatt = [];
        foreach ($this->_eretsegi as $tantargy) {
            $t = new Tantargy($tantargy['nev'], $tantargy['tipus'], rtrim($tantargy['eredmeny'], '%'));
            if ($tantargy['nev'] == $this->_kovetelmeny->getKotelezo()) {
                $this->_kotelezo = $t;
            }
            if(rtrim($tantargy['eredmeny'],'%')<20) {
                $pontszamalatt[] = $tantargy['nev'];
                $eredmeny = false;
            }
            $this->_tantargyrepo->add($t);
        }
        if($eredmeny == false)
            $this->_errormessage = "hiba, nem lehetséges a pontszámítás a " . implode(", ",$pontszamalatt) . " tárgy(ak)ból elért 20% alatti eredmény miatt";
        return $eredmeny;
    }

    public function getValaszthatoMax(): int
    {
        $max = 0;
        foreach ($this->_kovetelmeny->getValaszthatok() as $tantargy) {
            if ($talat = $this->_tantargyrepo->searchNev($tantargy)) {
                $max = $talat->getEredmeny() > $max ? $talat->getEredmeny() : $max;
            }
        }
        return $max;
    }

    public function getKotelezo(): int
    {
        $eredmeny = 0;
        if ($tantargy = $this->_tantargyrepo->searchNev($this->_kovetelmeny->getKotelezo())) {
            if ($this->_kovetelmeny->getKotelezoszint() == null || $tantargy->getTipus() == $this->_kovetelmeny->getKotelezoszint()) {
                $eredmeny = $tantargy->getEredmeny();
            }
        }
        return $eredmeny;
    }
    public function getTobbletpont() : int
    {
        $tobbletpont = 0;
        $emeltszint = $this->_kotelezo->getTipus()=='emelt' ? $this->_tobbletpontok->getEmeltszint() : 0;
        foreach($this->_tobblet as $nyelvizsga) {
            $tobbletpont += $this->_tobbletpontok->getNyelvizsgak()->searchPont($nyelvizsga['tipus']);
        }
        return $emeltszint+$tobbletpont;
    }

    public function getErrormessage() : string
    {
        return $this->_errormessage;
    }

    private function checkKotelezotantargyak() :bool
    {
        $eredmeny = true;
        foreach($this->_kotelezotantargyak->getTantargyak() as $tantargy) {
            if($this->_tantargyrepo->searchNev($tantargy->getNev()) == false )
            {
                $eredmeny = false;
                $this->_errormessage = "hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt";
            } 
                
        }
        return $eredmeny;
    }

}
