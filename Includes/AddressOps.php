<?php
/*
    Document   : AddressOps.php
    Created on : Oct 12, 2010
    Author     : Wilson Kwok
*/
  class AddressOps {
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

    //This function retrieve the address info by username
    public function retrieveAddressInfoByUser($username) {
        require_once("Includes/db.php");
        $address = BookDB::getInstance()->retrieveAddressInfoByUser($username);
        return $address;
    }

    public function addUserAddress($username, $fullName, $addressLine1, $addressLine2, $city, $state, $zip) { 
        require_once("Includes/db.php");
        BookDB::getInstance()->addUserAddress($username, $fullName, $addressLine1, $addressLine2, $city, $state, $zip);
    }

    //This function display a list of existing addresses that are associated with the user account
    public function displayExisitngAddress($address) {
        echo '<br/><table><tr><th class="exist-ship-address-header">Address book</th></tr></table>';

        while ($row = mysql_fetch_array($address)) {
            $fullName = $row['FULL_NM'];
            $addressLine1 = $row['ADDR_LINE_1'];
            $addressLine2 = $row['ADDR_LINE_2'];
            $city = $row['CITY'];
            $state = $row['STATE'];
            $zip = $row['ZIP'];
            echo '<form name="chooseAddress'.$count.'" action="ShippingMethod.php" onClick="setCookie(\''.$fullName.'\', \''.$addressLine1.'\', \''.$addressLine2.'\', \''.$city.'\', \''.$state.'\', \''.$zip.'\', 1);">';
            echo '<table><tr><td class="exist-ship-address-body-column-one">';
            echo '<input class="exist-ship-to-this-address-button" name="shipToExistAddress" type="submit" value="Ship to this Address"/>';
            echo '</td><td class="exist-ship-address-body-column-two">';
            echo $fullName. ',&nbsp;&nbsp;' .$addressLine1. ', ';
            if ($addressLine2 <> "")
                echo $addressLine2. ', ';
            echo $city. ', ' .$state. ' ' .$zip.'<br/>';
            echo '</td></tr></table>';
            echo '</form>';
        }
        echo '<br/>';
        echo '<div class="separator"><hr></div>';
    }

    //This function displays the address form for users to enter a new shipping address
    public function displayNewAddressForm($_SESSION, $address, $newAddressError, $fullNameError, $addressLine1Error, $cityError, $stateError, $zipError) {
        echo '<form name="newAddressForm" action="'.$_SERVER[PHP_SELF].'" method="POST">';
        if (!is_null($address))
            echo '<div class="ship-address-prompt">Or enter a new shipping address</div>';
        echo '<div class="ship-address-instruction">*<I> Information required</I></div><br/>';
        if ($newAddressError) {
            echo '<div class="new-address-error">';
            echo 'Field name(s) in red are required<br/><br/>';
            echo '</div>';
        }
        echo '<table>';
        echo '<tr>';
        if ($fullNameError)
            echo '<td class="new-address-field-error">*Full Name:</td>';
        else
            echo '<td class="new-ship-address-body">*Full Name:</td>';
        echo '<td><input type="text" name="fullName" size="40" value="' .$_SESSION["fullName"]. '"/></td>';
        echo '</tr>';
        echo '<tr>';
        if ($addressLine1Error)
            echo '<td class="new-address-field-error">*Address Line 1:</td>';
        else
            echo '<td class="new-ship-address-body">*Address Line 1:</td>';
        echo '<td><input type="text" name="addressLine1" size="40" value="' .$_SESSION["addressLine1"]. '"/></td>';
        echo '</tr>';
        echo '<tr><td><font size="1">Street address, P.O. Box, company name</font></td></tr>';
        echo '<tr>';
        echo '<td class="new-ship-address-body">Address Line 2:</td>';
        echo '<td><input type="text" name="addressLine2" size="40" value="' .$_SESSION["addressLine2"]. '"/></td>';
        echo '</tr>';
        echo '<tr><td><font size="1">Apartment, suite, unit, building, floor</font></td></tr>';
        echo '<tr>';
        if ($cityError)
            echo '<td class="new-address-field-error">*City:</td>';
        else
            echo '<td class="new-ship-address-body">*City:</td>';
        echo '<td><input type="text" name="city" size="30" value="' .$_SESSION["city"]. '"/></td>';
        echo '</tr>';
        echo '<tr>';
        if ($stateError)
            echo '<td class="new-address-field-error">*State:</td>';
        else
            echo '<td class="new-ship-address-body">*State:</td>';
        echo '<td><input type="text" name="state" size="25" value="' .$_SESSION["state"]. '"/></td>';
        echo '</tr>';
        echo '<tr>';
        if ($zipError)
            echo '<td class="new-address-field-error">*Zip:</td>';
        else
            echo '<td class="new-ship-address-body">*Zip:</td>';
        echo '<td><input type="text" name="zip" size="30" value="' .$_SESSION["zip"]. '"/></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td></td>';
        echo '<td><input class="new-ship-to-this-address-button" name="shipToNewAddress" type="submit" value="Ship to this address"/><br></td>';
        echo '</tr></table>';
        echo '</form>';
    }
  }
?>