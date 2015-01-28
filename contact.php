<?php
session_start();

include_once("include/class.TemplatePower.inc.php");

$template = new TemplatePower("template/contact.html");
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

if(isset($_POST['verzenden'])
    AND !empty($_POST['naam'])
    AND !empty($_POST['email'])
    AND !empty($_POST['onderwerp'])
    AND !empty($_POST['bericht']))
{
    $query = $db->prepare
        ("
        INSERT INTO Berichten SET
        naam = :naam,
        email = :email,
        onderwerp = :onderwerp,
        bericht = :bericht
        ");

    $query->bindParam(":naam", $_POST['naam']);
    $query->bindParam(":email", $_POST['email']);
    $query->bindParam(":onderwerp", $_POST['onderwerp']);
    $query->bindParam(":bericht", $_POST['bericht']);
    $query->execute();

    $template->newBlock("SENDSUCCES");
}
else
{
    if(isset($_POST['registreren']))
    {
        $template->newBlock("SENDERROR");
    }
}

$template->printToScreen();

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 27-1-14
 * Time: 9:49
 */

?>