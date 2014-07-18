<?php

/**
 * @file authorize.php
 * 
 * This is the authorization endpoint, a user is redirected here
 * if a client requests authorization. The user has the option to
 * grant authorization and let the server give out an auth. code, or
 * deny authorization.
 */

require_once __DIR__.'/server.php';

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

//if the query contains a user id, get in the end a token which is associated with that user
isset($_GET['user_id']) ? $userid = $_GET['user_id'] : $userid = null;

//if the user is redirected here during a normal UserLogin skip the dialog
if( isset($_GET['client_id']) && ($_GET['client_id'] == 'MainsiteLogin')) {
	$_POST['authorized'] = 'yes';
}

//using the authorization controller, validate the request
if (!$server->validateAuthorizeRequest($request, $response)) {
	$res = json_decode($response->getResponseBody());
	$uri = $_GET['redirect_uri'];
	if(strpos($uri,'?') === false) {
		$uri .= '?error='.$res->error.'&error_description='.$res->error_description;
	} else {
		$uri .= '&error='.$res->error.'&error_description='.$res->error_description;
	}
	header('Location: '.$uri);
}

//dialog that shows which scopes the client is requesting
//option to deny or grant them
if (empty($_POST)) {
	echo 'The client '. $_GET['client_id'] . ' requests the following rights:';
	foreach(explode(' ', $_GET['scope']) AS $scope) {
   		echo '<p>   '.$scope."</p>";
	}
  exit('
<form method="post">
  <label>Do you authorize the client?</label><br />
  <input type="submit" name="authorized" value="yes">
  <input type="submit" name="authorized" value="no">
</form>');
}

//if user authorized the client, an auth. code is given out
$is_authorized = ($_POST['authorized'] === 'yes');
$server->handleAuthorizeRequest($request, $response, $is_authorized, $userid);
$response->send();

