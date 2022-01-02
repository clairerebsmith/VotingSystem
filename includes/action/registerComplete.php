<?php

    // This page has been defined to verify the user from the email link they have clicked on
    // Line 10 and 11 are retrieving the information from the URL Link



    error_reporting(0);

    require"../config/opendb.php";

    $email = mysqli_real_escape_string($conn, $_GET['email']);
    $hash = mysqli_real_escape_string($conn, $_GET['hash']);

    if(filter_var($email, FILTER_VALIDATE_EMAIL)){  // filter the email variable to only be an email type string

        $checkStatement = "SELECT * FROM  `userHash` WHERE `email` = '$email' AND `hash` = '$hash' AND `verified` = '0';";  // Make the SQL statement
        $result = mysqli_query($conn, $checkStatement); // Execute SQL statement

        if($result -> num_rows > 0){  // If a result has been found from the executed SQL statement
            $updateUserHash = "UPDATE `userHash` SET `verified` = '1' WHERE `email` = '$email' AND `hash` = '$hash';";  // Update table
            $updateLogin = "UPDATE `login` SET `verified` = '1' WHERE `email` = '$email' ;";                            // Update table

            mysqli_autocommit($conn, false);  // Turn off Auto Commit

            //    Line 24 is turning off the auto_commit function of MySQL in case there is an error adding any of the information.
            //    Line 30 till 38 is querying the database to check whether the execution of the statement is ok or not.


            mysqli_query($conn, $updateUserHash);
            if(mysqli_error($conn)){
                array_push($error, mysqli_error($conn));
            }

            mysqli_query($conn, $updateLogin);
            if(mysqli_error($conn)){
                array_push($error, mysqli_error($conn));
            }


            // If there are no error, execute the update table SQL Statements
            // After that redirect the user to the login screen
            // else if there is an error, give the user a pop up for stating the error

            if(empty($error)){
                mysqli_commit($conn);
                header("location: ../../login.php");
            }else{
                mysqli_rollback($conn);
                echo "<script>alert('$error');</script>";
            }

        }else{
            ?>
            <html>
                <body>
                    <h1 style="text-align: center">You have already verified your email address. Please login. </h1>
                </body>
            </html>
            <?php
        }
    }else{
        ?>
            <html>
                <body>
                <h1 style="text-align: center"> Error, please check your email again.! </h1>
                </body>
            </html>
        <?php
    }



?>