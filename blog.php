<?php
session_start();
include_once("include/class.TemplatePower.inc.php");
$template = new TemplatePower("template/blog.html");
$template->prepare();

if(isset($_SESSION['Groep_idGroep']))
{
    if($_SESSION['Groep_idGroep'] == 2)
    {
        header("location: project_admin.php");
    }
}
else
{
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

    $getBlog = $db->prepare("SELECT * FROM Blog ORDER BY datum DESC");
    $getBlog->execute();
    $blogs = $getBlog->fetch(PDO::FETCH_ASSOC);

    while($blogArtikel = $getBlog->fetch(PDO::FETCH_ASSOC))
    {
        $template->newBlock("BLOG");
        $template->assign(array(
            "DATUM"=>$blogArtikel['datum'],
            "TITEL"=>$blogArtikel['titel'],
            "INHOUD"=>$blogArtikel['inhoud']
            ));
    }
    if(empty($blogs['titel']));
    {
        $template->newBlock("LEEG");
        $template->assign("GEENBLOGS", 'Er zijn nog geen blogs gemaakt.');
    }
}

$template->printToScreen();

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 27-1-14
 * Time: 9:33
 */

?>