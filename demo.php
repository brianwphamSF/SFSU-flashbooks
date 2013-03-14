<?php
session_start();
$_SESSION["sessionId"] = session_id();
//$_SESSION["sessionId"] = 'g648gjp1mnbcu06ggkdtqn4840';
$_SESSION["refererPage"] = 'index.php';
?>
<?php include "layout/header.php"; ?>
<div>

<p>

<center><img src='images/advertisements.png' alt='ad'/></center>
</div>
<p>
<?php
  $url = htmlspecialchars($_SERVER['HTTP_REFERER']);
  echo "<a href='$url'>Go Back</a>";
?>
<?php include "layout/footer.php"; ?>