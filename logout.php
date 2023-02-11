<?php
	require_once 'include/config.php';
	unset($_SESSION['cams_logged_in']);
	unset($_SESSION['user']);
	header('location: '.BASE_URL);