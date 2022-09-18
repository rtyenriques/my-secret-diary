<?php


session_start();

if (array_key_exists("id", $_COOKIE) && $_COOKIE['id']) {
    $_SESSION['id'] = $_COOKIE['id'];
  
}


if (array_key_exists("id", $_SESSION) && $_SESSION['id']) {
    echo "<p></p>";
    // echo "<p><a href='index.php?logout=1'>logout</a></p> ";
    include("connection.php");
    $query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." ";
    $row = mysqli_fetch_array(mysqli_query($link, $query));
    $diaryContent = $row['diary'];

} else {
    header("Location: index.php");
}

include("header.html");
include("loginpage.html");
include("footer.html");


?>