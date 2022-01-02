<?php
    include "includes/config/opendb.php";
    include "includes/config/validate.php";
    $validate = new validate();
    $validate -> checkAdmin();
    $validate -> inactivityLogOut();

    error_reporting(0);

    if(isset($_POST['submitCandidate'])){                                                                                                                                       // If the upload button has been pressed.

        $error =[];                                                                                                                                                             // Initiate an empty array

        $createTemp = "CREATE TABLE `tempCandidate` like `candidate`;";                                                                                                         // Set SQL Command, create a new table called tempCandidate
        mysqli_query($conn, $createTemp);                                                                                                                                       // Execute SQL Command

        if(mysqli_error($conn)){                                                                                                                                                // If there is an error in executing the command
            array_push($error, mysqli_error($conn));                                                                                                                     // Store the error in the initiated error array
        }

        $file = $_FILES["file"]["tmp_name"];                                                                                                                                    // Store temporary file location in variable
        $insertTemp = "LOAD DATA LOCAL INFILE '$file' INTO TABLE `tempCandidate` FIELDS TERMINATED BY ',' ENCLOSED BY '\"' LINES TERMINATED BY '\n' IGNORE 1 LINES";            // SQL Command to load the file and export the data into a tempCandidate Table
        mysqli_query($conn, $insertTemp);                                                                                                                                       // Execute above SQL statement

        if(mysqli_error($conn)){                                                                                                                                                // If error in execution
            array_push($error, mysqli_error($conn));                                                                                                                     // Store the error in the initiated error array
        }

        $selectCandidate = "SELECT * FROM `candidate`;";                                                                                                                        // Set SQL Command select everything from the candidate table
        $candidateResult = mysqli_query($conn, $selectCandidate);                                                                                                               // Execute SQL Command

        $selectTempCandidate = "SELECT * FROM `tempCandidate`;";                                                                                                                // Set SQL command, select everything from the tempCandidate table
        $candidateTempResult = mysqli_query($conn, $selectTempCandidate);                                                                                                       // Execute SQL Command

        $candidateID = [];                                                                                                                                                      // Store all of the CandidateID in an array
        $tempCandidateID = [];                                                                                                                                                  // Store all of the tempCandidateID in an array

        for($i = 0; $i < $candidateResult -> num_rows; ++$i){                                                                                                                   // for loop for all the results found in the candidate table from line 29/30
            $candidate = $candidateResult -> fetch_assoc();                                                                                                                     // fetch all the results
            $candidateID[] = $candidate['regID'];                                                                                                                               // store the result in the array mentioned above
        }

        for($i = 0; $i < $candidateTempResult -> num_rows; ++$i ){                                                                                                              // for loop for all the results found in the tempCandidate table from line 32/33
            $tempCandidate = $candidateTempResult -> fetch_assoc();                                                                                                             // fetch all the results
            $tempCandidateID[] = $tempCandidate['regID'];                                                                                                                       // store the result in the array mentioned above
        }

        $sameRegID = array_intersect($tempCandidateID, $candidateID);                                                                                                           // check if any tempCandidateID exists in the candidateID, and store it in the sameRegID array

        foreach ($sameRegID as $SID){                                                                                                                                           // If there is anything in the sameRegID array, run for loop
            $deleteCandidate = "DELETE FROM `tempCandidate` WHERE `regID` = '$SID'; ";                                                                                          // SET SQL command for deleting that tempCandidateID from the tempCandidate table
            mysqli_query($conn, $deleteCandidate);                                                                                                                              // Execute command
        }

        $moveData = "INSERT  INTO `candidate` SELECT * FROM `tempCandidate`;";                                                                                                  // SET SQL command to copy data from the tempCandidate table to the candidate table
        mysqli_query($conn, $moveData);                                                                                                                                         // Execute Command

        if(!mysqli_error($conn)){                                                                                                                                               // If there is no error in the above SQL command
            $deleteTable = "DROP TABLE `tempCandidate`;";                                                                                                                       // SET SQL command to delete tempCandidate table
            mysqli_query($conn, $deleteTable);                                                                                                                                  // Execute Command
        }
    }
?>

<!--The above PHP code is mainly used to check whether the logged in used is an admin and also to run a inactivityLogout function-->
<!--to make sure it logs the user out after 10 minutes of no activity. The rest of the code is Deepak's individual Enhancement.-->

<!--Below is an HTML code which renders the webpage. The PHP injection on line 86 is to include the navbar to the webpage. -->

<html>
    <head>
        <title>Admin Home: Voting System</title>5
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>
        <link href="includes/css/style.css" rel="stylesheet" type="text/css">
        <script>$("#btnfile").click(function () {$("#uploadFile").click();});</script>
    </head>
    <body>
        <?php require_once "template/nav.php" ?>

        <div class="jumbotron text-center" style="margin-bottom:0px">
            <h1>Admin Page</h1>
        </div>

        <div class="container" style="margin-top: 25px;">
            <div class="col-sm-4">
        <h2>Approving</h2>
        <ul class="list-group">
            <h4><li style="border: none;" class="list-group-item"> Approve Voters: <a href="voterList.php">Approve Voters</a></li></h4>
        </ul>

        <h2>Creating</h2>
        <ul class="list-group">
            <h4> <li style="border: none;" class="list-group-item">Create Option: <a href="createOption.php">Create Option</a></li></h4>
            <h4><li style="border: none;" class="list-group-item">Create Election: <a href="createElection.php">Create Election</a></li></h4>
            <h4><li style="border: none;" class="list-group-item">Create Admin: <a href="createAdmin.php">Create Admin</a></li></h4>
        </ul>

        </div>
            <div style="margin-top: 1%; border-left: black 1px solid " class="col-sm-8">
                <h3>Import Candidate Information Here</h3>
                <p>*only CSV file is Accepted!</p>
                <p>*Make sure all the details in the files are correct, there is possibility of data loss. Pay extra attention to the <strong>regID</strong> before uploading the file.</p>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div>
                        <label for="uploadFile" >Choose your CSV </label>
                        <input type="file" name="file" id="file" accept="text/csv" style="display: inline;" />
                        <input type="submit" name="submitCandidate" id="submitCandidate" value="Upload Information" />
                    </div>
                </form><br><br>
            </div>
        </div>
    </body>
</html>
