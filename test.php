<?php
require_once("bin/config/config.php");
echo password_hash("1234", PASSWORD_BCRYPT);
session_start();
var_dump($_SESSION);
echo UPLOAD_DIR;
//session_destroy();