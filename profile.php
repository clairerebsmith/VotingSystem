<?php
    include "includes/config/opendb.php";
    include "includes/action/userUpdate.php";
    include "includes/config/validate.php";

    $validate = new validate();
    $validate ->confirmVoter();
    $validate -> inactivityLogOut();

    if($_SESSION['admin'] == '1'){
        $validate -> log_user_out();
    }

    $voterID = "";
    if(isset($_SESSION['userID'])){
        $voterID = $_SESSION['userID'];
    }

    $voter = "SELECT * FROM `Users` WHERE `ID` = '$voterID';";
    $voterResult = mysqli_query($conn, $voter)->fetch_assoc();

    $dbLocation = $voterResult['locationID'];

    $voterLocation = "SELECT * FROM `location` WHERE ID = '$dbLocation';";
    $location = mysqli_query($conn, $voterLocation) -> fetch_assoc();

    $voterDoc = "SELECT * FROM `documentation` WHERE `userID` = '$voterID';";
    $voterDocResult = mysqli_query($conn, $voterDoc);

    if (isset($_POST['uploadFile'])) {
        $update = new userUpdate();
        $response = $update->uploadFile($conn);

        if ($response[0] == "success") {
            header("location:profile.php");
        }
    }

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title>My Profile</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

        <link href="includes/css/style.css" rel="stylesheet" type="text/css">
        <link href="includes/css/profile.css" rel="stylesheet" type="text/css">
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <?php require_once "template/nav.php" ?>

        <div class="jumbotron text-center" style="margin-bottom:0px">
            <h1>Profile Page</h1>
            <p>Upload your documents here.</p>
        </div>
        <button onclick="topFunction()" style="border-radius: 50%;" id="myBtn" title="Go to top">Top</button>
        <div>
            <div class="row content">
                <div class="col-sm-4 sidenav">
                    <h2>Address Details </h2>
                    <div class="form-group">
                        <label for="houseNumber">House Number: </label>
                        <input type="text" class="form-control" id="houseNumber" value="<?php echo $voterResult['houseNumber']; ?>" disabled="disabled" />
                    </div>
                    <div class="form-group">
                        <label for="add1">County: </label>
                        <input type="text" class="form-control" id="add1" value="<?php echo $location['County']; ?>" disabled="disabled" />
                    </div>
                    <div class="form-group">
                        <label for="add2">District: </label>
                        <input type="text" class="form-control" id="add2" value="<?php echo $location['District']; ?>" disabled="disabled" />
                    </div>
                    <div class="form-group">
                        <label for="postcode">Postcode: </label>
                        <input type="text" class="form-control" id="postcode" value="<?php echo $voterResult['postcode']; ?>" disabled="disabled" />
                    </div>

                    <h2>Contact Details</h2>

                    <div class="form-group">
                        <label for="housePhone">House Phone Number: </label>
                        <input type="text" class="form-control" id="housePhone" value="<?php echo $voterResult['housePhone']; ?>" disabled="disabled" />
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile Number: </label>
                        <input type="text" class="form-control" id="mobile" value="<?php echo $voterResult['mobile']; ?>" disabled="disabled" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address: </label>
                        <input type="text" class="form-control" id="email" value="<?php echo $voterResult['email']; ?>" disabled="disabled" />
                    </div>
                </div>

                <div class="col-sm-8">
                    <h2>Your Files</h2>
                    <table class="table table-hover">
                        <?php
                            if ($voterDocResult->num_rows > 0) {
                                echo "<tr>";
                                    echo "<th>File Name </th>";
                                    echo "<th>File Type </th>";
                                    echo "<th>File Checked </th>";
                                    echo "<th>Check File </th>";
                                    echo "<th>Preview File </th>";
                                echo "</tr>";
                            }

                            for ($i = 0; $i < $voterDocResult->num_rows; ++$i) {
                                $voterDocResults = $voterDocResult->fetch_assoc();

                                echo "<tr>";
                                    echo "<td>" . $voterDocResults['docName'] . "</td>";
                                    echo "<td>" . $voterDocResults['docType'] . "</td>";

                                    if ($voterDocResults['approved'] == '0') {
                                        echo "<td style='color: red;'>NO</td>";
                                    } else if ($voterDocResults['approved'] == '1') {
                                        echo "<td style='color:green;'>YES</td>";
                                    }

                                    ?>
                                        <td><a href="<?php echo $voterDocResults['docPath']; ?>" download> Download File</a></td>
                                        <td>
                                            <a class="glyphicon glyphicon-eye-open" href="<?php echo $voterDocResults['docPath']; ?>"> Preview</a>
                                            <div class="box">
                                                <iframe src="<?php echo $voterDocResults['docPath']; ?>" width="250px" height="250px"></iframe>
                                            </div>
                                        </td>
                                    <?php
                                echo "</tr>";
                            }
                        ?>
                    </table>

                    <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data">
                        <h3>Upload your Documentations:</h3>
                        <div class="form-group">
                            <label for="file">
                                <input type="file" name="file" id="file"/>
                                <input type="hidden" name="voterID" id="voterID" value="<?php echo $voterID ?>"/>
                            </label>
                            <input type="submit" name="uploadFile" value="Upload Documentation" class="btn btn-primary"/>
                        </div>
                        <p style="color:red;">
                            * Please make sure that the file is one of the following type: PDF, JPEG, JPG or PNG
                        </p>
                    </form>

                    <p style="color:red;">
                        <?php
                            if (isset($response) && $response[0] != 'success') {
                               foreach ($response as $r){
                                   echo $r. "<br>";
                               }
                            }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>