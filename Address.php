<?php
/*
    Document   : Address.php
    Created on : Oct 12, 2010
    Author     : Wilson Kwok
*/
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //If user decide to use existing address, record that in the session array
    //for later use in the Order Summary page. Otherwise, the user has entered
    //a new shipping address, and record that in the session array for later use
    //in the Order Summary page.
    if ($_POST["shipToExistAddress"])
        $_SESSION["shipToExistAddress"] = true;
    else
        $_SESSION["shipToNewAddress"] = true;

    $_SESSION["addressLine2"] = $_POST["addressLine2"];
    //Check full name input in the new shipping address form
    if ($_POST["fullName"] == "")
        $fullNameError = true;
    else {
        $_SESSION["fullName"] = $_POST["fullName"];
        $fullNameError = false;
    }
    //Check address line 1 input in the new shipping address form
    if ($_POST["addressLine1"] == "")
        $addressLine1Error = true;
    else {
        $_SESSION["addressLine1"] = $_POST["addressLine1"];
        $addressLine1Error = false;
    }
    //Check city input in the new shipping address form
    if ($_POST["city"] == "")
        $cityError = true;
    else {
        $_SESSION["city"] = $_POST["city"];
        $cityError = false;
    }
    //Check state input in the new shipping address form
    if ($_POST["state"] == "")
        $stateError = true;
    else {
        $_SESSION["state"] = $_POST["state"];
        $stateError = false;
    }
    //Check zip code input in the new shipping address form
    if ($_POST["zip"] == "")
        $zipError = true;
    else {
        $_SESSION["zip"] = $_POST["zip"];
        $zipError = false;
    }

    //If any of the error above is true, then the for is not filled out completely
    if ($fullNameError || $addressLine1Error || $cityError || $stateError || $zipError) {
        $newAddressError = true;
    }
    else {
        $newAddressError = false;
        require_once("Includes/AddressOps.php");
        AddressOps::getInstance()->addUserAddress($_SESSION["userLogin"],
                                                  $_SESSION["fullName"],
                                                  $_SESSION["addressLine1"],
                                                  $_SESSION["addressLine2"],
                                                  $_SESSION["city"],
                                                  $_SESSION["state"],
                                                  $_SESSION["zip"]);
        header('Location: ShippingMethod.php');
    }
}
?>
<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/Address.css" type="text/css" rel="stylesheet" media="all"/>
        <script language="Javascript" type="text/javascript">
        function setCookie (fullName, addressLine1, addressLine2, city, state, zip, expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);
            document.cookie = 'fullName' + "=" + escape(fullName) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'addressLine1' + "=" + escape(addressLine1) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'addressLine2' + "=" + escape(addressLine2) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'city' + "=" + escape(city) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'state' + "=" + escape(state) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'zip' + "=" + escape(zip) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
        }
        </script>
    </head>
    <body>
        <div class="ship-address-mainbody">
            <?php
            $url = 'ShoppingCart.php';
            echo "<br/><a href='".$url."'><font size='2em'><u><< Back</u></font></a>";
            ?>
            <div class="checkout-status"><br/><br/></div>
            <div class="ship-address-prompt">Select shipping address<br/></div>
            <div class="ship-address-instruction">Please select an address from your address book. Or you can enter a new shipping address.</div>
                <?php
                require_once("Includes/AddressOps.php");
                $address = AddressOps::getInstance()->retrieveAddressInfoByUser($_SESSION["userLogin"]);
                if (!is_null($address)) {
                    AddressOps::getInstance()->displayExisitngAddress($address);
                }
                else {
                    echo '<br><font class="ship-address-warning"><u>It seems like you do not have any address(es) for you account.</u></font><br/><br/>';
                    echo '<br><font class="ship-address-instruction">Please enter a new shipping address</font><br/>';
                }
                AddressOps::getInstance()->displayNewAddressForm($_SESSION,
                                                                 $address,
                                                                 $newAddressError,
                                                                 $fullNameError,
                                                                 $addressLine1Error,
                                                                 $cityError,
                                                                 $stateError,
                                                                 $zipError);
                ?>
         </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>