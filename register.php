<?php
session_start();

include_once("include/class.TemplatePower.inc.php");

$template = new TemplatePower("template/register.html");
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

if(isset($_POST['registreren'])
    AND !empty($_POST['gebruikersnaam'])
    AND !empty($_POST['wachtwoord'])
    AND !empty($_POST['voornaam'])
    AND !empty($_POST['achternaam'])
    AND !empty($_POST['geslacht'])
    AND !empty($_POST['geboortedatum'])
    AND !empty($_POST['email']))
{
    $selectGebruikers = $db->prepare("
    SELECT count(*) AS gevonden FROM Gebruikers WHERE gebruikersnaam = :gebruikersnaam");
    $selectGebruikers->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
    $selectGebruikers->execute();

    $gebruikers = $selectGebruikers->fetch(PDO::FETCH_ASSOC);

    if($gebruikers[gevonden] == 0)
    {
        $insertGebruiker = $db->prepare("
        INSERT INTO Gebruikers SET
        gebruikersnaam = :gebruikersnaam,
        wachtwoord = :wachtwoord,
        voornaam = :voornaam,
        tussenvoegsel = :tussenvoegsel,
        achternaam = :achternaam,
        geslacht = :geslacht,
        geboortedatum = :geboortedatum,
        email = :email
        ");

        $insertGebruiker->bindParam(":gebruikersnaam", $_POST['gebruikersnaam']);
        $wachtwoord = sha1($_POST['wachtwoord']);
        $insertGebruiker->bindParam(":wachtwoord", $wachtwoord);
        $insertGebruiker->bindParam(":voornaam", $_POST['voornaam']);
        $insertGebruiker->bindParam(":tussenvoegsel", $_POST['tussenvoegsel']);
        $insertGebruiker->bindParam(":achternaam", $_POST['achternaam']);
        $insertGebruiker->bindParam(":geslacht", $_POST['geslacht']);
        $insertGebruiker->bindParam(":geboortedatum", $_POST['geboortedatum']);
        $insertGebruiker->bindParam(":email", $_POST['email']);
        $insertGebruiker->execute();

        $get_user = $db->prepare
            ("
            SELECT * FROM Gebruikers
            ORDER BY idGebruikers
            DESC LIMIT 1
            ");
        $get_user->execute();

        $gebruiker = $get_user->fetch(PDO::FETCH_ASSOC);

        $template->newBlock("GEBRUIKER");
        $template->assign(array(
            "VOORNAAM" => $gebruiker['voornaam'],
            "GEBRUIKERSNAAM" => $gebruiker['gebruikersnaam'],
            "EMAIL" => $gebruiker['email']));
        header( "refresh:5;url=index.php" );
    }
    else
    {
        $template->newBlock("REGERROR2");
    }
}
else
{
    if(isset($_POST['registreren']))
    {
        $template->newBlock("REGERROR");
    }
}

$template->printToScreen();

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 21-1-14
 * Time: 12:39
 */

?>