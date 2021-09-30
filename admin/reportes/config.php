<?php

try {
    $pdo = new PDO('musql:host=localhost;dbname=helpdesk ' ,"root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (\Exception $e) {
   $e->getMessage();
}