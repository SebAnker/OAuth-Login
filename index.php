<?php

/*!
The index file of UserLogin.
It loads all necessary libraries and displays sites 
according to the login status. If the user is logged in
he/she will be redirected to the own user page.
*/

define("BASE_URI", 'http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));

require_once __DIR__.'/libraries/constants.php';
require_once(LIBRARY_PATH.'/autoload.php');
require_once CLASSES_PATH.'/OAuthLogin.php';
require_once CLASSES_PATH.'/Database.php';

if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    require_once(LIBRARY_PATH."/password_compatibility_library.php");
}

if(isset($_GET['register'])) {
	require CLASSES_PATH."/Registration.php";
	$registration = new Registration();

	include(VIEW_PATH."/register.php");

} elseif(isset($_GET['user'])) {
	include(VIEW_PATH.'/profile.php');

} else {	
	$login = new OAuthLogin();

	if ($login->isUserLoggedIn() == true) {	
		header('Location: '.LOGIN_ADRESS.'?user='.$_SESSION['user_name']);
	} else {
    	include(VIEW_PATH."/not_logged_in.php");
	}
}
