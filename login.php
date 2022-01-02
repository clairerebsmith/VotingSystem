<?php
    require "includes/config/validate.php";
    error_reporting(0);

    if (isset($_POST['submit'])) {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $validate = new validate();
        $response = $validate->validate_user($email, $password);


        if (is_null($response)) {
            header("location:home.php");

        } else {
            $response;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

        <link href="includes/css/login.css" type="text/css" rel="stylesheet">
        <link href="includes/css/style.css" rel="stylesheet" type="text/css">
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <!--Enabling and shaping the push to top button-->
        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>

        <?php require_once "template/nav.php"; ?>

        <div class="jumbotron text-center">
            <h1>Login To Start Voting! </h1>
        </div>

        <div id="login" class="container-fluid bg-grey ">
            <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
                <div class="row">
                    <div class="col-sm-5">
                        <p>Please login to cast a vote: </p>
                        <p>If you have not registered, <a href="registerVoter.php" target="_blank">click here</a> to Register.</p>
                    </div>
                    <div class="col-sm-7">
                        <div class="row">
                            <div class="form-group">
                                <label for="email">Registered Email:</label>
                                <input type="text" class="form-control" id="email" placeholder="Enter Your Registered Email" name="email" required=true/>
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password" class="form-control" id="password" placeholder="Enter Password" name="password" required="true"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <input type="submit" name="submit" id="submit" class="btn btn-lg" value="Login"/>
                            </div>
                            <div>
                                <p style="color: red;">
                                    <?php
                                        if (isset($response)) {
                                            echo $response;
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>