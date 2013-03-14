<?php
/*
    Document   : Registration.php
    Created on : Oct 28, 2010
    Author     : Ning Jiang
*/
session_start(); //open session
$url = htmlspecialchars($_SERVER['HTTP_REFERER']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Check full name input
    if ($_POST["fullName"] == "")
        $fullNameError = true; // check name input
    else {
        $_SESSION["fullName"] = $_POST["fullName"];
        $fullNameError = false;
    }    
    if ($_POST["username"] == "")
        $usernameError = true;
    else {
        $_SESSION["username"] = $_POST["username"];
        $usernameError = false;
    }
    //Check password input
    if ($_POST["password"] == "")
        $passwordError = true; // check if pw input is null
    else {
        $_SESSION["password"] = $_POST["password"];
        $passwordError = false;
    }

    if (strstr($_POST["username"], '@') == false && $_POST["username"] <> "")
        $userNameFormatError = true;
    else if ($fullNameError || $usernameError || $passwordError) {
        $registrationError = true;
    }
    else {
        require_once("Includes/RegistrationOps.php");
        $userAccount = RegistrationOps::getInstance()->verifyUsername($_POST["username"]);
        $row = @mysql_fetch_array($userAccount); // compare useraccount
        if ($row['USER_NM'] == $_POST["username"])
            $usernameError = true;
        else {
            RegistrationOps::getInstance()->createNewUserAccount($_POST["username"], $_POST["fullName"], $_POST["password"]);
            $_SESSION["userLogin"] = $_POST["username"];
            if (($_SESSION["refererPage"] == 'ShoppingCart.php') ||
                ($_SESSION["refererPage"] == 'SearchResults.php') ||
                ($_SESSION["refererPage"] == 'BookDetail.php'))
                header('Location: Address.php');
            else
                header('Location:'.$_SESSION["refererPage"]);
        }
    }
}
?>
<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/Registration.css" type="text/css" rel="stylesheet" media="all"/>
    </head>
    <body>
        <div class="registration-mainbody">
            <?php
            $url = htmlspecialchars('Login.php');
            echo "<br/><a href='".$url."'><font size='2em'><u><< Back</u></font></a>";
            if (($_SESSION["refererPage"] == 'ShoppingCart.php') ||
                ($_SESSION["refererPage"] == 'SearchResults.php') ||
                ($_SESSION["refererPage"] == 'BookDetail.php'))
                echo '<div class="checkout-status"><br/><br/></div>';
            ?>
            <form name="registrationForm" action="<?php echo $_SERVER[PHP_SELF]; ?>" method="POST">
                <?php
                require_once("Includes/RegistrationOps.php");
                RegistrationOps::getInstance()->displayRegistrationForm($_SESSION,
                                                                        $registrationError,
                                                                        $usernameError,
                                                                        $userNameFormatError,
                                                                        $fullNameError,
                                                                        $passwordError);
                ?>
            </form>
         </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>