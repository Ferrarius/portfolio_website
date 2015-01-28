<?php
session_start();

$db = new PDO('mysql:host=rdbms.strato.de;dbname=DB1582306', 'U1582306', 'p30pl3p30pl3');

if($_SESSION['idGroep'] == 2)
{
    if(!empty($_GET['id']))
    {
        $verwijderBericht = $db->prepare
            ("
            DELETE FROM Berichten
            WHERE idBerichten = :id
            ");

        $verwijderBericht->bindParam(":id", $_GET['id']);
        $verwijderBericht->execute();

        header("location: bericht_overzicht.php");
    }
}
else
{
    header("location: index.php");
}


/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 19-2-14
 * Time: 19:46
 */ 