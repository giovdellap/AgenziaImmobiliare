<?php


class FCliente extends FUtente
{
    protected static string $table = "cliente";
    protected static string $idString = "CL";


    /**
     * @return string
     */
    public static function getTable(): string
    {
        return self::$table;
    }

    /**
     * @return string
     */
    public static function getID():string
    {
        return self::$idString;
    }


}