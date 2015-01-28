<?php
session_start();

switch($_SESSION['idGroep'])
{
    case 1:
        header("location: gebruikers_panel.php");
        break;
    case 2:
        header("location: admin_panel.php");
        break;
}

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 13-2-14
 * Time: 22:57
 */

?>