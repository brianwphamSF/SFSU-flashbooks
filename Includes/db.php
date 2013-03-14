<?php
  class BookDB {
  // single instance of self shared among all instances
    private static $instance = null;

/*    // db connection config vars
    private $user = "fall201007";
    private $pass = "fall201007";
    private $dbName = "fall201007";
    private $dbHost = "hci.cs.sfsu.edu:3306";
*/
    private $dbHost = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbName = "fall201007";

    private $con = null;

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

    // private constructor
    private function __construct() {
        $this->con = mysql_connect($this->dbHost, $this->user, $this->pass)
        or die ("Could not connect to db: " . mysql_error());
        //SET NAMES sets client, results, and connection character sets
        mysql_query("SET NAMES 'utf8'");
        mysql_select_db($this->dbName, $this->con)
        or die ("Could not select db: " . mysql_error());
    }

    //This method will retrieve user's credentials
    public function retrieveUserCredentials($username, $password) {
        $username = mysql_real_escape_string($username);
        $password = mysql_real_escape_string($password);
        $result = mysql_query("SELECT USER_NM, PASSWD, FULL_NM
                               FROM USER_ACCT
                               WHERE USER_NM = '".$username."'
                               AND   PASSWD = '".$password."'");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    //This method will retrieve a list of credit cards belonging to a user.
    public function retrieveAddressInfoByUser($user) {
        $user = mysql_real_escape_string($user);
        $result =  mysql_query("SELECT FULL_NM,
                                       ADDR_LINE_1,
                                       ADDR_LINE_2,
                                       CITY,
                                       STATE,
                                       ZIP,
                                       ACCT_ID
                               FROM ADDR_DTL
                               WHERE ACCT_ID = (SELECT ACCT_ID
                                                FROM USER_ACCT
                                                WHERE USER_NM = '" . $user . "')");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    public function addUserAddress ($username, $fullName, $addressLine1, $addressLine2, $city, $state, $zip) {
        $username = mysql_real_escape_string($username);
        $fullName = mysql_real_escape_string($fullName);
        $addressLine1 = mysql_real_escape_string($addressLine1);
        $addressLine2 = mysql_real_escape_string($addressLine2);
        $city = mysql_real_escape_string($city);
        $state = mysql_real_escape_string($state);
        $zip = mysql_real_escape_string($zip);
        $result = mysql_query("INSERT INTO ADDR_DTL (FULL_NM, ADDR_LINE_1, ADDR_LINE_2, CITY, STATE, ZIP, ACCT_ID)
                               VALUES ('".$fullName."',
                                       '".$addressLine1."',
                                       '".$addressLine2."',
                                       '".$city."',
                                       '".$state."',
                                       '".$zip."',
                                       (SELECT ACCT_ID
                                        FROM USER_ACCT
                                        WHERE USER_NM = '".$username."'))");
    }


    //This method will retrieve a list of shipping methods available for users
    public function retrieveShippingMethods() {
        $result =  mysql_query("SELECT * FROM SHIP_METHOD");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    //This method will retrieve a list of credit cards belonging to a user.
    public function verifyUsername($username) {
        $user = mysql_real_escape_string($username);
        $result = mysql_query("SELECT USER_NM
                               FROM USER_ACCT
                               WHERE USER_NM = '".$username."'");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    public function createNewUserAccount($emailAddress, $fullName, $password) {
        $fullName = mysql_real_escape_string($fullName);
        $emailAddress = mysql_real_escape_string($emailAddress);
        $password = mysql_real_escape_string($password);
        mysql_query("INSERT INTO USER_ACCT (USER_NM, PASSWD, FULL_NM, EMAIL)
                    VALUES ('".$emailAddress."','".$password."','".$fullName."','".$emailAddress."')");
    }

    public function retrieveShoppingCartItems($shopCartID) {
        $shopCartID = mysql_real_escape_string($shopCartID);
        $result =  mysql_query("SELECT  SHOP_CART.BOOK_ID AS BOOK_ID,
                                        QTY,
                                        SHOP_CART.PRICE_PER_ITEM,
                                        TOTAL,
                                        TITLE,
                                        EDITION,
                                        INVENTORY_COUNT
                                FROM SHOP_CART, BOOK_DTL
                                WHERE SHOP_CART_ID = '".$shopCartID."'
                                AND SHOP_CART.BOOK_ID = BOOK_DTL.BOOK_ID");
       if (mysql_num_rows($result) > 0)
            return $result;
       else
            return null;
       mysql_close($con);
    }

    public function retrieveShoppingCartTotal($shopCartID) {
        $shopCartID = mysql_real_escape_string($shopCartID);
        $result =  mysql_query("SELECT SUM(TOTAL) AS SUBTOTAL
                                FROM SHOP_CART, BOOK_DTL
                                WHERE SHOP_CART_ID = '".$shopCartID."'
                                AND SHOP_CART.BOOK_ID = BOOK_DTL.BOOK_ID
                                GROUP BY SHOP_CART_ID");
       if (mysql_num_rows($result) > 0)
            return $result;
       else
            return null;
       mysql_close($con);
    }

    //This method will retrieve a list of credit cards belonging to a user.
    public function retrieveCreditCardInfoByUser($user) {
        $user = mysql_real_escape_string($user);
        $result =  mysql_query("SELECT CARD_TYPE,
                                       CARD_NUM,
                                       EXPIRATION_MTH,
                                       EXPIRATION_YR,
                                       NM_ON_CARD
                               FROM CREDIT_CARD_INFO
                               WHERE ACCT_ID = (SELECT ACCT_ID
                                                FROM USER_ACCT
                                                WHERE USER_NM = '" .$user. "')");
       if (mysql_num_rows($result) > 0)
            return $result;
       else
            return null;
       mysql_close($con);
    }

    public function addUserCreditCardInfo ($username, $cardType, $cardNumber, $expMonth, $expYear, $secureCode, $nameOnCard) {
        $username = mysql_real_escape_string($username);
        $fullName = mysql_real_escape_string($cardType);
        $addressLine1 = mysql_real_escape_string($cardNum);
        $addressLine2 = mysql_real_escape_string($expMonth);
        $city = mysql_real_escape_string($expYear);
        $state = mysql_real_escape_string($secureCode);
        $zip = mysql_real_escape_string($nameOnCard);
        $result = mysql_query("INSERT INTO CREDIT_CARD_INFO (CARD_TYPE, CARD_NUM, EXPIRATION_MTH, EXPIRATION_YR, SECURITY_CD, NM_ON_CARD, ACCT_ID)
                               VALUES ('".$cardType."',
                                       '".$cardNumber."',
                                       '".$expMonth."',
                                       '".$expYear."',
                                       '".$secureCode."',
                                       '".$nameOnCard."',
                                       (SELECT ACCT_ID
                                        FROM USER_ACCT
                                        WHERE USER_NM = '".$username."'))");
    }

    public function retrieveBookInfoByKeyword($keyword) {
        $keyword = mysql_real_escape_string($keyword);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM,
                                      UNGRAD_COURSE_NM,
                                      GRAD_COURSE_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR,
                                    (SELECT BOOK_ID,
                                            CONCAT(REPLACE(UNGRAD_COURSE_NM, ' ', ''),
                                                   REPLACE(GRAD_COURSE_NM, ' ', ''),
                                                   UNGRAD_COURSE_NM,
                                                   GRAD_COURSE_NM) AS COURSES
                                     FROM BOOK_DTL
                                     GROUP BY BOOK_ID) AS COMBINED_COURSES
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND BD.BOOK_ID = COMBINED_COURSES.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND (TITLE LIKE '%".$keyword."%'
                                    OR DESCRIPTION LIKE '%".$keyword."%'
                                    OR PUBLISHER LIKE '%".$keyword."%'
                                    OR ISBN_10 LIKE '%".$keyword."%'
                                    OR ISBN_13 LIKE '%".$keyword."%'
                                    OR AUTHOR LIKE '%".$keyword."%'
                                    OR COMBINED_COURSES.COURSES	LIKE '%".$keyword."%')
                               GROUP BY BOOK_ID");
       if (mysql_num_rows($result) > 0)
            return $result;
       else
            return null;
       mysql_close($con);
    }

    //This method will retrieve a list of books by book ID
    public function retrieveBookInfoByBookId($bookId) {
        $bookId = mysql_real_escape_string($bookId);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM,
                                      UNGRAD_COURSE_NM,
                                      GRAD_COURSE_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND BD.BOOK_ID = ".$bookId."
                               GROUP BY BOOK_ID");
       if (mysql_num_rows($result) > 0)
            return $result;
       else
            return null;
       mysql_close($con);
    }

    //This method will retrieve a list of books by book author
    public function retrieveBookInfoByAuthor ($author) {
        $author = mysql_real_escape_string($author);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND (AUTHOR LIKE '%".$author."%')
                               GROUP BY BOOK_ID");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    //This method will retrieve a list of books by book title
    public function retrieveBookInfoByTitle ($title) {
        $title = mysql_real_escape_string($title);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND (TITLE LIKE '%".$title."%')
                               GROUP BY BOOK_ID");
        if (mysql_num_rows($result) > 0)
           return $result;
        else
           return null;
        mysql_close($con);
    }

    //This method will retrieve a list of books by ISBN-10
    public function retrieveBookInfoByIsbn10 ($isbn10) {
        $isbn10 = mysql_real_escape_string($isbn10);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND (ISBN_10 LIKE '%".$isbn10."%')
                               GROUP BY BOOK_ID");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    //This method will retrieve a list of books by ISBN-13
    public function retrieveBookInfoByIsbn13 ($isbn13) {
        $isbn10 = mysql_real_escape_string($isbn13);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND (ISBN_13 LIKE '%".$isbn13."%')
                               GROUP BY BOOK_ID");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    //This method will retrieve a list of books by publisher
    public function retrieveBookInfoByPublisher ($publisher) {
        $publisher = mysql_real_escape_string($publisher);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND (PUBLISHER LIKE '%".$publisher."%')
                               GROUP BY BOOK_ID");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    //This method will retrieve a list of books by course
    public function retrieveBookInfoByCourse ($courseName) {
        $courseName = mysql_real_escape_string($courseName);
        $result = mysql_query("SELECT BD.BOOK_ID AS BOOK_ID,
                                      TITLE,
                                      AUTHOR,
                                      EDITION,
                                      PUBLISHER,
                                      PUBLISH_YR,
                                      ISBN_10,
                                      ISBN_13,
                                      DESCRIPTION,
                                      BOOK_IMAGE_DIR_PATH,
                                      PRICE,
                                      INVENTORY_COUNT,
                                      SCHOOL_NM
                               FROM BOOK_DTL BD,
                                    COURSE,
                                    (SELECT BOOK_ID,
                                            GROUP_CONCAT(FULL_NM  SEPARATOR ', ') AS AUTHOR
                                     FROM AUTHOR
                                     GROUP BY BOOK_ID) AUTHOR,
                                    (SELECT BOOK_ID,
                                            CONCAT(REPLACE(UNGRAD_COURSE_NM, ' ', ''),
                                                   REPLACE(GRAD_COURSE_NM, ' ', ''),
                                                   UNGRAD_COURSE_NM,
                                                   GRAD_COURSE_NM) AS COURSES
                                     FROM BOOK_DTL
                                     GROUP BY BOOK_ID) AS COMBINED_COURSES
                               WHERE BD.BOOK_ID = AUTHOR.BOOK_ID
                               AND BD.BOOK_ID = COMBINED_COURSES.BOOK_ID
                               AND (COURSE.COURSE_NM = UNGRAD_COURSE_NM OR COURSE.COURSE_NM = GRAD_COURSE_NM)
                               AND (COMBINED_COURSES.COURSES LIKE '%".$courseName."%')
                               GROUP BY BOOK_ID");
       if (mysql_num_rows($result) > 0)
            return $result;
       else
            return null;
       mysql_close($con);
    }

    //This method will retrieve a list of courses
    public function retrieveCourses($schoolName) {
        $result = mysql_query("SELECT SCHOOL_NM,
                                      SCHOOL_ABREV,
                                      COURSE_NM
                               FROM COURSE
                               WHERE SCHOOL_NM = '".$schoolName."'");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    //This method will retrieve the items in a user's shopping cart.
    public function retrieveShoppingCart($shopCartId){
        $result = mysql_query("SELECT TITLE, INVENTORY_COUNT, PRICE_PER_ITEM, QTY,TOTAL
                               FROM BOOK_DTL, SHOP_CART
                               WHERE SHOP_CART_ID = '" . $shopCartId."'
                               AND SHOP_CART.BOOK_ID = BOOK_DTL.BOOK_ID");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;

       mysql_close($con);

    }

    //This method will remove the specified shopping cart.
    public function removeShopCart($shopCartId) {
        return mysql_query("DELETE FROM SHOP_CART
                            WHERE SHOP_CART_ID = '".$shopCartId."'");
        mysql_close($con);
    }

    //This method will remove a book item from shopping cart
    public function removeBookFromShopCart($shopCartId, $bookId) {
        return mysql_query("DELETE FROM SHOP_CART
                            WHERE SHOP_CART_ID = '".$shopCartId."'
                            AND BOOK_ID = ".$bookId);
        mysql_close($con);
    }

    //This method will update a book item from shopping cart
    public function updateBookInShopCart($shopCartId, $bookId, $bookQty, $bookPrice) {
        $shopCartId = mysql_real_escape_string($shopCartId);
        $bookId = mysql_real_escape_string($bookId);
        $bookQty = mysql_real_escape_string($bookQty);
        $bookPrice = mysql_real_escape_string($bookPrice);
        return mysql_query("UPDATE SHOP_CART
                           SET QTY = ".$bookQty.",
                           TOTAL = (".$bookQty."*".$bookPrice.")
                           WHERE SHOP_CART_ID = '".$shopCartId."'
                           AND BOOK_ID = ".$bookId);
        mysql_close($con);
    }

    public function addItemsToShoppingCart($shopCartId, $bookId, $bookQty, $bookPrice) {
        $shopCartId = mysql_real_escape_string($shopCartId);
        $bookId = mysql_real_escape_string($bookId);
        $bookQty = mysql_real_escape_string($bookQty);
        $bookPrice = mysql_real_escape_string($bookPrice);
        $bookTotal = $bookQty * $bookPrice;
        return mysql_query("INSERT INTO SHOP_CART (SHOP_CART_ID, BOOK_ID, QTY, PRICE_PER_ITEM, TOTAL)
                            VALUES ('".$shopCartId."',".$bookId.",".$bookQty.",".$bookPrice.",".$bookTotal.")");
        mysql_close($con);
    }

    public function verifyItemInShoppingCart($shopCartId, $bookId) {
        $shopCartId = mysql_real_escape_string($shopCartId);
        $bookId = mysql_real_escape_string($bookId);
        $result = mysql_query("SELECT SC.SHOP_CART_ID,
                                      SC.BOOK_ID,
                                      SC.QTY,
                                      BD.INVENTORY_COUNT
                               FROM SHOP_CART SC, BOOK_DTL BD
                               WHERE SC.SHOP_CART_ID = '".$shopCartId."'
                               AND SC.BOOK_ID = ".$bookId."
                               AND SC.BOOK_ID = BD.BOOK_ID");
        if (mysql_num_rows($result) > 0)
            return $result;
        else
            return null;
        mysql_close($con);
    }

    public function updateBookInventory($bookId, $bookQty, $bookInventoryCount, $add) {
        $bookId = mysql_real_escape_string($bookId);
        $bookQty = mysql_real_escape_string($bookQty);
        $bookInventoryCount = mysql_real_escape_string($bookInventoryCount);
        if ($add)
            $result = mysql_query("UPDATE BOOK_DTL
                                   SET INVENTORY_COUNT = (".$bookInventoryCount."+".$bookQty.")
                                   WHERE BOOK_ID = ".$bookId);
        else
            $result = mysql_query("UPDATE BOOK_DTL
                                   SET INVENTORY_COUNT = (".$bookInventoryCount."-".$bookQty.")
                                   WHERE BOOK_ID = ".$bookId);
        mysql_close($con);
        return $result;

    }
  }
?>