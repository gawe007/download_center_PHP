<?php
require_once("app.php");

$req = null;
if(isset($_GET['r']) && !empty($_GET['r'])){
    $req = $_GET['r'];
}

$app = new app($req);

$app->render();