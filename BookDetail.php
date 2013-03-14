<?php
/*
    Document   : BookDetail.php
    Created on : Nov 10, 2010
    Author     : Qianjun Yang
*/
session_start();
$_SESSION["refererPage"] = 'BookDetail.php';
require_once("Includes/BookDetailOps.php");
require_once("Includes/ShoppingCartOps.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $bookDetail = BookDetailOps::getInstance()->retrieveBookInfoByBookId($_GET["bookId"]);
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST["searchAddToCartButton"]) {
        $shoppingCart = BookDetailOps::getInstance()->verifyItemInShoppingCart($_SESSION["sessionId"], $_COOKIE["searchBookId"]);
        $row = @mysql_fetch_array($shoppingCart);
        if (($row['SHOP_CART_ID'] == $_SESSION["sessionId"]) && ($row['BOOK_ID'] == $_COOKIE["searchBookId"])) {
            $qty = $row['QTY'];
            if (($qty + 1) > $row["INVENTORY_COUNT"]) {
                $addToCartError = true;
            }
            else {
                ShoppingCartOps::getInstance()->updateBookInShopCart($_SESSION["sessionId"], $_COOKIE["searchBookId"], ($qty + 1), $_COOKIE["searchBookPrice"]);
                header('Location: ShoppingCart.php');
            }
        }
        else {
            BookDetailOps::getInstance()->addItemsToShoppingCart($_SESSION["sessionId"], $_COOKIE["searchBookId"], 1, $_COOKIE["searchBookPrice"]);
            header('Location: ShoppingCart.php');
        }
    }
    else if ($_POST["checkOutButton"]) {
        $_SESSION["checkOutButton"] = $_POST["checkOutButton"];
        BookDetailOps::getInstance()->addItemsToShoppingCart($_SESSION["sessionId"], $_COOKIE["searchBookId"], 1, $_COOKIE["searchBookPrice"]);
        if ($_SESSION["login"])
           header('Location: Address.php');
        else
           header('Location: Login.php');
    }
}
?>
<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/BookDetail.css" type="text/css" rel="stylesheet" media="all"/>
        <script language="Javascript" type="text/javascript">
        function setCookie (searchBookId, searchBookPrice, searchBookInventoryCount, searchResultPageNumber, expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);
            document.cookie = 'searchBookId' + "=" + escape(searchBookId) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'searchBookPrice' + "=" + escape(searchBookPrice) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'searchBookInventoryCount' + "=" + escape(searchBookInventoryCount) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'searchResultPageNumber' + "=" + escape(searchResultPageNumber) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
        }
        </script>
    </head>
    <body>

        <div class="search-result-mainbody">
            <br/><br/>
            <?php
            $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
            echo "<a href='".$url."'><font size='2em'><u><< Back</u></font></a><br/><br/>";
            if (is_null($bookDetail)) {
                echo '<div class="add-to-cart-error">';
                echo 'Unable to retrieve detail for the book.<br/><br/>';
                echo '</div>';
            }
            else {
                BookDetailOps::getInstance()->displayBookDetail($bookDetail, $addToCartError);
            }
            ?>
        </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>