<?php
  error_reporting(E_ALL ^ E_DEPRECATED);

  $host  = "localhost";
  $user  = "root";
  $senha = "";
  $base  = "blog";

  $conn = mysqli_connect($host, $user, $senha, $base);
  session_start();
?>
