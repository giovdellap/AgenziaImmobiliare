<?php


class FAgenteImmobiliare extends FUtente
{
    private static string $table = "agente_immobiliare";
    private static string $idString = "AG";

    /**
     * Ritorna un array con tutti gli agenti immobiliari presenti nel DB
     * Ogni agente ha la propria lista degli appuntamenti completa
     * @return array
     */
    public static function getAllAgenti($inizio,$fine): ?array
    {
        $db = FDataBase::getInstance();
        $db_result = $db->loadAll(self::class);
        $agenti = array();
        foreach ($db_result as &$item)
            $agenti[] = self::AppUtenteInBetween($db_result["id"], $inizio,$fine);
        return $agenti;
    }

}