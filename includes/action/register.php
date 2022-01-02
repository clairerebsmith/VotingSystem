<?php

//    This entire class has been defined so that a new voter can be registered.
//    The function is called registerVoter which has an array and databaseConnection to execute the command.
//    Lines 11 to 26 is retrieving the data from the array to store them into their individual variables.
//    Line 32, 35 and 36 is where PHP is forming a SQL statement to insert data into the respective tables.
//    Line 44 is turning off the auto_commit function of MySQL in case there is an error adding any of the information.
//    Line 48 till 61 is querying the database to check whether the execution of the statement is ok or not.
//    If it is not ok, then store the error in the array defined on line 46


    error_reporting(0);

    class register{
        function registerVoter ($content, $conn) {

            $firstName = $content[0];
            $middleName = $content[1];
            $lastName = $content[2];
            $gender = $content[3];
            $DOB = $content[4];

            $email = $content[5];
            $mobile = $content[6];
            $housePhone = $content[7];

            $houseNumber = $content[8];
            $locationID = $content[9];

            $postcode = $content[10];

            $password = $content[11];


            $hash = md5(time());

            $userInsertStatement = "INSERT INTO `Users` ( `f_name`, `m_name`, `l_name`, `gender` , `DOB`, `housePhone`, `mobile`, `email`, `houseNumber`, `locationID`, `postcode`, `approved`) 
                                    VALUES ('$firstName', '$middleName', '$lastName', '$gender' , '$DOB' ,'$housePhone', '$mobile', '$email', '$houseNumber', '$locationID', '$postcode', '0')";


            $loginInsertStatement = "INSERT INTO `login` (`email`, `password`, `admin`, `verified`) VALUES ( '$email', '$password', '0', '0')";
            $userHashInsertStatement = "INSERT INTO `userHash` (`email`, `hash`, `verified`) VALUES ('$email', '$hash', '0')";

            mysqli_autocommit($conn, false);

            $variable = [];

            mysqli_query($conn, $userInsertStatement);
            if (mysqli_error($conn)){
                array_push($variable, mysqli_error($conn));
            }

            mysqli_query($conn, $loginInsertStatement);
            if (mysqli_error($conn)){
                array_push($variable, mysqli_error($conn));
            }

            mysqli_query($conn, $userHashInsertStatement);
            if (mysqli_error($conn)){
                array_push($variable, mysqli_error($conn));
            }


            if(!empty($variable)){      // If there is an error whilst executing the SQL statements

                mysqli_rollback($conn); // revert the changes to the tables
                return $variable;       // return all of the error to the registerVoter.php Page.

            }else{                      // Else if there is no error
                mysqli_commit($conn);   // Execute the command
                $subject ="Account Verification (Online Voting System)";
                $messageBody = "
                    Hello".$firstName.",
    
                    Thank you for singing up to vote online.
    
                    Just before you can start submitting your documents for you to vote, please click the link below to confirm your email address.
    
                    https://homepages.shu.ac.uk/~b6019531/votingSystem/includes/action/registerComplete.php?email=".$email."&hash=".$hash."
    
                    Thank You!
    
                    Regards
                    Online Voting Team";

                mail($email, $subject, $messageBody);  // Send an email to the voter to verify their email address

                array_push($variable, "success");
                array_push($variable, "Thank you for registering. Kindly check your email inbox and verify your email account. If it is not in your inbox, kindly check the spam folder.");

                return $variable;  // return the above two variables, stored in the array back to registerVoter.php
            }
        }

    }
?>