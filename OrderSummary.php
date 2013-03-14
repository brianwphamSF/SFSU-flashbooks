<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/OrderSummary.css" type="text/css" rel="stylesheet" media="all"/>
    </head>
    <body>
        <div class="submit-order-mainbody">
            <?php
            $url = htmlspecialchars("Payment.php");
            echo "<a href='".$url."'><font size='2em'><u><< Back</u></font></a>";
            ?>
            <div class="checkout-status"><br/><br/></div>
            <div class="review-order-prompt">Review and submit order</div>
            <div class="review-order-body">
                <form name="reviewOrderForm" action="OrderConfirmation.php">                    
                    <table class="review-order-table">
                        <?php
                        # Retrieve all user information
                        require_once("Includes/OrderSummaryOps.php");
                        OrderSummaryOps::getInstance()->displayShippingAddress($_SESSION);
                        OrderSummaryOps::getInstance()->displayShippingMethod();
                        OrderSummaryOps::getInstance()->displayPaymentMethod($_SESSION);
                        ?>
                    </table>
                    <table class="review-order-table">
                        <?php
                        require_once("Includes/db.php");
                        $itemsTotal = BookDB::getInstance()->retrieveShoppingCartTotal($_SESSION["sessionId"]);
                        OrderSummaryOps::getInstance()->displayOrderSummary($itemsTotal);
                        ?>
                        <tr class="review-order-table-body-align-right"><td></td><td>
                            <input class="review-order-button" name="placeYourOrder" type="submit" value="Place your order"/>
                        </td></tr>                        
                    </table>
                    <br style="clear: both">
                </form>
            </div>
       </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>
