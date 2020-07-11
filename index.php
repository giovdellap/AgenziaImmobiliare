<?php

require_once "autoload.php";
require_once 'StartSmarty.php';
require_once 'confDB.conf.php';
require_once 'Installation.php';
require_once 'DBInstaller.php';

$GLOBALS["path"] = "/AgenziaImmobiliare/";

if(Installation::verificaInstallazione())
{
    $fc = new CFrontController();
    $fc->main();
}
else Installation::installationStart();


