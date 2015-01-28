<?php
session_start();

include_once("include/class.TemplatePower.inc.php");

$template = new TemplatePower("template/bericht_overzicht.html");
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

if($_SESSION['idGroep'] == 2)
{
    $getBerichten = $db->prepare("SELECT * FROM Berichten ORDER BY datum DESC");
    $getBerichten->execute();

    while($rij = $getBerichten->fetch(PDO::FETCH_ASSOC))
    {
        $template->newBlock("BERICHTEN");
        $template->assign(array(
            "ID"=>$rij['idBerichten'],
            "DATUM"=>$rij['datum'],
            "NAAM"=>$rij['naam'],
            "EMAIL"=>$rij['email'],
            "ONDERWERP"=>$rij['onderwerp'],
            "BERICHT"=>$rij['bericht']
        ));
    }
}
else
{
    header("location: index.php");
}



$template->printToScreen();

?>