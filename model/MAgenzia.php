<?php


class MAgenzia
{
    private string $nome;
    private string $citta;
    private string $cap;
    private string $provincia;
    private string $indirizzo;
    private array $immagini;

    private array       $list_Clienti;
    private array       $list_AgentiImmobiliari;
    private array       $list_Immobili;
    private MCalendario $calendario;

    public function __construct()
    {
        $this->list_AgentiImmobiliari = array();
        $this->list_Clienti = array();
        $this->list_Immobili = array();
        $this->calendario = new MCalendario();
        $this->immagini = array();
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getCitta(): string
    {
        return $this->citta;
    }

    /**
     * @param string $citta
     */
    public function setCitta(string $citta): void
    {
        $this->citta = $citta;
    }

    /**
     * @return string
     */
    public function getCap(): string
    {
        return $this->cap;
    }

    /**
     * @param string $cap
     */
    public function setCap(string $cap): void
    {
        $this->cap = $cap;
    }

    /**
     * @return string
     */
    public function getProvincia(): string
    {
        return $this->provincia;
    }

    /**
     * @param string $provincia
     */
    public function setProvincia(string $provincia): void
    {
        $this->provincia = $provincia;
    }

    /**
     * @return string
     */
    public function getIndirizzo(): string
    {
        return $this->indirizzo;
    }

    /**
     * @param string $indirizzo
     */
    public function setIndirizzo(string $indirizzo): void
    {
        $this->indirizzo = $indirizzo;
    }



    /**
     * @return mixed
     */
    public function getCalendario(): MCalendario
    {
        return $this->calendario;
    }

    /**
     * @param mixed $calendario
     */
    public function setCalendario(MCalendario $calendario)
    {
        $this->calendario = $calendario;
    }

    /**
     * Aggiunge il Cliente alla lista clienti
     * @param MCliente $Cliente
     * @return bool esito dell'operazione
     */
    public function addCliente(MCliente $Cliente):bool{

        if(!in_array($Cliente, $this ->list_Clienti) ) {
            $this->list_Clienti[] = $Cliente;
            return true;
        }
        else return false;
    }

    /**
     * Elimina il Cliente dalla lista Clienti
     * @param MCliente $Cliente
     */
    public function removeCliente(MCliente $Cliente):void{

        if(in_array($Cliente, $this ->list_Clienti) )
        {
            unset($this->list_Clienti[array_search($Cliente, $this->list_Clienti)]);
        }
    }

    /**
     * Aggiunge l'Agente Immobiliare alla lista degli Agenti Immobiliari
     * @param MAgenteImmobiliare $AgenteImmobiliare
     * @return bool esito dell'operazione
     */
    public function addAgenteImmobiliare(MAgenteImmobiliare $AgenteImmobiliare):bool{

        if(!in_array($AgenteImmobiliare, $this ->list_AgentiImmobiliari) )
        {
            $this->list_AgentiImmobiliari[] = $AgenteImmobiliare;
            return true;
        }
        else return false;

    }

    /**
     * elimina l'Agente Immobiliare dalla lista degli Agenti Immobiliari
     * @param MAgenteImmobiliare $AgenteImmobiliare
     */
    public function removeAgenteImmobiliare(MAgenteImmobiliare $AgenteImmobiliare):void{

        if(in_array($AgenteImmobiliare, $this ->list_AgentiImmobiliari) )
        {
            unset($this->list_Clienti[array_search($AgenteImmobiliare, $this->list_AgentiImmobiliari)]);
        }

    }

    /**
     * Aggiunge l'Immobile alla lista degli immobili
     * @param MImmobile $Immobile
     * @return bool esito dell'operazione
     */
    public function addImmobile(MImmobile $Immobile):bool{
        if(!in_array($Immobile, $this ->list_Immobili) )
        {
            $this->list_Clienti[] = $Immobile;
            return true;
        }

        else return false;

    }

    /**
     * Elimina l'Immobile dalla lista degli Immobili
     * @param MImmobile $Immobile
     */
    public function removeImmobile(MImmobile $Immobile):void{

        if(in_array($Immobile, $this ->list_Immobilii))
        {
            unset($this->list_Clienti[array_search($Immobile, $this->list_Immobili)]);
        }

    }

    /**
     * Ritorna un array di Appuntamenti disponibili compresi fra $dataInizio e $dataFine
     * @param MCliente $cliente
     * @param MImmobile $immobile
     * @param MData $orarioinizio
     * @param MData $orariofine
     * @return array
     */
    public function checkDisponibilità(MCliente $cliente, MImmobile $immobile, MData $orarioinizio, MData $orariofine): array
    {
        $toReturn = array();
        $toCycleInizio = clone $orarioinizio;
        $toCycleFine = clone $orarioinizio;
        $toCycleFine->incrementoOrario(30);

        while ($toCycleInizio->getOrario() <= $orariofine->getOrario()) {

            $toAdd = new MAppuntamento(-1);
            foreach ($this->list_AgentiImmobiliari as &$agenti)
            {
                $appDisp = new MAppuntamento(0000);
                $inizio = clone $toCycleInizio;
                $fine = clone $toCycleFine;
                $appDisp->setAppuntamento($inizio, $fine, $cliente, $immobile, $agenti);

                $context = new MValidatorContext($appDisp);
                $valido = $context->validateAppuntamento(new MValidatorImmobile());
                if ($valido)
                    $valido = $context->validateAppuntamento(new MValidatorAgenteImmobiliare());
                if ($valido)
                    $valido = $context->validateAppuntamento(new MValidatorCliente());
                if ($valido) {
                    if ($toAdd->getId() != -1) {
                        if (sizeof($agenti->getListAppuntamenti()) < sizeof($toAdd->getAgenteImmobiliare()->getListAppuntamenti()))
                            $toAdd = $appDisp;
                    }
                    else $toAdd = $appDisp;
                }

            }
            if($toAdd->getId() != -1)
                $toReturn[] = $toAdd;
            $toCycleInizio->incrementoOrario(15);
            $toCycleFine->incrementoOrario(15);

        }
        return $toReturn;
    }

    /**
     * @return array
     */
    public function getImmagini(): array
    {
        return $this->immagini;
    }

    /**
     * @param array $immagini
     */
    public function setImmagini(array $immagini): void
    {
        $this->immagini = $immagini;
    }

    /**
     * Aggiunta di un oggetto MMedia all'array immagini
     * @param MMedia $immagine
     */
    public function addImmagine(MMedia $immagine): void
    {
        $this->immagini[] = $immagine;
    }
}