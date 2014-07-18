<?php

/**
 * 
 * If the user is not logged in with OAuthLogin, this site will be displayed.
 * The site contains a simple login form with username and password 
 * and a link to the registration site,
 */

// show potential errors / feedback (from login object)
if (isset($login)) {
    if ($login->errors) {
        foreach ($login->errors as $error) {
            echo $error;
        }
    }
    if ($login->messages) {
        foreach ($login->messages as $message) {
            echo $message;
        }
    }
}

echo '<h2>OAuth-Login</h2>';

?>

<!-- login form box -->
<form method="post" action="index.php" name="loginform">

<table width="300" border="0" cellpadding="3" cellspacing="1"> 
	<tr>
    	<td><label for="login_input_username">Username</label></td>
    	<td><input id="login_input_username" class="login_input" type="text" name="user_name" autocomplete="off" required /></td>
	</tr>
	<tr>
    	<td><label for="login_input_password">Password</label></td>
    	<td><input id="login_input_password" class="login_input" type="password" name="user_password" autocomplete="off" required /></td>
	</tr>
</table>
    <input type="submit"  name="login" value="Log in" />
	<?php
	if(isset($_GET['client_id'])) {
		echo '<input type="hidden"  name="oauth" value="'.$_SERVER['QUERY_STRING'].'" />';
	}?>
</form>

<form method="post" action="index.php?register" name="register">
	<input type="submit"  name="registration" value="Register new account" />
	<?php
	if(isset($_GET['client_id'])) {
		echo '<input type="hidden"  name="oauth" value="'.$_SERVER['QUERY_STRING'].'" />';
	}?>
</form>


