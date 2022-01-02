<?php

//    The below class has been made to run various checks on the user/admin to protect the website from
//    unauthorized access and to ensure that the website performs the way it is supposed to.

    error_reporting(0);
    session_name("votingSystem");
    session_start();

    class validate{

        function validate_user($email, $password){

            require "opendb.php";

            // Making sure the email and password variable do not have malicious code in them

            $email = mysqli_real_escape_string($conn, $email);
            $password = mysqli_real_escape_string($conn, $password);

            // Setting up and executing the MySQL statement

            $select = "SELECT `email`, `password`, `admin`, `verified` FROM `login` WHERE `email` LIKE '$email' ;";
            $row = mysqli_query($conn, $select) -> fetch_assoc();

            // If a result for that query has been found

            if($row){

                // Store required data into variables
                $db_email = $row['email'];
                $db_password = $row['password'];
                $db_verified = $row['verified'];
                $db_admin = $row['admin'];

                // If the voter has verified their email
                if($db_verified == '1'){

                    // Check if the password entered and the password stored in the DB match algorithms
                    if(password_verify($password, $db_password) == true){

                        // If password match -> Select all detail of the user and the execute the SQL command
                        $checkUser = "SELECT * FROM `Users` WHERE `email` LIKE '$email';";
                        $result = mysqli_query($conn, $checkUser) -> fetch_assoc();

                        // Store the necessary information in the Session variables
                        $_SESSION['userID'] = $result['ID'];
                        $_SESSION['status'] = 'authorized';
                        $_SESSION['email'] = $db_email;
                        $_SESSION['admin'] = $db_admin;

                    }else{
                        return "Please provide correct credentials to login.";
                    }
                }else{
                    return "Please make sure that you have verified your email";
                }
            }else{
                return "Your User Profile does not exist. Please register.";
            }
        }

        // Function has been set to run a user log out query, If it is triggered and a session exists,
        // delete all sessions and cookies and re-direct the user to the homepage.

        function log_user_out(){
            if(isset($_SESSION['status'])) {

                unset($_SESSION['status']);
                unset($_SESSION['email']);
                unset($_SESSION['admin']);
                unset($_SESSION['userID']);
                unset($_SESSION['timeout']);
                unset($_COOKIE[session_name()]);

                if(isset($_COOKIE[session_name()])) {

                    setcookie(session_name(), '', time() -86400);
                }
            }
            header("location:home.php");
        }

        // This function is run to check whether the user has an active session and is a valid voter.
        // If the voter does not have an active session, display a popup and log the user out
        // to fully destroy any corrupted cookies/session variables
        function confirmVoter(){
            if( empty($_SESSION['status']) || $_SESSION['status'] !='authorized'  || $_SESSION['status'] == null) {
                echo  "<script>alert('Please login to access this section of the website.')</script>";
                $this -> log_user_out();
            }
        }

        // This function is run on the homepage after the user logs in. It is just to notify the state of their documentation.
        // If they have not submitted any documentation, they are re-directed to the profile page and have to upload something.
        // If they have submitted documentation but are not approved yet, they are notified that it is still in process
        // If the voter has been approved, nothing happens.

        function checkDoc($conn, $userID){
            $return = [];

            // Setup SQL command and execute
            $checkDoc = "SELECT `approved` FROM `documentation` WHERE `userID` = '$userID';";
            $row = mysqli_query($conn, $checkDoc);
            $approved = true;


            // If a result has been found
            if($row -> num_rows > '0' ){

                array_push($return, 'docSubmitTrue');

                // Check if any of the documents have not been approved yet. If a document has not been approved set
                // approved to false
                for( $i = 0; $i < $row -> num_rows; ++$i){
                    $result = $row -> fetch_assoc();
                    if($result['approved'] == '0' ){
                        $approved = false;
                    }
                }

                // If approved is false add 'docApproveFalse to the return array'

                if($approved == false){
                    array_push($return, 'docApproveFalse');
                }

            }else{
                array_push($return, 'docSubmitFalse');
            }

            // return everything back to the homepage.
            return $return;
        }


        // Check if the user logged in, is an admin or not. If it is not an admin, log the user out.
        function checkAdmin(){
            $adminStatus = true;
            if($_SESSION['admin'] != '1'){
                $adminStatus = false;
            }

            if($adminStatus == false){
                $this->log_user_out();
            }
        }


        // This function is run to make sure that the voters dont have an inactive session for to long.
        // The function is only run when a session exists to make sure that users are not re-directed to the logout page
        // unnecessarily. Once the function has been triggered successfully. it works just like the logout function but
        // instead re-directs the user to logout.php page instead. The max inactivity time has been set for 10min which
        // which can be seen from like 158 as 600 seconds is 10 minutes
        function inactivityLogOut(){

            if(isset($_SESSION['status']) && $_SESSION['status'] == 'authorized'){
                $inactive = 600;
                if(!isset($_SESSION['timeout']) ){
                    $_SESSION['timeout'] = time() + $inactive;
                }

                $session_life = time() - $_SESSION['timeout'];

                if($session_life > $inactive) {

                    unset($_SESSION['status']);
                    unset($_SESSION['email']);
                    unset($_SESSION['admin']);
                    unset($_SESSION['userID']);
                    session_destroy(); header("Location:logout.php");

                    unset($_COOKIE[session_name()]);

                }
                $_SESSION['timeout']=time();
            }
        }
    }
?>