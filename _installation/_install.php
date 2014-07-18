<?php

define("BASE_URI", 'http://'.$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['SCRIPT_NAME'])));
require dirname(__DIR__).'/libraries/constants.php';


//Tables
$db_connection = new mysqli(AUTH_HOST, AUTH_USER, AUTH_PASS, AUTH_NAME);

$sql = "CREATE TABLE oauth_clients (
			client_id VARCHAR(80) NOT NULL, 
			client_secret VARCHAR(80) NOT NULL, 
			redirect_uri VARCHAR(2000) NOT NULL, 
			grant_types VARCHAR(80), scope VARCHAR(100), 
			user_id VARCHAR(80) COLLATE utf8_unicode_ci, 
			CONSTRAINT client_id_pk PRIMARY KEY (client_id));";
if($db_connection->query($sql)) {
	echo "oauth_clients created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "CREATE TABLE oauth_access_tokens (
			access_token VARCHAR(40) NOT NULL, 
			client_id VARCHAR(80) NOT NULL, 
			user_id VARCHAR(255), 
			expires TIMESTAMP NOT NULL, 
			scope VARCHAR(2000), 
			CONSTRAINT access_token_pk PRIMARY KEY (access_token));";
if($db_connection->query($sql)) {
	echo "oauth_access_tokens created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "CREATE TABLE oauth_authorization_codes (
			authorization_code VARCHAR(40) NOT NULL, 
			client_id VARCHAR(80) NOT NULL, 
			user_id VARCHAR(255), 
			redirect_uri VARCHAR(2000), 
			expires TIMESTAMP NOT NULL, 
			scope VARCHAR(2000), 
			CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code));";
if($db_connection->query($sql)) {
	echo "oauth_authorization_codes created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "CREATE TABLE oauth_refresh_tokens (
			refresh_token VARCHAR(40) NOT NULL, 
			client_id VARCHAR(80) NOT NULL, 
			user_id VARCHAR(255), expires TIMESTAMP NOT NULL, 
			scope VARCHAR(2000), 
			CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token));";
if($db_connection->query($sql)) {
	echo "oauth_refresh_tokens created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "CREATE TABLE oauth_users (
			user_id int(11) NOT NULL AUTO_INCREMENT, 
			user_name VARCHAR(255) COLLATE utf8_unicode_ci NOT NULL, 
			user_password_hash VARCHAR(2000) COLLATE utf8_unicode_ci, 
			user_type VARCHAR(64) DEFAULT 'standard', 
			foaf_uri VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT NULL,
			CONSTRAINT user_id_pk PRIMARY KEY (user_id), 
			UNIQUE KEY user_name (`user_name`));";
if($db_connection->query($sql)) {
	echo "oauth_users created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "CREATE TABLE oauth_scopes (
			scope TEXT, 
			is_default BOOLEAN);";
if($db_connection->query($sql)) {
	echo "oauth_scopes created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "CREATE TABLE oauth_jwt (
			client_id VARCHAR(80) NOT NULL, 
			subject VARCHAR(80), public_key VARCHAR(2000), 
			CONSTRAINT client_id_pk PRIMARY KEY (client_id));";
if($db_connection->query($sql)) {
	echo "oauth_jwt created created<br>";
} else {
	echo $db_connection->error;
	die;
}

echo "Tables created\n";

$sql = "INSERT INTO oauth_clients (client_id, client_secret, redirect_uri, grant_types, scope, user_id) VALUES ('MainsiteLogin', 'password', '".LOGIN_ADRESS."/index.php', NULL, 'admin', NULL);";
if($db_connection->query($sql)) {
	echo "main client created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "INSERT INTO oauth_clients (client_id, client_secret, redirect_uri, grant_types, scope, user_id) VALUES ('Xodx', 'password', '".XODX_ADRESS."/?c=oauth', NULL, 'xodx', NULL);";
if($db_connection->query($sql)) {
	echo "xodx client created created<br>";
} else {
	echo $db_connection->error;
	die;
}

$sql = "INSERT INTO oauth_users (user_id, user_name, user_password_hash, user_type) VALUES (1, 'admin', '$2y$10$0NkApjCun9lBISNU.Sz/cOdAUl40pXaYIWt8hJXkler0hRxzNNIk2', 'admin');";
if($db_connection->query($sql)) {
	echo "first admin created<br>";
} else {
	echo $db_connection->error;
	die;
}


echo "Installation finished";
