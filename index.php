<?php
//This is the structure for our website, Please don't change this page...
//For the page you want to develop
//1. Write your own php file, then include that part, eg: "include "user-login.php".
//2. Add your own css(recommend write in separate files, eg: user-login.css).
//   In this way, we can easily debugger our own code without interrupt other pages.
//3. When you add javascript in your page, please write the code and comments in
//   a separate file then include it in your page. Sometimes, those functions can
//   be applied in many other pages.
//4. When use database, please add the functions in the Includes/db.php file, or
//   write in new file. I hope we can write this project as an oop project. =)
//5. After we finish this project and make sure each page is working properly, then
//    we begin to integrate all pages/css together.
//--Qianjun
session_start();
$_SESSION["sessionId"] = session_id();
$_SESSION["refererPage"] = 'index.php';
?>

<?php include "layout/header.php"; ?>
<?php include "layout/sidebar-left.php"; ?>
<?php include "layout/content.php"; ?>
<?php include "layout/footer.php"; ?>