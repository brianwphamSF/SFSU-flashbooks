<?php
session_start();
$_SESSION["refererPage"] = 'ShoppingCart.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION["searchCategories"] = $_POST["searchCategories"];
    $_SESSION["searchKeyword"] = $_POST["searchKeyword"];
    $_SESSION["searchResultPageNumber"] = $_POST["searchResultPageNumber"];
    if ($_POST["checkOutButton"]) {
        $_SESSION["checkOutButton"] = $_POST["checkOutButton"];
        if (is_null($_SESSION["userLogin"]))
            header('Location: Login.php');
        else
            header('Location: Address.php');
    }
    else if ($_POST["changeQtyButton"]) {
        require_once("Includes/ShoppingCartOps.php");
        if ($_POST["qtyDropDown"] == 0)
            ShoppingCartOps::getInstance()->removeBookFromShopCart($_SESSION["sessionId"], $_COOKIE["bookId"]);
        else
            ShoppingCartOps::getInstance()->updateBookInShopCart($_SESSION["sessionId"], $_COOKIE["bookId"], $_POST["qtyDropDown"], $_COOKIE["pricePerItem"]);
/*        if ($_COOKIE["bookQty"] < $_POST["qtyDropDown"])
            BookDB::getInstance()->updateBookInventory($_COOKIE["searchBookId"], ($_POST["searchResultsQtyDropDown"] - $_COOKIE["bookQty"]), $_COOKIE["searchBookInventoryCount"], false);
        else if ($_COOKIE["bookQty"] > $_POST["qtyDropDown"])
            BookDB::getInstance()->updateBookInventory($_COOKIE["searchBookId"], ($_COOKIE["bookQty"] + $_POST["searchResultsQtyDropDown"]), $_COOKIE["searchBookInventoryCount"], true);
*/
        header('Location: ShoppingCart.php?searchCategories=$_POST["searchCategories"]&searchKeyword=$_POST["searchKeyword"]&searchResultPageNumber=$_POST["searchResultPageNumber"]');
    }
}
?>

<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>

<?php
/*This is the main body of shopping cart web page.
 * 
 */
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <link href="css/ShoppingCart.css" type="text/css" rel="stylesheet" media="all"/>
        <script language="Javascript" type="text/javascript">
        function setCookie (bookQty, bookId, pricePerItem, expireDays) {
            var exdate = new Date();
            exdate.setDate(exdate.getDate() + expireDays);
            document.cookie = 'bookQty' + "=" + escape(bookQty) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'bookId' + "=" + escape(bookId) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
            document.cookie = 'pricePerItem' + "=" + escape(pricePerItem) + ((expireDays == null) ? "" : ";expires=" + exdate.toGMTString());
        }
        </script>
    </head>
    <body>
        <div class="shop-cart-mainbody">
            <?php
            $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
            echo "<a href='".$url."'><font size='2em'><u><< Back</u></font></a>";
            ?>
            <div class="shop-cart-image"><br/><br/></div>
                <?php
                require_once("Includes/ShoppingCartOps.php");
                $shoppingCart = ShoppingCartOps::getInstance()->retrieveShoppingCartItems($_SESSION["sessionId"]);
                if (!is_null($shoppingCart)) {
                    $subTotal = BookDB::getInstance()->retrieveShoppingCartTotal($_SESSION["sessionId"]);
                    ShoppingCartOps::getInstance()->displayShoppingCartItems($shoppingCart, $subTotal, $_SESSION["searchCategories"], $_SESSION["searchKeyword"], $_SESSION["searchResultPageNumber"]);
                }
                else
                    echo '<br><font class="shop-cart-warning">It seems like you do not have any item(s) in your shopping cart.</font><br><br>';
                ?>
        </div>
    </body>
</html>
<?php include "layout/footer.php"; ?>
