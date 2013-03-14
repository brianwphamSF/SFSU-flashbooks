<?php
/*
    Document   : PaymentOps.php
    Created on : Oct 20, 2010
    Author     : Wilson Kwok
*/
  class PaymentOps {
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

    //This funtion revrieve the existing credit card associated with the user accounts
    public function retrieveCreditCardInfoByUser($username){
        require_once("Includes/db.php");
        $payment = BookDB::getInstance()->retrieveCreditCardInfoByUser($username);
        return $payment;
    }

    public function addUserCreditCardInfo ($username, $cardNumber, $cardType, $expMonth, $expYear, $secureCode, $nameOnCard) {
        require_once("Includes/db.php");
        BookDB::getInstance()->addUserCreditCardInfo($username, $cardType, $cardNumber, $expMonth, $expYear, $secureCode, $nameOnCard);
    }

    //This funtion displays the existing credit card associated with the user accounts
    public function displayExistingCreditCard($payment) {
        echo '<br/>';
        echo '<table><tr><th class="existing-card-column-header-one">';
        echo 'Your credit cards';
        echo '</th><th class="existing-card-column-header-two">';
        echo 'Expires on';
        echo '</th><th class="existing-card-column-header-three">';
        echo 'Name on card';
        echo '</th></tr></table>';

        while ($row = mysql_fetch_array($payment)) {
            $cardLastFourDigit = substr($row["CARD_NUM"], 12);
            $cardType = $row['CARD_TYPE'];
            $cardNumber = $row['CARD_NUM'];
            $expMonth = $row['EXPIRATION_MTH'];
            $expYear = $row['EXPIRATION_YR'];
            $nameOnCard = $row['NM_ON_CARD'];
            $secureCode = $row['SECURITY_CD'];
            echo '<form name="chooseCreditCard" action="OrderSummary.php" onClick="setCookie(\''.$cardNumber.'\', \''.$cardType.'\', \''.$cardLastFourDigit.'\', \''.$expMonth.'\', \''.$expYear.'\', \''.$nameOnCard.'\', \''.$secureCode.'\', 1);">';
            echo '<table><tr><td class="existing-card-column-body-one">';
            echo '<input class="exist-card-button" name="payWithExistCard" type="submit" value="Pay with this card"/>';
            echo '</td><td class="existing-card-column-body-two">';
            echo $cardType.'Card ending in '.$cardLastFourDigit;
            echo '</td><td class="existing-card-column-body-three">';
            echo $expMonth.'/'.$expYear;
            echo '</td><td class="existing-card-column-body-four">';
            echo $nameOnCard;
            echo '</td></tr></table>';
            echo '</form>';
        }
        echo '<br/>';
        echo '<div class="separator"><hr></div>';
        echo '<div class="payment-prompt">Or enter a new credit or debit card</div>';
        echo '<div class="payment-instruction">*<I> Information required</I></div><br/>';
    }

    //This funtion displays the new payment form for user to enter a new credit card for payment
    public function displayNewCreditCardForm($_SESSION, $newCardError, $cardNumberError, $nameOnCardError, $secureCodeError) {
        echo '<form name="addCreditCard" action="'.$_SERVER[PHP_SELF].'" method="POST">';
        if ($newCardError) {
            echo '<div class="new-card-error">';
            echo 'Field name(s) in red are required<br/><br/>';
            echo '</div>';
        }
        echo '<table class="new-card-table">';
        echo '<tr>';
        if ($cardNumberError)
            echo '<th class="new-card-field-error">*Card number</th>';
        else
            echo '<th class="new-card-header">*Card number</th>';
        if ($nameOnCardError)
            echo '<th class="new-card-field-error">*Name on card</th>';
        else
            echo '<th class="new-card-header">*Name on card</th>';
        if ($secureCodeError)
            echo '<th class="new-card-field-error">*Security Code</th>';
        else
            echo '<th class="new-card-header">*Security Code</th>';
        echo '<th class="new-card-header">*Expiration date</th>';
        echo '</tr><tr>';
        echo '<td><input type="text" style="border-color:#FDEEF4" name="cardNumber" value="'.$_SESSION["cardNumber"].'"/></td>';
        echo '<td><input type="text" style="border-color:#FDEEF4" name="nameOnCard" size="30" value="'.$_SESSION["nameOnCard"].'"/></td>';
        echo '<td><input type="text" style="border-color:#FDEEF4" name="secureCode" size="13" value="'.$_SESSION["secureCode"].'"/></td>';
        echo '<td>';
        $this->displayMonthDropDown();
        $this->displayYearDropDown();
        echo '</td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td/><td/><td/>';
        echo '<td><input class="new-card-button" name="payWithNewCard" type="submit" value="Add new card"/></td>';
        echo '</tr></table>';
        echo '</form>';
    }

    //This method displays the expire month drop down box
    private function displayMonthDropDown() {
        $startMth = 1;
        $endMth = 12;
        echo '<select name="expMonth" style="border-color:#FDEEF4">';
        for ($startMth; $startMth <= $endMth; $startMth++) {
            if ($startMth == 1)
                echo '<option value="'.$startMth.'" selected="selected">'.$startMth.'</option>';
            else 
                echo '<option value="'.$startMth.'">'.$startMth.'</option>';
        }
        echo '</select>';
    }

    //This method displays the expire year drop down box
    private function displayYearDropDown() {
        $startYear = date("Y");
        $endYear = $startYear + 20;
        echo '<select name="expYear" style="border-color:#FDEEF4">';
        for ($startYear; $startYear < $endYear; $startYear++)
            if ($startYear == date("Y"))
                echo '<option value="'.$startYear.'" selected="selected">'.$startYear.'</option>';
            else
                echo '<option value="'.$startYear.'">'.$startYear.'</option>';
        echo '</select>';
    }
  }
?>
