<?php
    session_name("votingSystem");
    session_start();

    require "includes/config/validate.php";
    require "includes/config/opendb.php";

    $validate = new validate();
    $validate -> inactivityLogOut();

    if (isset($_GET['status']) && $_GET['status'] == 'logout') {
        $validate->log_user_out();
    }

    if ($_SESSION['status'] == 'authorized') {
        if(is_int($_SESSION['userID']) == true) {
            $response = $validate->checkDoc($conn, $_SESSION['userID']);

            if ($response[0] == 'docSubmitTrue') {
                if ($response[1] == 'docApproveFalse') {
                    echo "<script>alert('Your files are still under review. Please wait till you are approved'); </script>";
                }
            }

            if ($response[0] == 'docSubmitFalse') {
                echo "<script>alert('Please submit your documents to be approved'); window.location.href='profile.php'; </script>";
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Welcome to Our Voting Website</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

        <link href="includes/css/home.css" rel="stylesheet" type="text/css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
        <link href="includes/css/style.css" rel="stylesheet" type="text/css">
    </head>

    <!--Enabling and shaping the push to top button-->
    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>

        <?php require_once "template/nav.php" ?>

        <div class="jumbotron text-center" style="margin-bottom:0px">
            <h1>Welcome to Our Voting System</h1>
            <p>Where we revolutionized voting!</p>
        </div>

        <div class="container-fluid bg-grey">
            <div class="row">
                <div class="col-sm-4">
                    <span class="image-centered glyphicon glyphicon-info-sign logo slideanim"></span>
                </div>
                <div class="col-sm-8">
                    <h2>Read More About This Election</h2><br>
                    <h4><strong>CASTING TIMES:</strong> Online Election is available from everywhere in the world as long as the
                        Election is running and you are approved. So cast your VOTE!</h4><br>
                    <p><strong>JUST TO LET YOU KNOW: </strong>You only need to register once - you do not need to register
                        separately for every election.
                        You must verify again if you’ve changed address, name or nationality.
                </div>
            </div>
        </div>

        <!-- Container (Services Section) -->
        <div id="votingWay" class="container-fluid text-center">
            <h2>HOW TO VOTE</h2>
            <h4>SIMPLY FOLLOW THESE STEPS </h4>
            <br>
            <div class="row slideanim">
                <div class="col-sm-2">
                    <span class="glyphicon glyphicon-circle-arrow-right logo-small"></span>
                    <h4>1</h4>
                    <p>Login or Register</p>
                </div>
                <div class="col-sm-2">
                    <span class="glyphicon glyphicon-circle-arrow-right logo-small"></span>
                    <h4>2</h4>
                    <p>Make Sure You Are Eligible</p>
                </div>
                <div class="col-sm-2">
                    <span class="glyphicon glyphicon-circle-arrow-right logo-small"></span>
                    <h4>3</h4>
                    <p>Choose Your Candidate</p>
                </div>
                <div class="col-sm-2">
                    <span class="glyphicon glyphicon-circle-arrow-right logo-small"></span>
                    <h4>4</h4>
                    <p>Submit Your Vote</p>
                </div>
                <div class="col-sm-4">
                    <span class="glyphicon glyphicon-ok-sign logo-small"></span>
                    <h4>5</h4>
                    <h2>Thank You For Your Vote !</h2>
                </div>
            </div>
            <br><br>
        </div>

        <br>

        <h2>SOME OF TWEETS RELATED TO THIS ELECTION: </h2>
        <div id="news" class="carousel slide text-center" data-ride="carousel">
            <a class="twitter-timeline" data-width="300" data-height="700" data-theme="light" data-link-color="#2B7BB9" href="https://twitter.com/realDonaldTrump?ref_src=twsrc%5Etfw"><a>
            <a class="twitter-timeline" data-width="300" data-height="700" data-theme="light" data-link-color="#2B7BB9" href="https://twitter.com/govuk?ref_src=twsrc%5Etfw"><a>
            <a class="twitter-timeline" data-width="300" data-height="700" data-theme="light" data-link-color="#2B7BB9" href="https://twitter.com/UN?ref_src=twsrc%5Etfw"></a>
            <a class="twitter-timeline" data-width="300" data-height="700" data-theme="light" data-link-color="#2B7BB9" href="https://twitter.com/usa?ref_src=twsrc%5Etfw"></a>
            <a class="twitter-timeline" data-width="300" data-height="700" data-theme="light" data-link-color="#2B7BB9" href="https://twitter.com/BBCNews?ref_src=twsrc%5Etfw"></a>
        </div>

        <div id="choosing" class="container-fluid">
            <div class="text-center">
                <h2>Select Your Status</h2>
                <h4>Please choose one the following options </h4>
            </div>
            <div class="row slideanim">
                <div class="col-sm-6 col-xs-12">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h1>New USER?</h1>
                        </div>
                        <div class="panel-body para-user">
                            <h2>You Need to: </h2>
                            <ul class="list-centered">
                                <li>Register</li>
                                <li>Verify Registration</li>
                                <li>Upload Documents</li>
                                <li>Wait to be Verified</li>
                                <li>Once Approved</li>
                                <li>Happy Voting!</li>
                            </ul>
                        </div>
                        <form action="registerVoter.php">
                            <div class="panel-footer">
                                <h3>Ready to Register?</h3>
                                <h4>Click The Button Below: </h4>
                                <button class="btn btn-lg">Sign Up</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-12">
                    <div class="panel panel-default text-center">
                        <div class="panel-heading">
                            <h1>Existing USER? </h1>
                        </div>
                        <div class="panel-body para-user">
                            <h2>You Need to:</h2>
                            <ul class="list-centered">
                                <li>Login</li>
                                <li>Make sure you are approved</li>
                                <li>Happy Voting!</li>
                            </ul>

                        </div>
                        <form action="login.php">
                            <div class="panel-footer">
                                <h3>Ready to Vote?</h3>
                                <h4>Click The Button Below: </h4>
                                <button class="btn btn-lg">Login</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container (Contact Section) -->
        <div id="contact" class="container-fluid bg-grey">
            <h2 class="text-center">CONTACT</h2>
            <div class="row">
                <div class="col-sm-5">
                    <p>Contact us and we'll get back to you within 24 hours.</p>
                    <p><span class="glyphicon glyphicon-map-marker"></span> Sheffield, UK</p>
                    <p><span class="glyphicon glyphicon-phone"></span> +44 1515151515</p>
                    <p><span class="glyphicon glyphicon-envelope"></span> hallam@hallam.com</p>
                </div>
                <div class="col-sm-7 slideanim">
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
                        </div>
                        <div class="col-sm-6 form-group">
                            <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
                        </div>
                    </div>
                    <textarea class="form-control" id="comments" name="comments" placeholder="Comment" rows="5"></textarea><br>
                    <div class="row">
                        <div class="col-sm-12 form-group">
                            <button class="btn btn-default pull-right" type="submit">Send</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

