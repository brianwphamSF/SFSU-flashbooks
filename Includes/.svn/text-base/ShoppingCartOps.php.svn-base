<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

  class ShoppingCartOps {
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

    public function removeBookFromShopCart($shopCartId, $bookId) {
        require_once("Includes/db.php");
        BookDB::getInstance()->removeBookFromShopCart($shopCartId, $bookId);
    }

    public function updateBookInShopCart($shopCartId, $bookId, $bookQty, $bookPrice) {
        require_once("Includes/db.php");
        BookDB::getInstance()->updateBookInShopCart($shopCartId, $bookId, $bookQty, $bookPrice);
    }

    public function retrieveShoppingCartItems($shopCartId) {
        require_once("Includes/db.php");
        $shoppingCart = BookDB::getInstance()->retrieveShoppingCartItems($shopCartId);
        return $shoppingCart;
    }

    public function displayShoppingCartItems($shoppingCart,
                                             $itemsTotalResult,
                                             $searchCategories, 
                                             $searchKeyword, 
                                             $searchResultPageNumber) {
        echo '<form name="shoppingCartForm" action="'.$_SERVER[PHP_SELF].'" method="POST">';
        $row = mysql_fetch_array($itemsTotalResult);
        $itemsTotal = $row['SUBTOTAL'];
        echo '<table><tr><td class="subtotal-column-one"></td>';
        echo '<td class="subtotal-column-two"></td>';
        echo '<td class="subtotal-column-three"></td>';
        echo '<td class=""subtotal-column-four">';
        echo '<input class="checkout-button" name="checkOutButton" type="submit" value="CheckOut"/></td></tr></table>';
        echo '</form>';
        echo '<table><tr><th class="shop-cart-column-header-one">Shopping Cart Items - To Buy Now</th>';
        echo '<th class="shop-cart-column-header-two">Price:</th>';
        echo '<th class="shop-cart-column-header-three">Qty:</th>';
        echo '<th class="shop-cart-column-header-four">Change Qty?</th></tr></table>';

        while ($row = mysql_fetch_array($shoppingCart)) {
            $bookId = $row['BOOK_ID'];
            $bookTitle = $row['TITLE'];
            $bookQty = $row['QTY'];
            $pricePerItem = $row['PRICE_PER_ITEM'];
            $total = $row['TOTAL'];
            $inventoryCount = $row['INVENTORY_COUNT'];
            $bookEdition = $row['EDITION'];
            echo '<form name="changeQtyForm" action="'.$_SERVER[PHP_SELF].'" method="POST" onClick="setCookie(\''.$bookQty.'\', \''.$bookId.'\', \''.$pricePerItem.'\', 50);">';
            echo '<table><tr><td class="shop-cart-column-body-one">';
            echo $bookTitle.' (';
            $this->displayBookEdition($bookEdition);
            echo ')</td><td class="shop-cart-column-body-two">';
            echo '$'.number_format($pricePerItem, 2);
            echo '</td><td class="shop-cart-column-body-three">';
            $this->displayQtyDropDown($bookQty, $inventoryCount);
            echo '</td><td class="shop-cart-column-body-four">';
            echo '<input name="searchCategories" value="'.$searchCategories.'" type="hidden">';
            echo '<input name="searchKeyword" value="'.$searchKeyword.'" type="hidden">';
            echo '<input name="searchResultPageNumber" value="'.$searchResultPageNumber.'" type="hidden">';
            echo '<input class="change-qty-button" name="changeQtyButton" type="submit" value="Change"/>';
            echo '</td></tr></table></form>';
        }

        echo '<form name="shoppingCartForm" action="'.$_SERVER[PHP_SELF].'" method="POST">';
        echo '<table><tr><td class="subtotal-column-one"></td>';
        echo '<td class="subtotal-column-two">subtotal =&nbsp;</td>';
        echo '<td class="subtotal-column-three">$'.number_format($itemsTotal, 2).'</td>';
        echo '<td class=""subtotal-column-four">';
        echo '<input class="checkout-button" name="checkOutButton" type="submit" value="CheckOut"/></td></tr></table>';
        echo '</form>';
        echo '<br/>';
        echo '<form name="continueShoppingForm" action="SearchResults.php" method="POST">';
        echo '<table><tr><td class="subtotal-column-one"></td>';
        echo '<td class="subtotal-column-two"></td>';
        echo '<td class="subtotal-column-three"></td>';
        echo '<td class="subtotal-column-four">';
        echo '<input name="searchCategories" value="'.$searchCategories.'" type="hidden">';
        echo '<input name="searchKeyword" value="'.$searchKeyword.'" type="hidden">';
        echo '<input name="searchResultPageNumber" value="'.$searchResultPageNumber.'" type="hidden">';
        echo '<input class="continue-hopping-button" name="continutShoppingButton" type="submit" value="Continue Shopping >>"/></td></tr></table>';
        echo '</form>';

    }

    private function displayQtyDropDown($bookQty, $inventoryCount) {
        echo '<select class="qty-drop-down-box" name="qtyDropDown" style="border-color:#FDEEF4">';
        for ($count = 0; $count <= $inventoryCount; $count++) {
            if ($count == $bookQty)
                echo '<option value="'.$count.'" selected="selected">'.$count.'</option>';
            else
                echo '<option value="'.$count.'">'.$count.'</option>';
        }
        echo '</select>';
    }

    private function displayBookEdition($bookEdition) {
        switch ($bookEdition % 10) {
            case 1:
                echo $bookEdition.'st Edition';
                break;
            case 2:
                echo $bookEdition.'nd Edition';
                break;
            case 3:
                echo $bookEdition.'rd Edition';
                break;
            default:
                echo $bookEdition.'th Edition';
        }
    }
  }
?>
