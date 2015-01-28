<?php
session_start();

include_once("include/class.TemplatePower.inc.php");

$template = new TemplatePower("template/login.html");
$template->prepare();

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

if(isset($_SESSION['idGebruiker']))
{
    $template->newBlock("SESSIONERROR");
}
else
{
    if(isset($_POST['login'])
        AND !empty($_POST['gebruikersnaam'])
        AND !empty($_POST['wachtwoord']))
    {
        $check = $db->prepare("
        SELECT count(*) FROM Gebruikers
        WHERE gebruikersnaam = :gebruikersnaam
        AND wachtwoord = :wachtwoord");

        $check->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
        $wachtwoord = sha1($_POST['wachtwoord']);
        $check->bindParam(":wachtwoord", $wachtwoord);
        $check->execute();

        if($check->fetchColumn() == 1)
        {
            $getGegevens = $db->prepare("
            SELECT * FROM Gebruikers
            WHERE gebruikersnaam = :gebruikersnaam
            AND wachtwoord = :wachtwoord");

            $getGegevens->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
            $wachtwoord = sha1($_POST['wachtwoord']);
            $getGegevens->bindParam(":wachtwoord", $wachtwoord);
            $getGegevens->execute();

            $info = $getGegevens->fetch(PDO::FETCH_ASSOC);

            $_SESSION['gebruikersnaam'] = $info['gebruikersnaam'];
            $_SESSION['idGroep'] = $info['Groep_idGroep'];

            $template->newBlock("LOGINSUCCES");

            $template->newBlock("SESSION");
            $template->assign(array(
                "GEBRUIKERSNAAM" => $_SESSION['gebruikersnaam']));
        }
        else
        {
            if(isset($_POST['login']))
            {
                $template->newBlock("LOGINERROR");
            }
        }
    }
    else
    {
        if(isset($_POST['login']))
        {
            $template->newBlock("LOGINERROR2");
        }
    }
}

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
 * Date: 27-1-14
 * Time: 9:50
 */

?>