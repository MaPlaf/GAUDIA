<?php

    try{
        $db = new PDO('mysql:server=localhost;dbname=gaudia;charset=utf8', 'root', '');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $exception) {
            echo "ERROR: " . $exception->getMessage();
    }