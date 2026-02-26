<?php 

session_start();
include "include/koneksi.php";

$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM mobil WHERE id ='$id'");

header("Location: mobil.php");
?>

