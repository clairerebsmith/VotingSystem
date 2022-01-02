<!--forcing the CSS to overwrite the bootstrap-->
<style>
    img {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>
<!--Adding all the required libraries and CSS-->
<head>


    <title>404</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link href="includes/css/style.css" rel="stylesheet" type="text/css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>


</head>


<div class="container">
    <h1><img src="./includes/img/404.png" alt="500" width="700" height="500">
        <?php
        if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
            $refuri = parse_url($_SERVER['HTTP_REFERER']); // use the parse_url() function to create an array containing information about the domain
            if ($refuri['host'] == "localhost") {           //checking how the browser has come to this page

                echo "<p><a href=\"home.php\" >Click Here</a> to get redirected to Home.</p>";
            } else {
                echo "<p><a href=\"home.php\">Click Here</a> to get redirected to Home.</p>";
            }
        } else {
            echo "<h2>Our System has detected that you have typed this URL manually!<br>Please try to access the website via the links provided. <h2><h3><a href=\"home.php\">Click Here</a> to get redirected to Home.</h3>";
        }
        ?>

</div>