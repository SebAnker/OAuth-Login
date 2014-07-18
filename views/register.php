<?php

/**
 * If the user tried to register a new account with OAuthLogin, 
 * this site will be displayed.
 * It contains a simple register form with user name, password and 
 * repeat password as well as the option save the uri to a foaf file.
 */

if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo $error;
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo $message;
        }
    }
}
?>

<h2>Register</h2>

<form method="post" action="index.php?register" name="registerform">

<table width="500" border="0" cellpadding="3" cellspacing="1"> 

    <tr>
    	<td><label for="login_input_username">Username (only letters and numbers, 2 to 64 characters)</label></td>
    	<td><input id="login_input_username" class="login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required autocomplete="off"/></td>
    </tr><tr>
    	<td><label for="login_input_password_new">Password (min. 5 characters)</label></td>
    	<td><input id="login_input_password_new" class="login_input" type="password" name="user_password_new" pattern=".{5,}" required autocomplete="off" /></td>
	</tr><tr>
    	<td><label for="login_input_password_repeat">Repeat password</label></td>
    	<td><input id="login_input_password_repeat" class="login_input" type="password" name="user_password_repeat" pattern=".{5,}" required autocomplete="off" /></td>
	</tr><tr>
    	<td><label for="login_input_uri">Your FOAF profile URI (optional)</label></td>
    	<td><input id="login_input_uri" class="login_input" type="text" name="foaf_uri" autocomplete="off" /></td>
	</tr>
</table>
	<p>If you already have a FOAF profile enter your URI here if you don't have one, just leave it blank.</p>

    <input type="submit"  name="register" value="Register" />
	<?php
	if(isset($_POST['oauth'])) {
		echo '<input type="hidden"  name="oauth" value="'.$_POST['oauth'].'" />';
	}?>
</form>

<a href="index.php">Back to Login Page</a>
