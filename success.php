<?php
    include "includes/config/validate.php";
    error_reporting(0);

    $validate = new validate();
    $validate -> inactivityLogOut();
    $validate -> confirmVoter();
?>


<html>
    <head>
        <title>Success!</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="includes/css/style.css" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <style>
            img {
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
    </head>

    <body>
        <div style="padding-top: 10%">
            <img style="display: " src="./includes/img/success.png" alt="logOut" width="400" height="400">
            <h1 style="text-align:center;">Thank You for your VOTE!</h1>
            <h2 style="text-align:center;">See you in the next Election!</h2>
            <h3 style="text-align:center;"> <a href="home.php" target="_blank">click here</a> to be redirected to home page for more news.</h3>
        </div>
    </body>
</html>
