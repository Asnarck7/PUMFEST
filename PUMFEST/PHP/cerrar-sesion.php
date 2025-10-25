<?php
session_start();
session_unset();
session_destroy();
header("Location: ../PHP/index.php"); // o ../index.php según tu estructura
exit;
?>
