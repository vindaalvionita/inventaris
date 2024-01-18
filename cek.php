<?php
    //Jika belum melakukan login
    if(isset($_SESSION['log'])){

    } else {
        header('location:login.php');
    }
?>