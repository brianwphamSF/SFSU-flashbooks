<?php
// php login sessions, cookies, etc should be declared here so once a user entered in their info in the login area. they won't have to re-enter their info and
// the website will keep track of what they may do on the website.
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>

        <title>Flash Books</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="Content-Language" content="en-US" />
        <meta name="author" content="Wilson Kwok, Qianjun Yang, Brian Pham, Ning Jiang, Jing Jing Liu, Michael Zhu" />
        <meta name="copyright" content="&copy; 2010 SFSU" />
        <meta name="keywords" content="Flash Books" />
        <meta name="rating" content="general" />
        <meta name="revisit-after" content="7 days" />
        <link rel="stylesheet" href="css/stylever2.css" type="text/css" />
        <link rel="stylesheet" href="css/jquery.css" type="text/css" />
        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("ul.menu_body li:even").addClass("alt");
//                $('img.menu_head').click(function () {
//                    $('ul.menu_body').slideToggle('medium');
//                });
                $('ul.menu_body li a').mouseover(function () {
                    $(this).animate({ fontSize: "19px", paddingLeft: "20px" }, 50 );
                });
                $('ul.menu_body li a').mouseout(function () {
                    $(this).animate({ fontSize: "16px", paddingLeft: "10px" }, 50 );
                });
            });
        </script>
    </head>

    <body class="mainbody sidebars">
        <div id="wrapper">
            <!-- start header -->

            <div id="primary-menu">
                <ul>
                    <li><a title="Go To The Home Page" href="index.php">Home</a></li>
                    <!--<li><a title="" href="#">Search</a></li>-->
                    <form name="searchForm" action="SearchResults.php" method="POST">
                        <li><a title="" href="#">
                                <select name="searchCategories" title="Search in"  style='min-width:10em; _width:10em;'>
                                    <option value="searchByKeywords" selected="selected">Keywords</option>
                                    <option value="searchByBookTitle">Title</option>
                                    <option value="searchByBookAuthor">Author</option>
                                    <option value="searchByBookPublisher">Publisher</option>
                                    <option value="searchByISBN10">ISBN 10</option>
                                    <option value="searchByISBN13">ISBN 13</option>
                                    <option value="searchByCourse">Course</option>
                                </select>
                                <input type="text" name="searchKeyword" value="" size="63" />
                                <input type="submit" name="goSearch" value="Go" class="form-submit"/>
                            </a>
                        </li>
                    </form>
                    <li><a title="" href="ShoppingCart.php"><img src="images/shoppingcart.png" border="0" alt="Cart"/></a></li>
                    <form name="signIn" action="index.php" method="POST">
                        <?php
                        if (is_null($_SESSION["userLogin"]))
                            echo '<li><a title="signIn" href="Login.php">Sign In</a></li>';
                        else {
                            echo '<li><a title="signOut" href="Login.php?signOut=yes">Sign Out</a></li>';
                        }
                        //echo '<font size="2em" color="#FFFFFF">Welcome '.$_SESSION["welcomeUser"].'!</font>';
                        ?>
                    </form>
                </ul>
            </div>

            <!-- end header -->

            <!-- start page -->
            <div id="page">