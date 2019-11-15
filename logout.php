<?php
if (isset($_SESSION['workshop'])) {
    unset($_SESSION['workshop']);
    session_destroy();
}
header('Location: index.php');