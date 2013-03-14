<?php
/*
    Document   : BookDetailOps.php
    Created on : Nov 10, 2010
    Author     : Qianjun Yang
*/
  class BookDetailOps {
    //single instance of self shared among all instances
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

    public function verifyItemInShoppingCart($shopCartId, $bookId) {
        require_once("Includes/db.php");
        $shoppingCart = BookDB::getInstance()->verifyItemInShoppingCart($shopCartId, $bookId);
        return $shoppingCart;
    }
    
    public function addItemsToShoppingCart($shopCartId, $bookId, $bookQty, $bookPrice) {
        require_once("Includes/db.php");
        BookDB::getInstance()->addItemsToShoppingCart($shopCartId, $bookId, $bookQty, $bookPrice);
    }

    public function retrieveBookInfoByBookId($bookId) {
        require_once("Includes/db.php");
        $bookDetail = BookDB::getInstance()->retrieveBookInfoByBookId($_GET["bookId"]);
        return $bookDetail;
    }

    public function displayBookDetail($bookDetail, $addToCartError) {
        if ($addToCartError) {
            echo '<div class="add-to-cart-error">';
            echo 'The quantity for the item you are trying to add exceeds our available quantity.<br/><br/>';
            echo '</div>';
        }   
        $row = mysql_fetch_array($bookDetail);
        $bookId = $row['BOOK_ID'];
        $bookTitle = $row['TITLE'];
        $bookAuthor = $row['AUTHOR'];
        $bookEdition = $row['EDITION'];
        $bookPublisher = $row['PUBLISHER'];
        $bookPublishYear = $row['PUBLISH_YR'];
        $bookIsbn10 = $row['ISBN_10'];
        $bookIsbn13 = $row['ISBN_13'];
        $bookDescription = $row['DESCRIPTION'];
        $bookImagePath = $row['BOOK_IMAGE_DIR_PATH'];
        $bookPrice = $row['PRICE'];
        $bookInventoryCount = $row['INVENTORY_COUNT'];
        $bookSchoolName = $row['SCHOOL_NM'];
        $ungradCourseName = $row['UNGRAD_COURSE_NM'];
        $gradCourseName = $row['GRAD_COURSE_NM'];
        echo '<form name="bookItemForm" action="'.$_SERVER[PHP_SELF].'" method="POST" onClick="setCookie(\''.$bookId.'\', \''.$bookPrice.'\', \''.$bookInventoryCount.'\', \'\', 50);">';
        echo '<br/><table class="search-results-table"><tr><td class="search-results-column-one"><img src="'.$bookImagePath.'" style="width: 140px;"></td>';
        echo '<td class="search-results-column-two"><font class="book-title">'.$bookTitle.'&nbsp(';
        $this->displayBookEdition($bookEdition);
        echo ')</font><font class="book-author"><br/>by&nbsp;'.$bookAuthor.'<br/>';
        echo 'Publisher:&nbsp;'.$bookPublisher.',&nbsp;'.$bookPublishYear.'';
        echo '<br/>*Recommended for '.$bookSchoolName.' Computer Science courses</font><br/>Course(s): ';
        if ($ungradCourseName <> '')
            echo $ungradCourseName;
        if (($ungradCourseName <> '') && ($gradCourseName <> ''))
            echo ', ';
        if ($gradCourseName <> '')
            echo $gradCourseName;
        echo '<br/><font class="book-price">$'.number_format($bookPrice, 2).'</font><br/><br/><br/>';
        echo '<input class="form-button" name="searchAddToCartButton" type="submit" value="Add to cart"/>';
        echo '&nbsp;&nbsp;';
        echo '<input class="form-button" name="checkOutButton" type="submit" value="Checkout"/>';
        echo '</td></tr></table>';
        echo '<table><tr><td class="book-description"><font class="book-description-label">Description:&nbsp;</font><font>'.$bookDescription.'</font></td></tr></table>';
        echo '</form>';
        echo '<br/>';
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