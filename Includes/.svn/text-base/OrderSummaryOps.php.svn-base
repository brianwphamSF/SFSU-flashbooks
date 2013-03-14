<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
  class OrderSummaryOps {
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

    public function displayShippingAddress($_SESSION) {
        if ($_SESSION["shipToNewAddress"]) {
            $fullName = $_SESSION["fullName"];
            $addressLine1 = $_SESSION["addressLine1"];
            $addressLine2 = $_SESSION["addressLine2"];
            $city = $_SESSION["city"];
            $state = $_SESSION["state"];
            $zip = $_SESSION["zip"];
        }
        else {
            $fullName = $_COOKIE["fullName"];
            $addressLine1 = $_COOKIE["addressLine1"];
            $addressLine2 = $_COOKIE["addressLine2"];
            $city = $_COOKIE["city"];
            $state = $_COOKIE["state"];
            $zip = $_COOKIE["zip"];
        }

        echo '<tr><td class="review-order-table-header">';
        echo 'Shipping to:';
        echo '</td></tr>';
        echo '<tr><td class="review-order-table-body">';
        echo $fullName.'<br/>'.$addressLine1.'<br>';
        if ($addressLine2 <> "")
            echo $addressLine2.'<br/>';
        echo $city.',&nbsp;'.$state.'&nbsp;'.$zip;
        echo '</td></tr>';
    }

    public function displayShippingMethod() {
        echo '<tr></tr><tr><td class="review-order-table-header">';
        echo 'Shipping Speed:';
        echo '</td></tr>';
        echo '<tr><td class="review-order-table-body">';
        echo $_COOKIE["carrier"].' - '.$_COOKIE["shipMethodDesc"];
        echo '</td></tr>';
    }

    public function displayPaymentMethod($_SESSION) {
        if ($_SESSION["payWithNewCard"]) {
            $cardLastFourDigit = substr($_SESSION["cardNumber"], 12);
            $cardType = $_SESSION["cardType"];
            $expMonth = $_SESSION["expMonth"];
            $expYear = $_SESSION["expYear"];
        }
        else {
            $cardLastFourDigit = $_COOKIE["cardLastFourDigit"];
            $cardType = $_COOKIE["cardType"];
            $expMonth = $_COOKIE["expMonth"];
            $expYear = $_COOKIE["expYear"];
        }
        echo '<tr></tr><tr><td class="review-order-table-header">';
        echo 'Payment Method:';
        echo '</td></tr>';
        echo '<tr><td class="review-order-table-body">';
        echo $cardType.'Card ending in '.$cardLastFourDigit;
        echo '<br/>Expiration date: '.$expMonth.'/'.$expYear;
        echo '</td></tr>';
    }

    public function displayOrderSummary($itemsTotalResult) {
        $row = @mysql_fetch_array($itemsTotalResult);
        $itemsTotal = $row['SUBTOTAL'];
        $subTotal = $itemsTotal + $_COOKIE["shipCost"];
        $tax = $subTotal * (0.095);
        $total = $subTotal + $tax;
        echo '<tr></tr><tr><td class="review-order-table-header" colspan=2>Order Summary:</td></tr>';
        echo '<tr><td class="review-order-table-body">Item:</td>';
        echo '<td class="review-order-table-body-align-right">$'.number_format($itemsTotal,2).'</td></tr>';
        echo '<tr><td class="review-order-table-body">Shipping & Handling:</td>';
        echo '<td class="review-order-table-body-align-right">$'.number_format($_COOKIE["shipCost"],2).'</td></tr>';
        echo '<tr><td class="review-order-table-body">Total Before Tax:</td>';
        echo '<td class="review-order-table-body-align-right">$'.number_format($subTotal,2).'</td></tr>';
        echo '<tr><td class="review-order-table-body">Estimated Tax:</td>';
        echo '<td class="review-order-table-body-align-right">$'.number_format($tax,2).'</td></tr>';
        echo '<tr><td colspan=2><hr></td></tr>';
        echo '<tr><td class="review-order-table-body">Order Total:</td>';
        echo '<td class="review-order-table-body-align-right">$'.number_format($total,2).'</tr>';
    }
  }
?>