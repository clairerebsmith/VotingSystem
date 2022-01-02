<?php

    include "includes/config/opendb.php";
    include "includes/config/validate.php";
    error_reporting(0);

    $admin = new validate;
    $admin -> checkAdmin();
    $admin -> inactivityLogOut();

    $ID = $_GET['ID'];
    $ID = $conn -> real_escape_string($ID);

    $voter = "SELECT * FROM `Users` WHERE ID = '$ID';";
    $voterResult = mysqli_query($conn, $voter)->fetch_assoc();

    $dbLocation = $voterResult['locationID'];

    $voterLocation = "SELECT * FROM `location` WHERE ID = '$dbLocation';";
    $location = mysqli_query($conn, $voterLocation) -> fetch_assoc();


    if (isset($_POST['submit'])) {
        $db_process = $_POST['process'];
        $db_ID = $_POST['docID'];

        if ($db_process == 'Checked') {
            $updateDoc = "UPDATE `documentation` set `approved` = '1' WHERE `ID` = '$db_ID';";
            mysqli_query($conn, $updateDoc);
            header("Refresh:0");
        } else if ($db_process == 'Not Checked') {
            $updateDoc = "UPDATE `documentation` set `approved` = '0' WHERE `ID` = '$db_ID';";
            mysqli_query($conn, $updateDoc);
            header("Refresh:0");
        }
    }

    if (isset($_POST['approveUser'])) {
        $updateUser = "UPDATE `Users` set approved = '1' WHERE ID = '$ID';";
        mysqli_query($conn, $updateUser);
        header("location: voterList.php");

        $subject = "Success: Congratulations you are ready to vote !";
        $email = $voterResult['email'];

        $message =  " Dear Voter,
        
                       This email is to let you know that all your documents have been reviewed and we are delighted to let you know that you
                       have been approved for voting online.
                       
                       Please login to the online voting portal.
                       
                       https://homepages.shu.ac.uk/~b6019531/votingSystem/home.php
                       
                       Kind regards,
                       Document verification department (Online Voting System)
                       
                    ";

        mail($email, $subject, $message);
    }

    if(isset($_POST['rejectVoter'])){

        $email = $voterResult['email'];
        $subject ="Online Voting System Rejection: Your immediate attention is required ! ";
        $messageBody =  "
                        Dear Voter
                        
                        Thank you for your application for your voting ID.
                        
                        We received a large number of applications, and after carefully reviewing all of them, unfortunately, we have to inform you that your submitted documents are not eligible for voting or we have found a problem with them.
                        Please call us to rectify this issue or go to your nearest voting campaign for our team to asses your document and to verify you or explain to you what went wrong.
                        
                        Kind regards,
                        Document verification department (Online Voting System)
                        ";
        mail($email, $subject, $messageBody);

        $updateVoter = "UPDATE `Users` set `approved` = '-1' WHERE `ID` = '$ID';";
        mysqli_query($conn, $updateVoter);
        header("location:voterList.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Approve Voter</title>
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
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>

        <?php include "template/nav.php" ?>

        <div class="jumbotron text-center" style="margin-bottom:0px">
            <h1>Voter Profile</h1>
        </div>

        <div class="container-fluid">
            <div class="row content">
                <div class="col-sm-4 sidenav">
                    <h1>Voter Details</h1>
                    <table class="table">
                        <tr>
                            <th>First Name:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $voterResult['f_name'] ?>"/></td>
                        </tr>
                        <tr>
                            <th>Middle Name:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $voterResult['m_name'] ?>"/></td>
                        </tr>
                        <tr>
                            <th>Last Name:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $voterResult['l_name'] ?>"/></td>
                        </tr>
                        <tr>
                            <th>House Number:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $voterResult['houseNumber'] ?>"/></td>
                        </tr>
                        <tr>
                            <th>County:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $location['County'] ?>"/></td>
                        </tr>
                        <tr>
                            <th>District:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $location['District'] ?>"/></td>
                        </tr>
                        <tr>
                            <th>Postcode:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $voterResult['postcode'] ?>"/></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><input type="text" disabled="disabled" value=" <?php echo $voterResult['email'] ?>"/></td>
                        </tr>
                    </table>
                </div>

                <div class="col-sm-8">
                    <h2>Your Files</h2>
                    <table class="table table-hover">
                        <tr>
                            <th>Document ID</th>
                            <th>Document Name</th>
                            <th>Document Type</th>
                            <th>Check Status</th>
                            <th style="color: white;">View File</th>
                            <th style="color: white;">Approved?</th>
                        </tr>

                        <tbody>
                            <?php
                                $approveUserShow = 'hide';
                                $rejectShow = 'hide';

                                $voterDoc = "SELECT * FROM `documentation` WHERE `userID`  = '$ID';";
                                $voterDocResult = mysqli_query($conn, $voterDoc);

                                for ($i = 0; $i < $voterDocResult->num_rows; ++$i) {
                                    $voterDocResults = $voterDocResult->fetch_assoc();

                                    if ($voterDocResults['approved'] == '1') {
                                        $approveUserShow = 'show';
                                        $rejectShow = 'show';
                                    }

                                    if ($voterResult['approved'] == '0') {
                                        $approveDocShow = 'show';
                                    } else  {
                                        $approveDocShow = 'hide';
                                        $approveUserShow = 'hide';
                                        $rejectShow = 'hide';

                                    }

                                    echo "<tr>";
                                        $ID = $voterDocResults['ID'];
                                        echo "<td>" . $ID . "</td>";
                                        echo "<td>" . $voterDocResults['docName'] . "</td>";
                                        echo "<td>" . $voterDocResults['docType'] . "</td>";

                                        if ($voterDocResults['approved'] == '0') {
                                            echo "<td style='color:red'>NO</td>";
                                            $process = 'Checked';
                                        } else if ($voterDocResults['approved'] == '1') {
                                            echo "<td style='color:green'>YES</td>";
                                            $process = 'Not Checked';
                                        }
                                        ?> <td><a href="<?php echo $voterDocResults['docPath']; ?>" download> View File</a></td> <?php

                                        echo "<td><form method='POST' action = ''>";
                                            echo "<input type='hidden' name='docID' value='$ID' />";
                                            echo "<input type='hidden' name = 'process' value='$process' />";
                                            echo "<input type = 'submit' name = 'submit' id='approveButton' class ='$approveDocShow' value = '$process' />  ";
                                        echo "</td></form>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                    <p>
                        <form action="" method="POST">
                            <input type='submit' name='approveUser' value='Approve Voter' id="approveVoter" class='<?php echo $approveUserShow ?>'/>
                            <input type='submit' name='rejectVoter' value='Reject Voter' id="rejectVoter" class='<?php echo$rejectShow ?>'/>
                        </form>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>