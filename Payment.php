<?php
/*
    Document   : Payment.php
    Created on : Oct 20, 2010
    Author     : Wilson Kwok
*/
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["payWithExistCard"])
        $_SESSION["payWithExistCard"] = true;
    else
        $_SESSION["payWithNewCard"] = true;
    $_SESSION["expMonth"] = $_POST["expMonth"];
    $_SESSION["expYear"] = $_POST["expYear"];
    
    //Check credit card number input
    if ($_POST["cardNumber"] == "")
        $cardNumberError = true;
    else {
        $_SESSION["cardNumber"] = $_POST["cardNumber"];
        if (substr($_POST["cardNumber"], 0 ,1) == 5)
            $_SESSION["cardType"] = 'Master';
        else if (substr($_POST["cardNumber"], 0 ,1) == 4)
            $_SESSION["cardType"] = 'Visa';
        $cardNumberError = false;
    }
    //Check name on card input
    if ($_POST["nameOnCard"] == "")
        $nameOnCardError = true;
    else {
        $_SESSION["nameOnCard"] = $_POST["nameOnCard"];
        $nameOnCardError = false;
    }
    //Check security code input
    if ($_POST["secureCode"] == "")
        $secureCodeError = true;
    else {
        $_SESSION["secureCode"] = $_POST["secureCode"];
        $secureCodeError = false;
    }
    if ($cardNumbertError || $nameOnCardError || $secureCodeError) {
        $newCardError = true;
    }
    else {
        $newCardError = false;
        require_once("Includes/PaymentOps.php");
        PaymentOps::getInstance()->addUserCreditCardInfo($_SESSION["userLogin"],
                                                         $_SESSION["cardNumber"],
                                                         $_SESSION["cardType"],
                                                         $_SESSION["expMonth"],
                                                         $_SESSION["expYear"],
                                                         $_SESSION["secureCode"],
                                                         $_SESSION["nameOnCard"]);
        header('Location: OrderSummary.php');
    }
}
?>
<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/Payment.css" type="text/css" rel="stylesheet" media="all"/>
        <script language="Javascript" type="text/javascript">
        function setCookie (cardNumber, cardType, cardLastFourDigit, expMonth, expYear, nameOnCard, secureCode, expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);            
            document.cookie = 'cardNumber' + "=" + escape(cardNumber) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'cardType' + "=" + escape(cardType) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'cardLastFourDigit' + "=" + escape(cardLastFourDigit) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'expMonth' + "=" + escape(expMonth) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'expYear' + "=" + escape(expYear) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'secureCode' + "=" + escape(secureCode) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'nameOnCard' + "=" + escape(nameOnCard) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
        }
        </script>
    </head>
    <body>
        <div class="payment-mainbody">
            <?php
            $url = htmlspecialchars("ShippingMethod.php");
            echo "<a href='".$url."'><font size='2em'><u><< Back</u></font></a>";
            ?>
            <div class="checkout-status"><br/><br/></div>
            <div class="payment-prompt">How would you like to pay?<br/></div>
            <div class="payment-instruction">Please select a credit from your credit card list. Or you can enter a new credit card.</div>
                <?php
                require_once("Includes/PaymentOps.php");
                $payment = PaymentOps::getInstance()->retrieveCreditCardInfoByUser($_SESSION["userLogin"]);
                //If credit card(s) is available                
                if (!is_null($payment))
                    PaymentOps::getInstance()->displayExistingCreditCard($payment);
                else {
                    echo '<br><font class="card-warning">It seems like you do not have any credit or debit card(s) for you account.</font><br><br>';
                    echo '<br><font class="payment-instruction">Please enter a credit or debit card</font>';
                } 
                PaymentOps::getInstance()->displayNewCreditCardForm($_SESSION,
                                                                    $newCardError,
                                                                    $cardNumberError,
                                                                    $nameOnCardError,
                                                                    $secureCodeError);
                ?>
            </div>
    </body>
</html>

<?php include "layout/footer.php"; ?>
