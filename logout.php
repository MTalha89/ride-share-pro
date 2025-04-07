<?php
session_start();
session_destroy();
header("Location: /ride-sharing-app/login.php");
exit;
?>