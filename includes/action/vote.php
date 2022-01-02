<?php
    include "../config/opendb.php";
    include "../config/validate.php";
    error_reporting(0);


    $selectedOption = $conn -> real_escape_string($_POST['option']);
    $selectedElection = $conn -> real_escape_string($_POST['hiddenElectionName']);

    $passwordOptions = [
        'cost' => 10,
    ];

    $userID = password_hash($_SESSION['userID'], PASSWORD_BCRYPT, $passwordOptions);

    $insertVote = "INSERT INTO `$selectedElection` (`voterID`, `voted`) VALUES ('$userID', '$selectedOption');";
    $vote = mysqli_query($conn, $insertVote);

    if(!mysqli_error($conn)){
        header("location: ../../success.php");
    }else{
        header("location: ../../template/votingError.php");
    }

?>

<!--The above page has been created so that the voter's ID is hashed and then stored in the table. -->
<!--line 7 and 8 are parsing the retrieved string to make sure no malicious data is injected into the db.-->
<!--Line 14 is hashing the voterID using BCRYPT Algorithm.-->
<!--Line 16 and 17 is making sure that the vote is entered into the table-->
<!--Line 19 is checking whether there was an error whilst executing the SQL statement and re-directs the user accordingly-->