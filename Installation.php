<?php
require_once "DBInstaller.php";

class Installation
{
    static function installationStart(){

        if (VReceiverProxy::getRequest()){
            // viene inviato un cookie per verificare se questi sono abilitati
            setcookie('verifica', 'verifica',time()+3600);
            VSmartyFactory::basicSmarty()->display('installazione.tpl');
        }
        else {

            $smarty = VSmartyFactory::basicSmarty();
            // controllo versione PHP
            if (version_compare(PHP_VERSION,'7.4.0' , '<' ))
                VSmartyFactory::errorSmarty($smarty, 'PHP')->display('installazione.tpl');
            // controllo cookie
            if (!isset($_COOKIE['verifica']))
                VSmartyFactory::errorSmarty($smarty, 'Cookie')->display('installazione.tpl');
            // verifica JS
            if (!isset($_COOKIE['checkjs']))
                VSmartyFactory::errorSmarty($smarty, 'JS')->display('installazione.tpl');
            else {
                setcookie('verifica', '',time()-3600);
                setcookie('checkjs', '',time()-3600);
                // si procede con l'installazione e il popolamento del db
                DBInstaller::installDB();

                $admin = FAmministratore::getAmministratore();
                $admin->setPassword(VReceiverProxy::getPasswordAdmin());
                FAmministratore::modificaAmministratore($admin);

                header ('Location: '.$GLOBALS['path']);
            }

        }
    }

    /**
     * Funzione che verifica la presenza del cookie di installazione; quindi se l'installazione è stata effettuata
     */
    public static function verificaInstallazione(){
        $verifica = false;
        if(file_exists('confDB.conf.php'))
            $verifica = true;
        return $verifica;
    }

}