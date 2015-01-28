<?php
session_start();

include_once("include/class.TemplatePower.inc.php");

$template = new TemplatePower("template/admin_panel.html");
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

if($_SESSION['idGroep'] == 2)
{
    $template->printToScreen();
}
else
{
    header("location: index.php");
}

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 13-2-14
 * Time: 23:44
 */ 