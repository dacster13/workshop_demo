<?php 
session_start();
if (empty($_SESSION['workshop'])) {
    header('Location: index.php');
}