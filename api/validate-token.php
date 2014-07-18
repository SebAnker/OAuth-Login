<?php

/**
 * @file resource.php
 * This is a resource endpoint, it will validate incoming recource requests
 * and sends back a success message and detailed information about the token
 */


require_once __DIR__.'/server.php';

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

isset($_POST['scope'])? $scopeRequired = $_POST['scope'] :$scopeRequired = null;

// Handle a request for an OAuth2.0 Access Token and send the response to the client
if (!$server->verifyResourceRequest($request, $response, $scopeRequired)) {
	echo json_encode(array('success' => false));
    die;
}

echo json_encode(array('success' => true));
