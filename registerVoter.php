<?php

//  The below form is sending all of the information to the registerVoter function
//  inside the register has been commented. All the individual information pieces are stored in an array after being
//  Parsed through the mysqli_real_escape string function that would ensure a minimum risk of SQL injection
//  The data is send back from the function and the website acts accordingly.

    include "includes/config/opendb.php";
    include "includes/action/register.php";
    error_reporting(0);

    if (isset($_POST['submit'])) {
        if ($_POST['password'] === $_POST['password_con']) {

            $content = [];

            $unHashPassword = mysqli_real_escape_string($conn, $_POST['password']);

            $passwordOptions = [
                'cost' => 10,
            ];

            $password = password_hash($unHashPassword, PASSWORD_BCRYPT, $passwordOptions);

            array_push($content, mysqli_real_escape_string($conn, $_POST['firstName']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['middleName']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['lastName']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['gender']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['DOB']));

            array_push($content, mysqli_real_escape_string($conn, $_POST['email']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['mobile']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['housePhone']));

            array_push($content, mysqli_real_escape_string($conn, $_POST['houseNumber']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['selectDistrict']));
            array_push($content, mysqli_real_escape_string($conn, $_POST['postcode']));

            array_push($content, $password);

            $register = new register();

            $result = $register->registerVoter($content, $conn);

            if ($result[0] == "success") {  // if registration was successful

                echo "<script> alert('$result[1]'); window.location.href='home.php'; </script> "; // pop up box for the user to know the registration was a success and the new voter gets redirected to homepage.
            }

        } else {
            echo "<script>alert('Your passwords are not matching. Please complete the form and try again. ')</script>";
            $result[0] = "Please make sure that the password match.";
            $result[1] = "active";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register Voter</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">

        <link href="includes/css/registerVoter.css" rel="stylesheet" type="text/css">
        <link href="includes/css/style.css" rel="stylesheet" type="text/css">


        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="includes/js/registerVoter.js"></script>
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>

        <script>
            function showCity(){
                let county = document.getElementById("county").value;

                $.post("template/showCity.php", {county: county}, function (data) {
                    if (data) {
                        $("#selectDistrict").html(data);
                    }
                });
            }
        </script>

    </head>
    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>
        <?php require_once "template/nav.php"; ?>

        <div class="jumbotron text-center">
            <h1>Register To Start Voting! </h1>
        </div>

        <form id="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
            <div id="register" style="overflow: hidden" class="container-fluid bg-grey ">
                <div id="Tab" class="container">
                    <div class="tab-content ">
                        <div class="tab-pane active"  id="1">
                            <h1>Personal Details</h1>
                            <div class="form-group pd-item-required">
                                <label for="firstName">First Name:</label>
                                <input type="text" class="form-control" id="firstName" placeholder="Enter First Name" name="firstName" value="<?php if (isset($_POST['firstName'])) { echo $_POST['firstName']; } ?>" required/>
                            </div>

                            <div class="form-group">
                                <label for="middleName">Middle Name:</label>
                                <input type="text" class="form-control" id="middleName" placeholder="Enter Middle Name" value="<?php if (isset($_POST['middleName'])) { echo $_POST['middleName']; } ?>" name="middleName"/>
                            </div>

                            <div class="form-group pd-item-required">
                                <label for="lastName">Last Name:</label>
                                <input type="text" class="form-control" id="lastName" placeholder="Enter Last Name" name="lastName" value="<?php if (isset($_POST['lastName'])) { echo $_POST['lastName']; } ?>" required/>
                            </div>

                            <div class="form-group pd-item-required">
                                <label for="gender">Gender:</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value disabled selected>Please Select</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                    <option value="none">Prefer not to say</option>
                                </select>
                            </div>

                            <div class="form-group pd-item-required">
                                <label for="DOB">Date of Birth:</label>
                                <input type="date" class="form-control" id="DOB" name="DOB" value="<?php if (isset($_POST['DOB'])) { echo $_POST['DOB']; } ?>" required/>
                                <p id="DOBOver"></p>
                            </div>

                            <input type="submit" value="Next" onclick="nextView()" class="btn btn-lg"/>
                        </div>

                        <div class="tab-pane" id="2">
                            <h1>Address Details </h1>
                            <div class="form-group ad-item-required">
                                <label for="houseNumber">House Number:</label>
                                <input type="text" class="form-control" id="houseNumber" placeholder="Enter House Number" name="houseNumber" value="<?php if (isset($_POST['houseNumber'])) { echo $_POST['houseNumber']; } ?>" required/>
                            </div>

                            <div class="form-group ad-item-required">
                                <label for="county">County:</label>
                                <select required="required" class="form-control" id="county" onchange="showCity()">
                                    <option disabled="disabled" selected="selected">Please Select</option>
                                    <?php
                                        $selectCounty = "SELECT DISTINCT `County` from `location` WHERE `County` IS NOT NULL;";
                                        $county = mysqli_query($conn, $selectCounty);

                                        for($i = 0; $i < $county -> num_rows; ++$i){
                                            $countyResult = $county -> fetch_assoc();
                                            $countyName = $countyResult['County'];

                                            echo "<option value='$countyName'>$countyName</option>";
                                        }

                                    ?>
                                </select>
                            </div>

                            <div class="form-group ad-item-required">
                                <label for="selectDistrict">District:</label>
                                <select required="required" class="form-control" id="selectDistrict" name="selectDistrict" ></select>
                            </div>

                            <div class="form-group ad-item-required">
                                <label for="postCode">Post Code:</label>
                                <input type="text" class="form-control" id="postcode" placeholder="Enter Post Code" name="postcode" value="<?php if (isset($_POST['postcode'])) { echo $_POST['postcode']; } ?>" required/>
                            </div>

                            <input type="submit" value="Previous" onclick="lastView()" class="btn btn-lg"/>
                            <input type="submit" value="Next" onclick="nextView()" class="btn btn-lg"/>
                        </div>

                        <div class="tab-pane" id="3">
                            <h1>Contact Details </h1>
                            <div class="form-group">
                                <label for="housePhone">House Phone Number:</label>
                                <input type="text" class="form-control" id="housePhone" placeholder="Enter House Tel Number" value="<?php if (isset($_POST['housePhone'])) { echo $_POST['housePhone']; } ?>" name="housePhone"/>
                            </div>

                            <div class="form-group">
                                <label for="mobile">Mobile Number:</label>
                                <input type="text" class="form-control" id="mobile" placeholder="Enter Mobile Number" name="mobile" value="<?php if (isset($_POST['mobile'])) { echo $_POST['mobile']; } ?>" required/>
                            </div>

                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" placeholder="Enter Email" name="email" value="<?php if (isset($_POST['email'])) {  echo $_POST['email']; } ?>" required/>
                                <p id="registerAvailable"></p>
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" title="Password must be 8 characters including 1 uppercase letter, 1 lowercase letter and numeric characters"  pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" required/>
                            </div>

                            <div class="form-group">
                                <label for="password_con">Confirm Password:</label>
                                <input type="password" class="form-control" id="password_con" placeholder="Re-Enter Password" name="password_con" required/>
                            </div>

                            <input type="submit" value="Previous" onclick="lastView()" class="btn btn-lg"/>
                            <input type="submit" name="submit" id="submit" value="Register" class="btn btn-lg"/>

                            <div class="form-group">
                                <p style="color:red;">
                                    <?php
                                    if (isset($result[0]) && $result[0] != "success") {
                                        echo $result[0];
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </body>
</html>