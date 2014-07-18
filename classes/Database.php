<?php

/**
 * @class Database
 * @brief Contains functions to simplify database requests
 * @author Sebastian Ankerhold
 */
class Database
{
	/**
     * @var object The database connection
     */
	private $db_connection = null;
	
	/**
	 * Constructor
	 * creates/checks database connection
	 */
	public function __construct()
    {
		$this->db_connection = new mysqli(AUTH_HOST, AUTH_USER, AUTH_PASS, AUTH_NAME);
		if (!$this->db_connection->set_charset("utf8")) {
                echo $this->db_connection->error;
        }
		if ($this->db_connection->connect_errno) {
				echo 'Database connection problem';
		}
	}

	/**
	 * Returns all available data of the corresponding user name
	 *	 
	 * @param string $user_name The name of a user
	 * @return all available user data
	 */
	public function getUserDataName($user_name)
	{
		$user_name = $this->db_connection->real_escape_string($user_name);
		$sql = "SELECT * FROM oauth_users WHERE user_name = '" . $user_name. "';";
		$data = $this->db_connection->query($sql);
		
		return $data->fetch_object();
	}

	/**
	 * Returns all available data of the corresponding user id
	 *	 
	 * @param int $user_id The ID of a user
	 * @return all available user data
	 */
	public function getUserDataId($user_id)
	{
		$sql = "SELECT * FROM oauth_users WHERE user_id = '" . $user_id. "';";
		$data = $this->db_connection->query($sql);
		
		return $data->fetch_object();
	}

	/**
	 * Returns data necessary for a login
	 *	 
	 * @param string $user_name The name of a user
	 * @return login relevant data
	 */
	public function getLoginData($user_name)
	{
		$user_name = $this->db_connection->real_escape_string($user_name);
		$sql = "SELECT user_name, user_password_hash, user_id, foaf_uri FROM oauth_users WHERE user_name = '" . $user_name. "';";
		$data = $this->db_connection->query($sql);

		return $data->fetch_object();
	}
	
	/**
	 * Checks if a user with a given name is already registered
	 *	 
	 * @param string $user_name The name of a user
	 * @return true if user name already exists, otherwise false 
	 */
	public function userExists($user_name)
	{
		$user_name = $this->db_connection->real_escape_string($user_name);		

		$sql = "SELECT * FROM oauth_users WHERE user_name = '" . $user_name . "';";
		$data = $this->db_connection->query($sql);

		return ($data->num_rows == 1);
	}

	/**
	 * Adds a new user and password to the database
	 *	 
	 * @param string $user_name The name of a user
	 * @param string $user_password_hash The password for that user
	 * @return true if adding was successful, else false
	 */
	public function addUser($user_name, $user_password_hash, $foaf_uri = null)
	{
		$sql = "INSERT INTO oauth_users (user_name, user_password_hash, foaf_uri)
                VALUES('" . $user_name . "', '" . $user_password_hash . "', '" . $foaf_uri . "');";
		return $this->db_connection->query($sql);
	}

	/**
	 * Returns the user id of a given user name
	 *	 
	 * @param string $user_name The name of a user
	 * @return The ID of that user
	 */
	public function getUserId($user_name) 
	{
		$data = $this->getLoginData($user_name);
		return $data->user_id;
	}

	/**
	 * Returns the user name of a given user id
	 *	 
	 * @param int $user_id The Id of a user
	 * @return The name of that user
	 */
	public function getUserName($user_id) 
	{
		$data = $this->getUserDataId($user_id);
		return $data->user_name;
	}

	/**
	 * Returns the user type of a given user name
	 *	 
	 * @param string $user_name The name of a user
	 * @return The type of that user
	 */
	public function getUserType($user_name) 
	{
		$data = $this->getUserDataName($user_name);
		return $data->user_type;
	}

	/**
	 * Changes an entry of a given user to a new value
	 *	 
	 * @param int $user_name The name of a user
	 * @param string $key The column that needs changing
	 * @param string $value The new value of that column
	 * @return true if change was successful, false else
	 */
	public function changeEntry($user_id, $key, $value)
	{
		$sql = "UPDATE oauth_users SET ".$key."='".$value."'WHERE user_id=".$user_id.";";
		return $this->db_connection->query($sql);
	}

	/**
	 * Checks the type a given user and returns string of scopes 
	 *
	 * @param string $user_name
	 * @return a string which contains all the scopes the user was permitted
	 */
	public function getScope($user_name)
	{
		$type = $this->getUserType($user_name);
		if($type == 'admin') {
			return 'admin';
		}
	}
}
