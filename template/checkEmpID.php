<?php
    include "../includes/config/validate.php";
    require "../includes/config/opendb.php";

    if (! empty($_POST['empID'])) {

        $empID = $conn -> real_escape_string($_POST['empID']);  // Parsing the information to make sure
                                                                // no SQL injection occurs.

        // Setting SQL statement and executing it.

        $sql = "SELECT `employeeID` FROM `Admin` WHERE `employeeID` LIKE '$empID'";
        $result = mysqli_query($conn, $sql);

            if ($result -> num_rows != 0) {
                echo "Please use your own employee ID";
            }
    } else {
        echo 'Please enter employee ID';
    }
?>

<!--The above code has been written to check whether a pre-existing Admin with same Employee ID -->
<!--Already exists ot not. If the value is empty, the strong returned is "Please enter employee ID". If the -->
<!--employeeID already exists, it returns "Please use your own employeeID. -->
