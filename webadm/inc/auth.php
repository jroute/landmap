<?php
session_start();

if( $_SESSION['webadm'] != 'true' ){
	header('location: login.php');
}