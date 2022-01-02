<?php

    require "includes/config/validate.php";
    require "includes/config/opendb.php";
    error_reporting(0);

    $validate = new validate();
    $validate->checkAdmin();
    $validate -> inactivityLogOut();

    $voterList = "  SELECT Users.ID, Users.f_name, Users.m_name, Users.l_name, Users.email, Users.approved, login.admin
                    FROM  `Users` INNER JOIN  `login` ON login.email = Users.email WHERE login.admin =  '0' AND Users.approved = '0';";
    $voterResult = mysqli_query($conn, $voterList);
?>

<!DOCTYPE>
<html>
    <head>
        <title>List of Voters</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>

        <link href="includes/css/style.css" rel="stylesheet" type="text/css">
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>

        <?php require_once "template/nav.php"; ?>

        <div class="jumbotron text-center">
            <h1>USER LIST</h1>
        </div>

        <div class="container">
            <table class="table table-hover">
                <tr>
                    <th>Voter ID</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Approved</th>
                    <th style="color: white;">View Voter</th>
                </tr>

                <tbody>
                    <?php
                        for ($i = 0; $i < $voterResult->num_rows; ++$i) {
                            $voterResults = $voterResult->fetch_assoc();
                            $ID = $voterResults['ID'];
                            echo "<tr>";
                            echo "<td>" . $ID . "</td>";
                            echo "<td>" . $voterResults['f_name'] . "</td>";
                            echo "<td>" . $voterResults['m_name'] . "</td>";
                            echo "<td>" . $voterResults['l_name'] . "</td>";
                            echo "<td>" . $voterResults['email'] . "</td>";

                            if ($voterResults['approved'] == '0') {
                                echo "<td style='color:red;'>NO</td>";
                            } else if ($voterResults['approved'] == '1') {
                                echo "<td style='color:green;'>YES</td>";
                            }
                            echo "<td><a href = 'voter.php?ID=$ID'  name='voterID' name='voterID'>View Voter </a></td>";

                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
