<?php
session_start();

include_once("include/class.TemplatePower.inc.php");

$template = new TemplatePower("template/gebruikers_panel.html");
$template->prepare();

if(isset($_SESSION['gebruikersnaam']))
{
    $template->newBlock("SESSION");
    $template->assign(array(
        "GEBRUIKERSNAAM" => $_SESSION['gebruikersnaam']));
}
else
{
    $template->newBlock("LOGINREG");
}

try{
    $db = new PDO('mysql:host=rdbms.strato.de;dbname=DB1582306', 'U1582306', 'p30pl3p30pl3');
    $db->setAttribute(PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $error)
{
    $template->newBlock("SQLERROR");
    $template->assign(array(
        "GETLINE" => $error->getLine(),
        "GETFILE" => $error->getFile(),
        "GETMESSAGE" => $error->getMessage()
    ));
}


$template->printToScreen();

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 26-2-14
 * Time: 18:17
 */

?>