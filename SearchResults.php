<?php
/*
    Document   : SearchResults.php
    Created on : Oct 25, 2010
    Author     : Shenhaochen Zhu
*/
session_start();
$_SESSION["refererPage"] = 'SearchResults.php';
require_once("Includes/SearchResultsOps.php");
require_once("Includes/ShoppingCartOps.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $_SESSION["searchCategories"] = $_GET["searchCategories"];
    $_SESSION["searchKeyword"] = $_GET["searchKeyword"];
    $_SESSION["searchResultPageNumber"] = $_GET["searchResultPageNumber"];
    $bookDetails = SearchResultsOps::getInstance()->chooseAndPerformSearch($_GET["searchCategories"], $_GET["searchKeyword"]);
}
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["searchCategories"] = $_POST["searchCategories"];
    $_SESSION["searchKeyword"] = $_POST["searchKeyword"];
    $_SESSION["searchResultPageNumber"] = $_POST["searchResultPageNumber"];
    $bookDetails = SearchResultsOps::getInstance()->chooseAndPerformSearch($_POST["searchCategories"], $_POST["searchKeyword"]);
    if ($_POST["searchAddToCartButton"]) {
        $shoppingCart = SearchResultsOps::getInstance()->verifyItemInShoppingCart($_SESSION["sessionId"], $_COOKIE["searchBookId"]);
        $row = @mysql_fetch_array($shoppingCart);
        if (($row['SHOP_CART_ID'] == $_SESSION["sessionId"]) && ($row['BOOK_ID'] == $_COOKIE["searchBookId"])) {
            $qty = $row['QTY'];
            if (($qty + 1) > $row["INVENTORY_COUNT"]) {
                $addToCartError = true;
            }
            else {
                ShoppingCartOps::getInstance()->updateBookInShopCart($_SESSION["sessionId"], $_COOKIE["searchBookId"], ($qty + 1), $_COOKIE["searchBookPrice"]);
                header('Location: ShoppingCart.php?searchCategories=$_POST["searchCategories"]&searchKeyword=$_POST["searchKeyword"]&searchResultPageNumber=$_POST["searchResultPageNumber"]');
            }
        }
        else {
            SearchResultsOps::getInstance()->addItemsToShoppingCart($_SESSION["sessionId"], $_COOKIE["searchBookId"], 1, $_COOKIE["searchBookPrice"]);
            header('Location: ShoppingCart.php?searchCategories=$_POST["searchCategories"]&searchKeyword=$_POST["searchKeyword"]&searchResultPageNumber=$_POST["searchResultPageNumber"]');
        }
    }
    else if ($_POST["checkOutButton"]) {
        $_SESSION["checkOutButton"] = $_POST["checkOutButton"];
        SearchResultsOps::getInstance()->addItemsToShoppingCart($_SESSION["sessionId"], $_COOKIE["searchBookId"], 1, $_COOKIE["searchBookPrice"]);
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
        <link href="css/SearchResults.css" type="text/css" rel="stylesheet" media="all"/>
        <script language="Javascript" type="text/javascript">
        function setCookie (searchBookId, searchBookPrice, searchBookInventoryCount, searchResultPageNumber, expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);
            document.cookie = 'searchBookId' + "=" + escape(searchBookId) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'searchBookPrice' + "=" + escape(searchBookPrice) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'searchBookInventoryCount' + "=" + escape(searchBookInventoryCount) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'searchResultPageNumber' + "=" + escape(searchResultPageNumber) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
        }
        function setSearchResultPageNumberCookie (searchResultPageNumber, expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);
            document.cookie = 'searchResultPageNumber' + "=" + escape(searchResultPageNumber) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
        }
        </script>
    </head>
    <body onLoad="javascript:setSearchResultPageNumberCookie('', 1);">

        <div class="search-result-mainbody">
            <br/><br/>
            <?php
            $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
            echo "<a href='".$url."'><font size='2em'><u><< Back</u></font></a><br/><br/>";
            if (is_null($bookDetails)) {
                echo '<div class="add-to-cart-error">';
                echo 'No match found for your search.<br/><br/>';
                echo '</div>';
            }
            else {
                $numRows = mysql_num_rows($bookDetails);
                SearchResultsOps::getInstance()->displayBreadCrumbs($numRows, $_SESSION["searchResultPageNumber"], $_SESSION["searchCategories"], $_SESSION["searchKeyword"]);
                SearchResultsOps::getInstance()->displayBookItems($bookDetails, $addToCartError, $_SESSION["searchResultPageNumber"], $_SESSION["searchCategories"], $_SESSION["searchKeyword"]);
                SearchResultsOps::getInstance()->displayBreadCrumbs($numRows, $_SESSION["searchResultPageNumber"], $_SESSION["searchCategories"], $_SESSION["searchKeyword"]);
            }
            ?>
        </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>
