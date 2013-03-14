<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
  class ShippingMethodOps {
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

    public function retrieveShippingMethods() {
        require_once("Includes/db.php");
        $shippingMethod = BookDB::getInstance()->retrieveShippingMethods();
        return $shippingMethod;
    }
    public function displayShippingMethods($result) {
        echo '<div class="checkout-status"><br/><br/></div>';
        echo '<div class="ship-method-prompt">Choose your shipping options<br/></div>';
        echo '<br/>';
        echo '<table><tr><th class="ship-method-header">';
        echo 'Choose a shipping method:';
        echo '</th></tr></table>';

        while ($row = mysql_fetch_array($result)) {
            $carrier = $row["CARRIER"];
            $shipMethodDesc = $row['SHIP_METHOD_DESC'];
            $shipCost = $row['COST'];
            echo '<form name="chooseShippingMethod" action="Payment.php" onClick="setCookie(\''.$carrier.'\', \''.$shipMethodDesc.'\', \''.$shipCost.'\', 50);">';
            echo '<table><tr><td class="ship-method-column-body-one">';
            echo '<input class="ship-method-button" name="useThisShipMehtod" type="submit" value="Use this shipping method"/>';
            echo '</td><td class="ship-method-column-body-two">';
            echo '$'.$shipCost;
            echo '</td><td class="ship-method-column-body-three">';
            echo $carrier.' - '.$shipMethodDesc;
            echo '</td></tr></table>';
            echo '</form>';
        }
    }
  }
?>
