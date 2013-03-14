<?php
/*
    Document   : OrderConfirmation.php
    Created on : Nov 2, 2010
    Author     : Wilson Kwok
*/
session_start();
?>
<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/OrderConfirmation.css" type="text/css" rel="stylesheet" media="all"/>
        <script type="text/javascript">
        function Print() {
            window.print();
        }
    </script>

    </head>
    <body>
        <div class="conf-mainbody">
            <?php
            require_once("Includes/db.php");
            BookDB::getInstance()->removeShopCart($_SESSION["sessionId"]);
            $_SESSION["cardNumber"] = '';
            $_SESSION["cardType"] = '';
            $_SESSION["expMonth"] = '';
            $_SESSION["expYear"] = '';
            $_SESSION["secureCode"] = '';
            $_SESSION["nameOnCard"] = '';            
            $_SESSION["addressLine1"] = '';
            $_SESSION["addressLine2"] = '';
            $_SESSION["city"] = '';
            $_SESSION["state"] = '';
            $_SESSION["zip"] = '';
            $_SESSION["shipToNewAddress"] = '';
            $_SESSION["shipToExistAddress"] = '';
            $_SESSION["payWithExistCard"] = '';
            $_SESSION["payWithNewCard"] = '';
            ?>
            <div class="conf-prompt">Order Confirmation<br/><br/></div>
            Your order has been placed successfully.<br/><br/>
            Your confirmation number is <?php echo $_SESSION["sessionId"]; ?>.<br/><br/>
            A confirmation has been sent to <?php echo $_SESSION["userLogin"]; ?>.<br/><br/>
            <form name="print" action="">
                <input class="print-conf-button" type="button" value="Print your order confirmation" onClick="window.print()">
            </form>
         </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>