<?php
session_start();

$_SESSION['gebruikersnaam'] = array();

session_unset();

header("location: index.php");

/**
 * Created by PhpStorm.
 * User: Laurens
 * Date: 29-1-14
 * Time: 14:41
 */

?>