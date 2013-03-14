<?php
/*
    Document   : LoginOps.php
    Created on : Nov 6, 2010
    Author     : Wilson Kwok
*/
  class LoginOps {
  // single instance of self shared among all instances
    private static $instance = null;

    //This method must be static, and must return an instance of the object if the object
    //does not already exist.
    public static function getInstance() {
        if (!self::$instance instanceof self) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    // The clone and wakeup methods prevents external instantiation of copies of the Singleton class,
    // thus eliminating the possibility of duplicate objects.
    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Deserializing is not allowed.', E_USER_ERROR);
    }

    public function retrieveUserCredentials($username, $password) {
        require_once("Includes/db.php");
        $userAccount = BookDB::getInstance()->retrieveUserCredentials($username, $password);
        return $userAccount;
    }

    public function displayLoginForm($_SESSION, $verifyCredentialError, $loginError, $userNameFormatError, $usernameError, $passwordError, $emailExistError) {
        if ($verifyCredentialError) {
            echo '<div id="login-error">';
            echo '<br/>The e-mail address and password you entered do not match any accounts on record. Please make sure that you have correctly entered the e-mail address associated with your FlashBooks.com account.<br/><br/>';
            echo '</div>';
        }
        if ($loginError) {
            echo '<div id="login-error">';
            echo '<br/>Field name(s) in red are required.<br/><br/>';
            echo '</div>';
        }
        if ($userNameFormatError) {
            echo '<div id="login-error">';
            echo '<br/>Please provide a valid email address.<br/><br/>';
            echo '</div>';
        }
        if ($emailExistError) {
            echo '<div id="login-error">';
            echo '<br/>The email address you provided is already registered with FlashBooks. Please use another email address.<br/><br/>';
            echo '</div>';
        }
        echo '<form name="loginForm" action="Login.php" method="POST" onclick="setCookie()";>';
        echo '<table class="login-table">';
        echo '<tr>';
        if ($usernameError || $userNameFormatError)
            echo '<td id="login-field-error">Enter your e-mail address:</td>';
        else
            echo '<td id="login-table-left-column">Enter your e-mail address:</td>';
        echo '<td><input type="text" name="username" size="40" value="' .$_SESSION["username"]. '"/></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td id="login-table-left-column" ><input type="radio" name="userRadio" value="newUser"/></td>';
        echo '<td>I am a new customer.</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td></td>';
        echo '<td id="login-instruction">(You\'ll create a password in a moment)</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td id="login-table-left-column" ><input type="radio" name="userRadio" value="oldUser" CHECKED/></td>';
        echo '<td>I am a returning customer.</td>';
        echo '</tr>';
        if ($passwordError && !$userNameFormatError)
            echo '<td id="login-field-error">Password:</td>';
        else
            echo '<td id="login-table-left-column">Password:</td>';
        echo '<td><input type="password" name="password" size="25"/></td>';
        echo '</tr>';
        echo '<tr>';       
        echo '<td></td>';
        echo '<td><input id="login-button" type="submit" value="Sign in >>"/></td>';
        echo '</tr>';
        echo '</table></form>';
    }
  }
?>