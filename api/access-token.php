<?php

/**
 * @file token.php
 * This is the token endpoint, which uses the configured Grant Types   
 * to return an access token to the client.
 */

require_once __DIR__.'/server.php';

// Handle a request for an OAuth2.0 Access Token and send the response to the client
$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();
