<?php
//database_connection.php

$connect = new PDO('mysql:host=localhost;dbname=ecwace', 'root', '');
// $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
session_start();

?>