<?php

/**
 * @file tokenLogin.php
 * The script handles the client request to login with the data of the currently
 * logged in user. If the user is not logged in, a redirect to UserLogin is performed,
 * where the user logs in and returns here.
 */
session_name("UserLogin");        
session_start();

if (empty($_SESSION['user_name'])) {
	header('Location: '.dirname(dirname($_SERVER['SCRIPT_NAME'])).'/index.php?'.$_SERVER['QUERY_STRING']);
} else {

	header('Location: '.dirname($_SERVER['SCRIPT_NAME']).'/request-auth-code.php?response_type=code&client_id='.$_GET['client_id'].'&state=xyz&scope='.$_GET['scope'].'&user_id='.$_SESSION['user_name'].'&redirect_uri='.$_GET['redirect_uri']);
}
