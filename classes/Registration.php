<?php

use GuzzleHttp\Client;

/**
 * @class Registration
 * @brief Handles the user registration
 * @author Sebastian Ankerhold
 */
class Registration

{
	/**
	 * @var object handles database requests
	 */
	private $database = null;	
	
	/**
     * @var array $errors Collection of error messages
     */
	public $errors = array();
   
	/**
     * @var array $messages Collection of success / neutral messages
     */
    public $messages = array();

   
	/**
	 * Constructor
	 */
    public function __construct()
    {   
		$this->database = new Database();	
	     
		if (isset($_POST["register"])) {
            $this->registerNewUser();
        }
    }

    /**
     * Handles the entire registration process. Checks all error possibilities
     * ,creates a new user in the database and performs a login for the new user 
	 * if everything is fine.
     */
    private function registerNewUser()
    {	
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Empty Username";
        } elseif (empty($_POST['user_password_new']) || empty($_POST['user_password_repeat'])) {
            $this->errors[] = "Empty Password";
        } elseif ($_POST['user_password_new'] !== $_POST['user_password_repeat']) {
            $this->errors[] = "Password and password repeat are not the same";
        } elseif (strlen($_POST['user_password_new']) < 5) {
            $this->errors[] = "Password has a minimum length of 5 characters";
        } elseif (strlen($_POST['user_name']) > 64 || strlen($_POST['user_name']) < 2) {
            $this->errors[] = "Username cannot be shorter than 2 or longer than 64 characters";
        } elseif (!preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])) {
            $this->errors[] = "Username does not fit the name scheme: only a-Z and numbers are allowed, 2 to 64 characters";
        } elseif (!empty($_POST['user_name'])
            && strlen($_POST['user_name']) <= 64
            && strlen($_POST['user_name']) >= 2
            && preg_match('/^[a-z\d]{2,64}$/i', $_POST['user_name'])          
            && !empty($_POST['user_password_new'])
            && !empty($_POST['user_password_repeat'])
            && ($_POST['user_password_new'] === $_POST['user_password_repeat'])) 
		{
			$user_name = $_POST['user_name'];
            $user_password = $_POST['user_password_new'];
            $user_password_hash = password_hash($user_password, PASSWORD_DEFAULT);		
			$foaf_uri = $_POST['foaf_uri'];
			
            if ($this->database->userExists($user_name)) {
            	$this->errors[] = "Sorry, that username is already taken.";

            } else {

				if ($this->database->addUser($user_name, $user_password_hash, $foaf_uri)) {
					//login with newly created account
					$login = new OAuthLogin($user_name);

                } else {
                    $this->errors[] = "Sorry, your registration failed. Please go back and try again.";
                }
            }

        } else {
            $this->errors[] = "An unknown error occurred.";
        }
    }
}
