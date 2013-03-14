<?php
/*
    Document   : Login.php
    Created on : Nov 6, 2010
    Author     : Wilson Kwok
*/
session_start();
$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if ($_GET["signOut"] == 'yes') {
        session_destroy();
        session_start();
        $_SESSION["sessionId"] = session_regenerate_id();
        header ('Location: index.php');
    }
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_GET["header"] == 'yes')
        $_SESSION["refererPage"] = 'index.php';
    //Check username input
    if ($_POST["username"] == "") {
        $usernameError = true;
    }
    else {
        $_SESSION["username"] = $_POST["username"];
        $usernameError = false;
    }    
    //Check password input
    if ($_POST["password"] == "" && $_COOKIE["userRadio"] == "oldUser") {
        $passwordError = true;
    }
    else {
        $_SESSION["password"] = $_POST["password"];
        $passwordError = false;
    }
 
    if (strstr($_POST["username"], '@') == false && $_POST["username"] <> "")
        $userNameFormatError = true;
    else if ($usernameError || $passwordError) {
        $loginError = true;
    }
    else {
        require_once("Includes/LoginOps.php");
        $userAccount = LoginOps::getInstance()->retrieveUserCredentials($_POST["username"], $_POST["password"]);
        $row = @mysql_fetch_array($userAccount);
        if ($_COOKIE["userRadio"] == "oldUser") {
            if (($row['USER_NM'] == $_POST["username"]) && ($row['PASSWD'] == $_POST["password"])) {
                $_SESSION["userLogin"] = $_POST["username"];
                $fullName = $row['FULL_NM'];
	        $pos = stripos($fullName, ' ');
		$_SESSION["welcomeUser"] = substr($fullName, 0, $pos);
                if (($_SESSION["refererPage"] == 'ShoppingCart.php') ||
                    ($_SESSION["refererPage"] == 'SearchResults.php') ||
                    ($_SESSION["refererPage"] == 'BookDetail.php'))
                    header('Location: Address.php');
                else
                    header('Location:'.$_SESSION["refererPage"]);
            }
            else
                $verifyCredentialError = true;

        }
        else {
            if ($row['USER_NM'] == $_POST["username"])
                $emailExistError = true;
            else
                header('Location: Registration.php');
        }
    }
}
?>
<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/Login.css" type="text/css" rel="stylesheet" media="all"/>
        <script language="Javascript" type="text/javascript">
        function setCookie(expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);
            var userRadio;
            for (var i = 0; i < document.loginForm.userRadio.length; i++) {
                if (document.loginForm.userRadio[i].checked) {
                    userRadio = document.loginForm.userRadio[i].value;
                    document.cookie = 'userRadio' + "=" + escape(userRadio) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
                }
            }
        }
        </script>
    </head>
    <body>
        <div class="login-mainbody">
            <?php
            if ((($_SESSION["refererPage"] == 'ShoppingCart.php')||
                ($_SESSION["refererPage"] == 'SearchResults.php') ||
                ($_SESSION["refererPage"] == 'BookDetail.php')) && $_SESSION["checkOutButton"])
                echo '<div class="checkout-status"><br/><br/></div>';
            echo '<div id="login-prompt">Please sign in<br/><br/><br/></div>';
            require_once("Includes/LoginOps.php");
            LoginOps::getInstance()->displayLoginForm($_SESSION, 
                                                      $verifyCredentialError,
                                                      $loginError,
                                                      $userNameFormatError,
                                                      $usernameError,
                                                      $passwordError,
                                                      $emailExistError);
            ?>                         
         </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>