<?php

namespace Oh;

use Oh\Model\IntezmenySzak;
use Oh\Model\Kovetelmeny;
use Oh\Model\KovetelmenyRepository;
use Oh\Model\Tantargy;
use Oh\Model\TantargyRepository;
use Oh\Model\Tobbletpont;

class Calc
{
    private array $_szak;
    private array $_eretsegi;
    private array $_tobblet;

    private Kovetelmeny $_kovetelmeny;                  //Adott kalkulációhoz tartozó kovetelmenyrendszer. Konstruktorban számolódik ki
    private IntezmenySzak $_intezmenyszak;              //Az exampleData által kapott tomből generált IntezmenySzak objektum. Konstruktorban inicializálódik
    private Tobbletpont $_tobbletpontok;                //A Többletpontok defivícióit tartalmazza. Konstruktor kapja meg.
    private TantargyRepository $_tantargyrepo;          //Az exampledataban lévő tantárgyakat tartalmazza. tantargyakBetoltese() inicializálja
    private TantargyRepository $_kotelezotantargyak;    //Általánosan kötelező éretségi tantárgyak. Intézménytől és Szaktól függetlenül
    private Tantargy $_kotelezo;                        //Adott szak-hoz tatozó kötelező tantárgy. Követelményekből kapjuk meg
    private string $_errormessage;                      //Bármilyen ok miatt is ha nem számolható felvételi pont , ebben tároljuk

    function __construct(array $exampleData, TantargyRepository $kotelezotantargyak, KovetelmenyRepository $kovetelmenyek, Tobbletpont $tobbletpontok)
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
        $this->_kovetelmeny = $kovetelmenyek->searchByIntezmenySzak($this->_intezmenyszak);
    }

    /**
     * @return integer 
     * Visszatérési érték: kalkulált pont.
     * Alap pont kiszámítására szolgáló függvény. Ha 0-át ad vissza, akkor mindenképpen van errormessage
     */

    public function calculate(): int
    {
        $eredmeny = 0;
        $kotelezo = 0;
        $valaszthato = 0;

        $this->tantargyakBetoltese();
        $this->checkKotelezotantargyak();


        if ($this->_errormessage == "") {
            $kotelezo = $this->getKotelezoPont();
            $valaszthato = $this->getValaszthatoMax();
            $eredmeny = ($kotelezo + $valaszthato) * 2;
        }

        /**
         * Itt vizsgáljuk hogy volt-e közben hiba. pl.:ha a kötelező tantárgynak nem megfelelő az értettségi szintje
         */
        if ($this->_errormessage != "") {
            $eredmeny = 0;
        }

        return $eredmeny;
    }

    /**
     * @return Bool
     * Visszatérési érték: Igaz ha minden vizsga 20% felett teljesített. Feltölti a $_tantargyrepo változót.
     */
    public function tantargyakBetoltese(): bool
    {
        $eredmeny = true;
        $pontszamalatt = [];
        foreach ($this->_eretsegi as $tantargy) {
            $t = new Tantargy($tantargy['nev'], $tantargy['tipus'], rtrim($tantargy['eredmeny'], '%'));
            if ($tantargy['nev'] == $this->_kovetelmeny->getKotelezo()) {
                $this->_kotelezo = $t;
            }
            if (rtrim($tantargy['eredmeny'], '%') < 20) {
                $pontszamalatt[] = $tantargy['nev'];
                $eredmeny = false;
            }
            $this->_tantargyrepo->add($t);
        }
        if ($eredmeny == false) {
            $this->_errormessage = "hiba, nem lehetséges a pontszámítás a " . implode(", ", $pontszamalatt) . " tárgy(ak)ból elért 20% alatti eredmény miatt";
        }

        return $eredmeny;
    }

    /**
     * @return integer
     * A kötelezően választható tantárgyak közül a maximálisnak az értékét adja vissza.
     */
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

    /**
     * @return integer
     * A Kötelező tantárgyhoz tartozó pontot adja vissza. Ellenőrzi a szintjét is.
     */
    public function getKotelezoPont(): int
    {
        $eredmeny = 0;
        if ($tantargy = $this->_tantargyrepo->searchNev($this->_kovetelmeny->getKotelezo())) {
            if ($this->_kovetelmeny->getKotelezoszint() == null || $tantargy->getTipus() == $this->_kovetelmeny->getKotelezoszint()) {
                $eredmeny = $tantargy->getEredmeny();
            } else {
                $this->_errormessage = "hiba , a kötelező tantárgy vizsgaztató szintje nem megfelelő";
            }
        }
        return $eredmeny;
    }

    /**
     * @return integer
     * A többletpontok kiszámolását végzi
     */
    public function getTobbletpont(): int
    {
        $tobbletpont = 0;
        $emeltszint = $this->_kotelezo->getTipus() == 'emelt' ? $this->_tobbletpontok->getEmeltszint() : 0;
        foreach ($this->_tobblet as $nyelvizsga) {
            $tobbletpont += $this->_tobbletpontok->getNyelvizsgak()->searchPont($nyelvizsga['tipus']);
        }
        return $emeltszint + $tobbletpont;
    }

    /**
     * @return String
     * Hibaüzenet lekérése
     */
    public function getErrormessage(): string
    {
        return $this->_errormessage;
    }

    /**
     * @return Bool
     * Ellenőrzi a kötelező tantárgyakból történt-e vizsga
     */
    private function checkKotelezotantargyak(): bool
    {
        $eredmeny = true;
        foreach ($this->_kotelezotantargyak->getTantargyak() as $tantargy) {
            if ($this->_tantargyrepo->searchNev($tantargy->getNev()) == false) {
                $eredmeny = false;
                $this->_errormessage = "hiba, nem lehetséges a pontszámítás a kötelező érettségi tárgyak hiánya miatt";
            }
        }
        return $eredmeny;
    }
}
