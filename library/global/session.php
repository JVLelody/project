<?php
    session_start();

    if(!( isset($_SESSION["login"]) && $_SESSION["login"] == "OK")){
      header("Location: ../index.php");
      exit;
    }
?>
