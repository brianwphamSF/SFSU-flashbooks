<?php
/*
    Document   : SearchResultsOps.php
    Created on : Oct 25, 2010
    Author     : Shenhaochen Zhu
*/
  class SearchResultsOps {
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

    public function retrieveBookInfoByKeyword($keyword) {
        require_once("Includes/db.php");
        $bookDetails = BookDB::getInstance()->retrieveBookInfoByKeyword($keyword);
        return $bookDetails;
    }

    public function retrieveBookInfoByTitle($title) {
        require_once("Includes/db.php");
        $bookDetails = BookDB::getInstance()->retrieveBookInfoByTitle($title);
        return $bookDetails;
    }

    public function retrieveBookInfoByAuthor($author) {
        require_once("Includes/db.php");
        $bookDetails = BookDB::getInstance()->retrieveBookInfoByAuthor($author);
        return $bookDetails;
    }

    public function retrieveBookInfoByPublisher($publisher) {
        require_once("Includes/db.php");
        $bookDetails = BookDB::getInstance()->retrieveBookInfoByPublisher($publisher);
        return $bookDetails;
    }

    public function retrieveBookInfoByIsbn10($isbn10) {
        require_once("Includes/db.php");
        $bookDetails = BookDB::getInstance()->retrieveBookInfoByIsbn10($isbn10);
        return $bookDetails;
    }

    public function retrieveBookInfoByIsbn13($isbn13) {
        require_once("Includes/db.php");
        $bookDetails = BookDB::getInstance()->retrieveBookInfoByIsbn13($isbn13);
        return $bookDetails;
    }

    public function retrieveBookInfoByCourse($course) {
        require_once("Includes/db.php");
        $bookDetails = BookDB::getInstance()->retrieveBookInfoByCourse($course);
        return $bookDetails;
    }

    public function chooseAndPerformSearch($searchCategories, $searchKeyword) {
        if ($searchCategories == "searchByKeywords") 
            $bookDetails = $this->retrieveBookInfoByKeyword($searchKeyword);
        else if ($searchCategories == "searchByBookTitle")
            $bookDetails = $this->retrieveBookInfoByTitle($$searchKeyword);
        else if ($searchCategories == "searchByBookAuthor")
            $bookDetails = $this->retrieveBookInfoByAuthor($$searchKeyword);
        else if ($searchCategories == "searchByBookPublisher")
            $bookDetails = $this->retrieveBookInfoByPublisher($searchKeyword);
        else if ($searchCategories == "searchByISBN10")
            $bookDetails = $this->retrieveBookInfoByIsbn10($searchKeyword);
        else if ($searchCategories == "searchByISBN13")
            $bookDetails = $this->retrieveBookInfoByIsbn13($searchKeyword);
        else if ($searchCategories == "searchByCourse")
            $bookDetails = $this->retrieveBookInfoByCourse($searchKeyword);
        return $bookDetails;
    }

    private function generateSpaces($size) {
        $space = '';
        for ($i = 0; $i < $size; $i++)
            $space .= '&nbsp';
        $space .= ';';
        return $space;
    }

    public function displayBreadCrumbs($numRows, $searchResultPageNumber, $searchCategories, $searchKeyword) {
        if ($searchResultPageNumber == '')
            $searchResultPageNumber = 1;
        $itemsPerPage = 5;
        $totalPage = number_format($numRows / $itemsPerPage);
        if ($totalPage == 0)
            $totalPage = 1;
        echo '<table><tr><td class="breadcrumbs-column-one"></td>';
        echo '<td class="breadcrumbs-column-two">Page '.$searchResultPageNumber.' of '.$totalPage.'</td>';

        //Set previous link
        if ($searchResultPageNumber <> 1)
            echo '<td class="breadcrumbs-column-three"><a href="'.$_SERVER[PHP_SELF].'?searchCategories='.$searchCategories.'&searchKeyword='.$searchKeyword.'&searchResultPageNumber='.($searchResultPageNumber - 1).'"><< Previous</a>'.$this->generateSpaces(3);
        else
            echo '<td class="breadcrumbs-column-three">';

        //Set numeric links
        if ($totalPage <= 10) {
            $pageStart = 1;
            $pageEnd = $totalPage;
        }
        else if ($searchResultPageNumber <= 5) {
            $pageStart = 1;
            $pageEnd = $pageStart + 9;
        }
        else if ($searchResultPageNumber >= ($totalPage - 5)) {
            $pageStart = $totalPage - 9;
            $pageEnd = $totalPage;
        }
        else {
            $pageStart = $searchResultPageNumber - 4;
            if (($searchResultPageNumber + 4) > $totalPage)
                $searchResultPageNumber = $totalPage;
            else
                $pageEnd = $searchResultPageNumber + 4;
        }
        echo 'Page&nbsp';
        for ($i = $pageStart; $i <= $pageEnd; $i++) {
            if ($i == $searchResultPageNumber)
                //echo '<a style="color:#000000; text-decoration: none" href="'.$_SERVER[PHP_SELF].'" onClick="setCookie(\'\', \'\', \'\', \''.$i.'\', 50);">'.$i.'</a>'.$this->generateSpaces(2);
                echo '<a style="color:#000000; text-decoration: none" href="'.$_SERVER[PHP_SELF].'?searchCategories='.$searchCategories.'&searchKeyword='.$searchKeyword.'&searchResultPageNumber='.$i.'">'.$i.'</a>'.$this->generateSpaces(2);
            else
               echo '<a style="text-decoration: none" href="'.$_SERVER[PHP_SELF].'?searchCategories='.$searchCategories.'&searchKeyword='.$searchKeyword.'&searchResultPageNumber='.$i.'">'.$i.'</a>'.$this->generateSpaces(2);
        }

        //Set next link
        if ($searchResultPageNumber <> $totalPage)
            echo $this->generateSpaces(1).'<a href="'.$_SERVER[PHP_SELF].'?searchCategories='.$searchCategories.'&searchKeyword='.$searchKeyword.'&searchResultPageNumber='.($searchResultPageNumber + 1).'">Next >></a></td>';
        else
            echo '</td>';
        echo '</tr><tr><td></td>';
        echo '<td class="breadcrumbs-column-two">Total found '.$numRows.'</td></tr></table>';
    }

    public function displayBookItems($result, $addToCartError, $searchResultPageNumber, $searchCategories, $searchKeyword) {
        $count = 1;
        $itemsPerPage = 5;
        if ($searchResultPageNumber == '')
            $itemListEndsAt = 1;
        else 
            $itemListEndsAt = $searchResultPageNumber * $itemsPerPage;
        $itemListStartsAt = $itemListEndsAt - $itemsPerPage + 1;
        if ($addToCartError) {
            echo '<div class="add-to-cart-error">';
            echo 'The quantity for the item you are trying to add exceeds our available quantity.<br/><br/>';
            echo '</div>';
        }   
        for ($i = 1; $i < $itemListStartsAt; $i++) {
            $row = mysql_fetch_array($result);
            $count++;
        }
        for ($itemListStartsAt; $itemListStartsAt <= $itemListEndsAt; $itemListStartsAt++) {
            $row = mysql_fetch_array($result);
            if (!$row)
                break;
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
            echo '<br/><table class="search-results-table"><tr><td class="search-results-column-one">'.$count.'.</td>';
            echo '<td class="search-results-column-two"><a href="BookDetail.php?bookId='.$bookId.'"><img src="'.$bookImagePath.'" style="width: 120px;"></a></td>';
            echo '<td class="search-results-column-three"><font class="book-title">'.$bookTitle.'&nbsp(';
            $this->displayBookEdition($bookEdition);
            echo ')&nbsp;</font><font class="book-author">by&nbsp;'.$bookAuthor;
            echo '<br/>*Recommended for '.$bookSchoolName.' Computer Science courses<br/>Course(s): ';
            if ($ungradCourseName <> '')
                echo $ungradCourseName;
            if (($ungradCourseName <> '') && ($gradCourseName <> ''))
                echo ', ';
            if ($gradCourseName <> '')
                echo $gradCourseName;
            echo '</font><br/><font class="book-price">$'.number_format($bookPrice, 2).'&nbsp;</font><br/><br/>';
            echo '<font class="book-description-label">Description:&nbsp;</font><font class="book-description">'.substr($bookDescription, 0, 150).'...</font></td>';
            echo '<td class="search-results-column-four">';
            echo '<input name="searchCategories" value="'.$searchCategories.'" type="hidden">';
            echo '<input name="searchKeyword" value="'.$searchKeyword.'" type="hidden">';
            echo '<input name="searchResultPageNumber" value="'.$searchResultPageNumber.'" type="hidden">';
            echo '<input class="form-button" name="searchAddToCartButton" type="submit" value="Add to cart"/>';
            echo '<br/><br/>';
            echo '<input class="form-button" name="checkOutButton" type="submit" value="Checkout"/>';
            echo '</td>';
            echo '</tr></table>';
            echo '</form>';
            $count++;
        }
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
