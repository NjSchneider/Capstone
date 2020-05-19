<?php

    try{
        $pdo = new PDO('mysql:host=localhost;dbname=game_store', 'root', '');
    } 
    catch(PDOException $e){
        exit('Database error.');
    }