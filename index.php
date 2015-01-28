<?php
session_start();

include_once("include/class.TemplatePower.inc.php");

$template = new TemplatePower("template/index.html");
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

$template->printToScreen();

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 22-1-14
 * Time: 11:21
 */

?>