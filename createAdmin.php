<?php
    include "includes/config/opendb.php";
    include "includes/config/validate.php";
    error_reporting(0);

//    Check whether user is an admin
//    Run the inactivity logout function in validate.php

    $admin = new validate;
    $admin -> checkAdmin();
    $admin -> inactivityLogOut();

    if(isset($_POST['addAdmin'])){
        if(empty($_POST['registerInput'])){
            $firstName = $conn -> real_escape_string($_POST['firstName']);
            $middleName = $conn -> real_escape_string($_POST['middleName']);
            $lastName = $conn -> real_escape_string($_POST['lastName']);
            $employeeID = $conn ->real_escape_string($_POST['employeeID']);

            $username= $conn -> real_escape_string($_POST['username']);
            $unHashPassword = $conn -> real_escape_string($_POST['password']);

            $passwordOptions = [
                'cost' => 10,
            ];

            $password = password_hash($unHashPassword, PASSWORD_BCRYPT, $passwordOptions);
            $id = uniqid();

            $insertAdmin = "INSERT INTO `Admin` (`ID`, `f_name`, `m_name`, `l_name`, `email`, `employeeID`) 
                            VALUES ('$id', '$firstName', '$middleName', '$lastName', '$username', '$employeeID');";

            $insertLogin = "INSERT INTO `login` ( `email`, `password`, `admin`, `verified`)
                            VALUES ('$username', '$password', '1', '1');";

            $error = [];

            mysqli_autocommit($conn, false);

            mysqli_query($conn, $insertAdmin);
            if(mysqli_error($conn)){
                array_push($error, mysqli_error($conn));
                array_push($error, 'Admin Table Problem');
            }

            mysqli_query($conn, $insertLogin);
            if(mysqli_error($conn)){
                array_push($error, mysqli_error($conn));
                array_push($error, 'Login Table Problem');
            }

            if(empty($error)){
                mysqli_commit($conn);
                header("location:admin.php");
            }else{
                mysqli_rollback($conn);
            }

        }
    }
?>

<html>
    <head>
        <title>Add Admin</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

        <link href="includes/css/voter.css" rel="stylesheet" type="text/css">
        <link href="includes/css/style.css" rel="stylesheet" type="text/css">


        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>


        <script>
//              The functions below are two check whether a username exists and to verify whether the admin who is being
//              created is using their own employee ID.
//              JS is constant checking whether anything has been entered in either the username field or the employeeID field.
//              If the user has enetered something, send that data across to emailCheck for verifying whether a username is available
//              or checkEmpID to check whether the employeeID alread exists in the table (this one has been added to reduce human error)
            $(document).ready(function(){
                $('#username').on("keyup input", function(){
                    var inputVal = $(this).val();
                    $.post( "template/emailCheck.php", { email:inputVal }, function (data){
                        if(data){
                            $("#registerAvailable").css({'color':'red', 'font-weight':'bold'});  // Set text color to red and bold the text
                            $("#registerAvailable").html(data);
                            $("#registerInput").html(data);

                        }else{
                            $("#registerAvailable").html(data);
                        }
                    });
                });

                $('#employeeID').on("keyup input", function(){
                    var empID = $(this).val();
                    $.post( "template/checkEmpID.php", { empID:empID }, function (data){
                        if(data){
                            $("#registerEmp").css({'color':'red', 'font-weight':'bold'});       // Set text color to red and bold the text
                            $("#registerEmp").html(data);
                            $("#registerInput").html(data);

                        }else{
                            $("#registerEmp").html(data);
                        }
                    });
                });

            });
        </script>
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
    <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>


    <?php include "template/nav.php" // Include navbar file ?>
    <div class="jumbotron text-center" style="margin-bottom:0px">
        <h1>Add Admin</h1>
    </div>

    <body>
        <div style="margin-top: 10px;" class="container">
<!--                Send form to the same page that is currently running without refreshing the page.-->
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-group">
                    <label for="firstName">First Name:</label>
                    <input type="text" class="form-control" id="firstName" name="firstName" required="required" value="<?php  if(isset($_POST['firstName'])){echo $_POST['firstName']; } ?>" />
                </div>
                <div class="form-group">
                    <label for="middleName">Middle Name:</label>
                    <input type="text" class="form-control" id="middleName" name="middleName" value="<?php  if(isset($_POST['middleName'])){echo $_POST['middleName']; } ?>" />
                </div>
                <div class="form-group">
                    <label for="lastName">Last Name:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" required="required"  value="<?php  if(isset($_POST['lastName'])){echo $_POST['lastName']; } ?>" />
                </div>
                <div class="form-group">
                    <label for="username">UserName:</label>
                    <input type="text" class="form-control" id="username" name="username" required="required" value="<?php  if(isset($_POST['username'])){echo $_POST['username']; } ?>" />
                    <p id="registerAvailable"></p>
                    <input type="hidden" class="form-control" id="registerInput" name="registerInput" />
                </div>


                <div class="form-group">
                    <label for="employeeID">Employee ID:</label>
                    <input type="text" class="form-control" id="employeeID" name="employeeID" required="required" value="<?php  if(isset($_POST['employeeID'])){echo $_POST['employeeID']; } ?>" />
                    <p id="registerEmp"></p>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password"  title="Password must be 8 characters including 1 uppercase letter, 1 lowercase letter and numeric characters"
                           pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required="required"  />
                </div>

                <input type="submit" class="btn btn-primary" value="Add Admin" name="addAdmin" id="addAdmin" />
                <p style="color: red;">
                    <?php

                        // If an error exists, display it here. For each error that exists, display it with two line breaks.
                        if(isset($error)){
                            foreach ($error as $e){
                                echo $e."<br><br>";
                            }

                        }
                    ?>
                </p>
            </form>
        </div>
    </body>
</html>
