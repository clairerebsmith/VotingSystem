<?php
    include "../includes/config/validate.php";
    require "../includes/config/opendb.php";

    if (empty($_POST['email'])) {

        if($_SESSION['admin'] == '1'){
            echo 'Please enter Username';
        }else{
            echo 'Please enter email Address';
        }

    } else {
        $email = $conn -> real_escape_string($_POST['email']);

        $sql = "SELECT `email` FROM `login` WHERE `email` LIKE '$email'";
        $result = mysqli_query($conn, $sql);

        if( isset($_SESSION['admin'])){
            if (mysqli_num_rows($result) != 0) {
                echo "Username Address already exists";
            }
        }else{
            if (mysqli_num_rows($result) != 0) {
                echo "Email Address already exists";
            }
        }
    }
?>

<!--Like the checkEmpID this page has the same functionality. Instead this time it is checking for emailID for voters and -->
<!--usernames for the admin. If a session exists and the session user is an admin it will display a different message-->