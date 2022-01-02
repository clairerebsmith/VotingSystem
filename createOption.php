<?php
    require_once "includes/config/opendb.php";
    include "includes/config/validate.php";
    error_reporting(0);

    $admin = new validate;
    $admin -> checkAdmin();
    $admin -> inactivityLogOut();

    $error = [];

    if (isset($_POST['basicOptionSubmit'])) {
        $option = mysqli_real_escape_string($conn, $_POST['basicOption']);

        $checkOption = "SELECT * FROM `basicOption` WHERE `Option` LIKE '$option';";
        $checkQuery = mysqli_query($conn, $checkOption);

        if ($checkQuery->num_rows > 0) {
            array_push($error, "Option already exists.");
            echo "<script>document.getElementById('basicOptionForm').style.visibility = 'visible';</script>";
        } else {
            $insertOption = "INSERT INTO `basicOption` (`Option`) VALUES ('$option');";

            mysqli_autocommit($conn, false);

            mysqli_query($conn, $insertOption);

            if (mysqli_error($conn)) {
                mysqli_rollback($conn);
                array_push($error, mysqli_error($conn));
            } else {
                mysqli_commit($conn);
                header("location:admin.php");

            }
        }
    }

    if (isset($_POST['candidateSubmit'])) {
        if (empty($_POST['candidatePartyID'])) {
            array_push($error, "Please make sure that you are selecting the party that the candidate is from.");
        } else {
            $firstName = mysqli_real_escape_string($conn, $_POST['candidateFName']);
            $middleName = mysqli_real_escape_string($conn, $_POST['candidateMName']);
            $lastName = mysqli_real_escape_string($conn, $_POST['candidateLName']);
            $regID = mysqli_real_escape_string($conn, $_POST['candidateID']);
            $partyID = mysqli_real_escape_string($conn, $_POST['candidatePartyID']);

            $checkCandidate = "SELECT * FROM `candidate` WHERE `regID` LIKE '$regID';";
            $checkQuery = mysqli_query($conn, $checkCandidate);

            if ($checkQuery->num_rows > 0) {
                array_push($error, "Please make sure that you are not re-using a candidate registration ID.");
            } else {
                $insertOption = "INSERT INTO `candidate` (`f_name`, `m_name`, `l_name`, `regID`, `partyID`) 
                                 VALUES ('$firstName', '$middleName', '$lastName', '$regID', '$partyID');";

                mysqli_autocommit($conn, false);

                mysqli_query($conn, $insertOption);
                if (mysqli_error($conn)) {
                    mysqli_rollback($conn);
                    array_push($error, mysqli_error($conn));
                } else {
                    mysqli_commit($conn);
                    header("location:admin.php");
                }
            }
        }
    }
?>

<html>
    <head>
        <title>Create Options</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

        <link href="includes/css/style.css" rel="stylesheet" type="text/css">
        <link href="includes/css/createOption.css" rel="stylesheet" type="text/css">

        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

        <script>
            function formDisplay() {
                let formOption = document.getElementById('optionType').value;

                if (formOption == 'basicOption') {
                    document.getElementById('basicOptionForm').style.display = 'inline';
                    document.getElementById('candidateForm').style.display = 'none';
                } else if (formOption == 'candidate') {
                    document.getElementById('candidateForm').style.display = 'inline';
                    document.getElementById('basicOptionForm').style.display = 'none';
                }
            }
        </script>
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>

        <?php include "template/nav.php" ?>
        <div class="jumbotron text-center" style="margin-bottom:0px">
            <h1>Create Options</h1>
            <p>Create options for your election</p>
        </div>

        <div class="container">
            <h2>Create Options</h2>
            <div>
                <label for="optionType">Option Type:</label>
                <select class="styled-select slate" name="optionType" id="optionType" required="required" onchange="formDisplay()">
                    <option value="" selected="selected" disabled="disabled">Please Select</option>
                    <option value="basicOption">Referendum</option>
                    <option value="candidate">Candidate</option>
                </select>
                <p style="color: red;">
                    <?php
                        if (isset($error)) {
                            foreach ($error as $e) {
                                echo $e;
                            }
                        }
                    ?>
                </p>
            </div>

            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <div id="basicOptionForm" style="display: none">
                    <h2>Referendum Option</h2>
                    <div class="form-group">
                        <label for="option">Option</label>
                        <input class="form-control" type="text" name="basicOption" id="basicOption" required="required" value="<?php if (isset($_POST['basicOption'])) { echo $_POST['basicOption']; }; ?>"/>
                    </div>
                    <div class="form-group">
                        <input class="btn btn-primary btn-lg" type="submit" value="Add Option" name="basicOptionSubmit" id="basicOptionSubmit"/>
                    </div>
                </div>
            </form>

            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <div id="candidateForm" style="display: none">
                    <h2>Candidate Information</h2>

                    <div class="form-group">
                        <label for="candidateMName">First Name</label>
                        <input class="form-control" type="text" name="candidateFName" id="candidateFName" value="<?php if (isset($_POST['candidateFName'])) { echo $_POST['candidateFName']; }; ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="candidateMName">Middle Name</label>
                        <input class="form-control" type="text" name="candidateMName" id="candidateMName" value="<?php if (isset($_POST['candidateMName'])) { echo $_POST['candidateMName']; }; ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="candidateLName">Last Name</label>
                        <input class="form-control" type="text" name="candidateLName" id="candidateLName" required="required" value="<?php if (isset($_POST['candidateLName'])) { echo $_POST['candidateLName']; }; ?>"/>
                    </div>

                    <div class="form-group">
                        <label for="candidateID">Registration ID</label>
                        <input class="form-control" type="text" name="candidateID" id="candidateID" required="required"/>
                    </div>

                    <div class="form-group">
                        <label for="candidatePartyID">Party Name</label>
                        <select class="styled-select slate" name="candidatePartyID" id="candidatePartyID" required="required">
                            <option disabled="disabled" selected="selected">Please Select</option>
                            <?php
                                $selectParty = 'SELECT * FROM `politicalParty`;';
                                $result = mysqli_query($conn, $selectParty);

                                for ($i = 0; $i < $result->num_rows; ++$i) {
                                    $party = $result->fetch_assoc();
                                    $partyID = $party['ID'];
                                    $partyName = $party['partyName'];
                                    echo "<option value='$partyID'>$partyName</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <input class="btn btn-primary btn-lg" type="submit" name="candidateSubmit" id="candidateSubmit" value="Add Candidate"/>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
