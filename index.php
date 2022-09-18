<?php

session_start();
$diaryContent = "";
$error = "";
$success = "";


if (array_key_exists("logout", $_GET)) {
    
    unset($_SESSION['id']);
    // session_unset();
    setcookie("id", "", time() - 60*60);
    $_COOKIE["id"] = "";
    // session_destroy();
} elseif (array_key_exists("id", $_SESSION) AND $_SESSION['id'] OR array_key_exists("id", $_COOKIE) AND $_COOKIE['id']) {
    header("Location: loggedinpage.php");
}




if (array_key_exists("submit", $_POST)) {
    
    include("connection.php");

    if (!$_POST['email']) {
        $error .= "email address required<br>";
    }

    if (!$_POST['password']) {
        $error .= "password is required";
    }

    if ($error != "") {
        $error = "<p>There were error(s) in your form</p>".$error;
    } else {

        if($_POST['signup'] == "1") {

            $query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

            $result = mysqli_query($link, $query);

            if (mysqli_num_rows($result) > 0) {
                $error = "That email is taken";
            } else {
                $query = "INSERT INTO `users` (`email`, `password`, `diary`) VALUES 
                ('".mysqli_real_escape_string($link, $_POST['email'])."',
                '".mysqli_real_escape_string($link, $_POST['password'])."', 'Welcome to your diary')";
   
                if (!mysqli_query($link, $query)) {
                echo "Couldnt sign you up";
                } else {
                 

                // $password = md5(md5(mysqli_insert_id($link)).$_POST['password']);
               
            
                $query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." ";
               
                mysqli_query($link, $query);

                // $query = "SELECT * FROM `users` WHERE id = '".mysqli_real_escape_string($link, mysqli_insert_id($link))."'";

                // $result = mysqli_query($link, $query);

                // $row = mysqli_fetch_array($result);

                // $_SESSION['id'] = $row['id'];
               
                $_SESSION['id'] = mysqli_insert_id($link);

                    if ($_POST['stayloggedin'] == "1") {
                        setcookie("id" , mysqli_insert_id($link), time() + 60*60*24*365 );
                    }
                //  echo "account success, please login";
                 $success = "account created successfully, please login";
                //  header("Location: loggedinpage.php");
                }
                }

            } else {
                $query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

                $result = mysqli_query($link, $query);

                $row = mysqli_fetch_array($result);
              
                
                
                // if (isset($row)) {
                    
                if (array_key_exists("id", $row)) {
                    
                    $hashedPassword = md5(md5($row['id']).$_POST['password']);
            
                   
                    if ($hashedPassword == $row['password']) {
                        
                        // $_SESSION['id'] = mysqli_insert_id($link);
                        $_SESSION['id'] = $row['id'];
                        if ($_POST['stayLoggedIn'] == '1') {
                            setcookie("id", $row['id'], time() + 60*60*24*365);
                        } 
                        
                    } else {
                        $error = "email and password combination cant be found";
                    }
                    header("Location: loggedinpage.php");
                } else {
                    $error = "email and password combination cant be found";
                }
               

        }

    }

} 

include("header.html");
include("forms.html");
include("footer.html");

?>

