<?php

/**
 * This site is a rudimentary user profile, showing all the data of a user.
 * If a profile visited by a user with admin rights, that user can grant/revoke
 * admin rights.
 */

use GuzzleHttp\Client;

session_name("UserLogin");
session_start();

$page_owner = $_GET['user'];
$current_user = $_SESSION['user_name'];

$client = new Client();
$response = $client->post(API_ADRESS.'/validate-token.php', [
    'body' => ['access_token' => $_SESSION['access_token']]]);
$tokenData = $response->json();


if($tokenData['success']) {

	$database = new Database();

	if(isset($_POST['grantAdmin'])) {
		if($database->changeEntry($database->getUserId($page_owner), 'user_type', 'admin')) {
			echo 'Granted administration rights';
		} 
		
	} elseif(isset($_POST['revokeAdmin'])) {
		if($database->changeEntry($database->getUserId($page_owner), 'user_type', 'standard')) {
			echo 'Admin rights revoked';
		} 
	}

	$page_owner_data = $database->getUserDataName($page_owner);

	echo "<h2>".$page_owner."'s Profile</h2>";
	echo '<p> Hello '.$current_user.'</p>';

	echo '<table width="300" border="0" cellpadding="3" cellspacing="1"> ';
	foreach ($page_owner_data as $key => $value) {
		if($key != 'user_password_hash') {
			echo '<tr>';
				echo '<td>'.$key.'</td>';
				echo '<td>'.$value.'</td>';
			echo '</tr>';
		}
	}
	echo '</table>';

	echo '<form method="post" name="admin_rights">';
	if(($current_user != $page_owner) && ($database->getUserType($current_user) == 'admin')) {
		if($page_owner_data->user_type == 'admin') {
			echo '<input type="submit"  name="revokeAdmin" value="Revoke Admin rights" />';
		} else {
			echo '<input type="submit"  name="grantAdmin" value="Grant Admin rights" />';
		}
		echo '<p><a href="'.LOGIN_ADRESS.'/?user='.$current_user.'">Back to own profile</a></p>';
	}	
	echo '</form>';

	echo '<p><a href="'.LOGIN_ADRESS.'/?logout">Logout</a></p>';

} else {
	echo 'No valid token';
}

