<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/ShippingMethod.css" type="text/css" rel="stylesheet" media="all"/>
        <script language="Javascript" type="text/javascript">
        function setCookie (carrier, shipMethodDesc, shipCost, expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);            
            document.cookie = 'carrier' + "=" + escape(carrier) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'shipMethodDesc' + "=" + escape(shipMethodDesc) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'shipCost' + "=" + escape(shipCost) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
        }
        </script>
    </head>
    <body>
        <div class="ship-method-mainbody">
            <?php
            // shipping method selection that is used in conjunction with the address
            // and what the user has selected...
            $url = htmlspecialchars("Address.php");
            echo "<a href='".$url."'><font size='2em'><u><< Back</u></font></a>";
            require_once("Includes/ShippingMethodOps.php");
            $shippingMethod = ShippingMethodOps::getInstance()->retrieveShippingMethods();
            ShippingMethodOps::getInstance()->displayShippingMethods($shippingMethod);
            ?>               
       </div>
    </body>
</html>

<?php include "layout/footer.php"; ?>
