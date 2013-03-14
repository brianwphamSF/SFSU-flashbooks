<?php
/*
    Document   : RegistrationOps.php
    Created on : Oct 28, 2010
    Author     : Ning Jiang
*/
  class RegistrationOps {
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

    public function verifyUsername($username) {
        require_once("Includes/db.php");
        $userAccount = BookDB::getInstance()->verifyUsername($username);
        return $userAccount;
    }

    public function createNewUserAccount($username, $fullName, $password) {
        require_once("Includes/db.php");
        BookDB::getInstance()->createNewUserAccount($username, $fullName, $password);
    }

    public function displayRegistrationForm($_SESSION, $registrationError, $usernameError, $userNameFormatError, $fullNameError, $passwordError) {
        echo '<div class="registration-prompt">Please register</div>';
        echo '<div class="registration-instruction1">New to FlashBooks? Register Below.</div>';
        echo '<div class="registration-instruction2">* Information required</div><br/>';

        if ($registrationError) {
            echo '<div class="registration-error">';
            echo 'Field name(s) in red are required<br/><br/>';
            echo '</div>';
        }
        if ($usernameError) {
            echo '<div class="registration-error">';
            echo 'The email address you entered is already registered with FlashBooks. Please enter another email address.<br/><br/>';
            echo '</div>';
        }
        if ($userNameFormatError) {
            echo '<div class="registration-error">';
            echo '<br/>Please provide a valid email address.<br/><br/>';
            echo '</div>';
        }

        echo '<table>';
        echo '<tr>';
        if ($fullNameError)
            echo '<td class="registration-field-error">*My name is:</td>';
        else
            echo '<td class="registration-body">*My name is:</td>';
        echo '<td><input type="text" name="fullName" size="40" value="' .$_SESSION["fullName"]. '"/></td>';
        echo '</tr>';
        echo '<tr>';
        if ($usernameError || $userNameFormatError)
            echo '<td class="registration-field-error">*My e-mail address:</td>';
        else
            echo '<td class="registration-body">*My e-mail address:</td>';
        echo '<td><input type="text" name="username" size="40" value="' .$_SESSION["username"]. '"/></td>';
        echo '</tr></table><br/>';

        echo '<div class="registration-instruction1">Protect your information with a password.</div>';
        echo '<table>';
        if ($passwordError)
            echo '<td class="registration-field-error">*Enter a new password:</td>';
        else
            echo '<td class="registration-body">*Enter a new password:</td>';
        echo '<td><input type="password" name="password" size="30"/></td>';
        echo '</tr>';
        echo '<tr><td></td>';
        echo '<td><input class="registration-button" type="submit" value="Continue >>"/><br></td>';
        echo '</tr></table>';
   
    }
  }
?>