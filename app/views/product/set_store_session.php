<?php
session_start();
if (isset($_POST['store'])) {
    $_SESSION['SelectedStore'] = intval($_POST['store']);
}