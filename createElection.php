<?php
    include "includes/config/opendb.php";
    include "includes/config/validate.php";
    error_reporting(0);

    $admin = new validate;
    $admin -> checkAdmin();
    $admin -> inactivityLogOut();


    if (isset($_POST['addElection'])) {
        $db_option = [];

        $type = mysqli_real_escape_string($conn, $_POST['electionType']);


        if($type == 'local'){
            $location = mysqli_real_escape_string($conn, $_POST['selectDistrict']);
        }else{
            $location = "0";
        }

        $electionName = mysqli_real_escape_string($conn, $_POST['electionName']);
        $electionStart = mysqli_real_escape_string($conn, $_POST['startDate']);
        $electionEnd = mysqli_real_escape_string($conn, $_POST['endDate']);

        $selectedOption = $_POST['selectOption'];

        if (empty($selectedOption)) {
            $error = "Please Select Options";
        } else if (count($selectedOption) < '2') {

            $error = "Please make sure that <strong>MORE</strong> than <strong>ONE</strong> option has been selected";
        } else {

            if ($selectedOption) {
                foreach ($selectedOption as $so) {
                    $electionOption = $electionOption . ", " . $so;
                }
            }
            $electionOption = substr($electionOption, 2);
            $electionOption = mysqli_real_escape_string($conn, $electionOption);

            $electionAdd = "INSERT INTO `electionClass` (`electionType`, `location`, `electionName`, `startTime`, `endTime`, `availableOptions`)
                            values ('$type', '$location', '$electionName', '$electionStart', '$electionEnd', '$electionOption');";

            $createElection = "CREATE TABLE `$electionName` (ID INT NOT NULL AUTO_INCREMENT, voterID VARCHAR(512) NOT NULL UNIQUE , voted INT NOT NULL, PRIMARY KEY(ID));";

            mysqli_autocommit($conn, false);

            mysqli_query($conn, $electionAdd);

            if (mysqli_error($conn)) {
                mysqli_rollback($conn);
            } else {
                mysqli_commit($conn);
                mysqli_query($conn, $createElection);
                mysqli_commit($conn);
                header("location: admin.php");
            }
        }
    }
?>

<html>
    <head>
        <title>Create Election</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="includes/css/style.css" type="text/css" rel="stylesheet">
        <link href="includes/css/createElection.css" type="text/css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>

        <script>
            function showCounty() {
                let typeValue = document.getElementById("electionType").value;

                $.post("template/selectOption.php", {type: typeValue}, function (option) {
                    if (option) {

                        $("#selectOption").html(option);
                    }
                });

                document.getElementById("selectOption").style.display = "inline";
                document.getElementById("labelSelectOption").style.display = "inline";


                if (typeValue == 'local') {
                    document.getElementById("selectCounty").style.visibility = "visible";
                } else {
                    document.getElementById("selectCounty").style.visibility = "hidden";
                    document.getElementById("selectCounty").selectedIndex = "0";
                    document.getElementById("selectDistrict").style.visibility = "hidden";
                    document.getElementById("selectDistrict").selectedIndex = "0";
                }
            }

            function showCity() {
                let typeValue = document.getElementById("electionType").value;
                let countyValue = document.getElementById("selectCounty").value;

                $.post("template/showCity.php", {county: countyValue}, function (data) {
                    if (data) {
                        $("#selectDistrict").html(data);
                    }
                });

                if (typeValue == 'local') {
                    document.getElementById("selectDistrict").style.visibility = "visible";
                    document.getElementById("selectDistrict").required = true;
                    document.getElementById("selectCounty").required = true;
                } else {
                    document.getElementById("selectDistrict").style.visibility = "hidden";
                    document.getElementById("selectDistrict").selectedIndex = "0";
                }
            }
        </script>
    </head>
    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>

        <?php require_once "template/nav.php" ?>

        <div class="jumbotron text-center">
            <h1>Election Management System</h1>
            <p>This page lets you to create a new election!</p>
        </div>

        <div class="container">
            <form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
                <div class="form-group">
                    <label for="electionType">Election Type: </label>
                    <select class="styled-select slate" name="electionType" required='required' id='electionType' onchange="showCounty()">
                        <option class="styled-select slate" selected="true" disabled>Please Select</option>
                        <option value="referendum">Referendum</option>
                        <option value="general">General</option>
                        <option value="local">Local</option>
                    </select>

                    <select class="styled-select slate" name="selectCounty" id='selectCounty' style="visibility: hidden" onchange="showCity()">
                        <option selected="true" disabled>Please Select</option>
                        <?php
                            $selectCounty = "SELECT DISTINCT `County` FROM `location`;";
                            $county = mysqli_query($conn, $selectCounty);

                            for ($i = 0; $i < $county->num_rows; ++$i) {
                                $countyResult = $county->fetch_assoc();
                                $countyName = $countyResult['County'];

                                if ($countyName) {
                                    echo "<option value='$countyName'>$countyName</option>";
                                }
                            }
                        ?>
                    </select>

                    <select class="styled-select slate" name="selectDistrict" id='selectDistrict' style="visibility: hidden">
                    </select>

                    <p style = "color:red;">
                        <?php
                            if(isset($selectError)){
                                echo $selectError;
                            }
                        ?>
                    </p>
                </div>

                <div class="form-group">
                    <label id='labelSelectOption' style="display: none" for="selectOption">Election Option: <p style="color:red ;font-weight: normal">* USE CMD or CTRL to select MULTIPLE rows</p></label>
                    <select name="selectOption[]" id='selectOption' class="multiselect-ui form-control" multiple = "multiple" style="display: none"></select>
                    <p style="color:red;">
                        <?php
                            if (isset($error)) {
                                echo $error;
                            }
                        ?>
                    </p>
                </div>

                <div class="form-group">
                    <label for="electionName">Election Name: </label>
                    <input type="text" class="form-control" name='electionName' id="electionName" require='required' value="<?php if (isset($_POST['electionName'])) {echo $_POST['electionName'];} ?>"/>
                </div>

                <div class="form-group">
                    <label for="startDate">Start Date: </label>
                    <input type="datetime-local" class="form-control" name='startDate' id="startDate" require='required' value="<?php if (isset($_POST['startDate'])) { echo $_POST['startDate']; } ?>"/>
                </div>

                <div class="form-group">
                    <label for="endDate">End Date: </label>
                    <input type="datetime-local" class="form-control" name='endDate' id="endDate" require='required' value="<?php if (isset($_POST['endDate'])) { echo $_POST['endDate']; } ?>"/>
                </div>

                <div class="form-group">
                    <input type="submit" name="addElection" id="addElection" value="Add Election" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </body>
</html>