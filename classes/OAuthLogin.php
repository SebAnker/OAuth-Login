<?php

use GuzzleHttp\Client;

/**
 * @class OAuthLogin
 *
 * @brief Handles user's login and logout process 
 * @author Sebastian Ankerhold
 * 
 */
class OAuthLogin
{
	/**
	 * @var object Handles database requests
	 */	
	private $database = null;	
	  
	/**
     * @var array Collection of error messages
     */  
	public $errors = array();
   
	/**
     * @var array Collection of success / neutral messages
     */ 
    public $messages = array();
   
	/**
	 * Constructor
	 * Checks and performs the possible login actions
	 */
    public function __construct($user_name = null)
    {
		if(isset($_GET['error'])) {
			$this->errors[] = $_GET('error_description');
		} else {
			session_name("UserLogin");        
			session_start();

			$this->database = new Database();

			$this->checkLoginOptions($user_name);
		}
    }


	
	/**
	 * Checks the GET and POST data and performs the corresponding actions.
	 * 
	 * @param string $user_name The name of a user
	 */
	private function checkLoginOptions($user_name = null)
	{
		if(isset($user_name)) {
			$this->doLoginWithRegistrationData($user_name);

			//if this login is part of an oauth request save the current query
			if(isset($_POST['oauth'])) {
				$_SESSION['oauth'] = $_POST['oauth'];
			}
			//request authorization code
			$this->getAuthCode();	
		}

		//if an authorization code was send, exchange it for an access token
		elseif (isset($_GET["code"])) {
            $this->getAccessToken();

			//if this login is part of an oauthal request goto the corresponding API
			if(isset($_SESSION['oauth'])) {
				header('Location: '.API_ADRESS.'/request-token.php?'.$_SESSION['oauth']);
				$_SESSION['oauth'] = null;
				exit;
			}
        }
		//if user tried to logout perform logout
        elseif (isset($_GET["logout"])) {
            $this->doLogout();
        }
		
		//login via post data
        elseif (isset($_POST["login"])) {
            $this->dologinWithPostData();
			if(empty($this->errors)) {
				//if this login is part of an oauthal request save the current query
				if(isset($_POST['oauth'])) {
				$_SESSION['oauth'] = $_POST['oauth'];
				}
				//request authorization code
				$this->getAuthCode();
			}	
        }
	}

    /**
     * Log in with post data by checking the validity of posted data
	 * and store essential data into PHP SESSION
     */
    private function dologinWithPostData()
    {		
        // check login form contents
        if (empty($_POST['user_name'])) {
            $this->errors[] = "Username field was empty.";
        } elseif (empty($_POST['user_password'])) {
            $this->errors[] = "Password field was empty.";
        } elseif (!empty($_POST['user_name']) && !empty($_POST['user_password'])) {

        	$user_name = $_POST['user_name'];

			//check if user is registered
        	if ($this->database->userExists($user_name)) {
				$loginData = $this->database->getLoginData($user_name);

				//using PHP 5.5's password_verify() function to check password 
				if (password_verify($_POST['user_password'], $loginData->user_password_hash)) {
					$_SESSION['user_name'] = $loginData->user_name; 
					$_SESSION['user_id'] = $loginData->user_id;
					$_SESSION['user_login_status'] = 1;

				} else {
                    $this->errors[] = "Wrong password. Try again.";
                }
			} else {
            	$this->errors[] = "This user does not exist.";
            }
		}
    }

	/**
	 * If a user registered a new account, this function uses the entered data
	 * to log in the user

	 * @param 
	 */
	private function doLoginWithRegistrationData($user_name)
	{
		$loginData = $this->database->getLoginData($user_name);

		$_SESSION['user_name'] = $loginData->user_name; 
		$_SESSION['user_id'] = $loginData->user_id;
		$_SESSION['user_login_status'] = 1;
	}	
	
    /**
     * Performs the logout by resetting the $_SESSION array and
	 * displays a logout message
     */
    public function doLogout()
    {
        // delete the session of the user
        $_SESSION = array();
        session_destroy();
        // return a little feeedback message
        $this->messages[] = "You have been logged out.";
    }

	
    /**
     * Simply return the current state of the user's login
     * @return boolean user's login status
     */
    public function isUserLoggedIn()
    {
        if (isset($_SESSION['user_login_status']) AND $_SESSION['user_login_status'] == 1) {
            return true;
        }
        return false;
    }

	/**
	 * Redirects to the authorization endpoint to request an authorization code
	 * If successful the user will be redirected back to this location with a code
	 */
	private function getAuthCode()
	{
		$clientid = 'MainsiteLogin';
		//get the scopes according to user_type	
		$scope = $this->database->getScope($_SESSION['user_name']);

		header('Location: '.API_ADRESS.'/request-token.php?response_type=code&client_id='.$clientid.'&state=xyz&scope='.$scope.'&user_id='.$_SESSION['user_name']);
		exit;
	}

	/**
	 * Request an access token by sending the received auth.code
	 * to the token endpoint.
	 * If successful the token will be stored in SESSION
	 */
	private function getAccessToken()
	{
		$client = new Client();	
		
		$response = $client->post(API_ADRESS.'/access-token.php', [
    		'body' => [
        		'client_id' => 'MainsiteLogin',
        		'client_secret' => 'password',
				'grant_type' => 'authorization_code',
				'code' => $_GET['code'],
				'redirect_uri'=>LOGIN_ADRESS.'/index.php'
    		]
		]); 
		$array = $response->json();	

		$_SESSION['access_token'] = $array['access_token'];
		$_SESSION['scope'] = $array['scope'];
	}

}
