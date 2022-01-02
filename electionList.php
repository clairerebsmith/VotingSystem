<?php

    include "includes/config/opendb.php";
    include "includes/config/validate.php";
    error_reporting(0);

    $validate = new validate();
    $validate -> confirmVoter();
    $validate -> inactivityLogOut();

    $voterID = $_SESSION['userID'];
    $adminStatus = $_SESSION['admin'];

    if($adminStatus == '0'){
        $selectVoter = "SELECT * FROM `Users` WHERE ID = '$voterID';";
        $voter = mysqli_query($conn, $selectVoter) -> fetch_assoc();

        if($voter['approved'] == '1'){
            $today = date("Y-m-d H:i:s");
            $voterLocation = $voter['locationID'];
            $selectElections = "SELECT * FROM `electionClass` WHERE `endTime` > '$today' AND (`location` = '$voterLocation' OR `location` = '0');";
            $resultElection = mysqli_query($conn, $selectElections);

            if($resultElection -> num_rows > '0'){

                $electionNames = [];
                $electionVoted = [];

                for($i = 0; $i < $resultElection -> num_rows; ++$i){
                    $election = $resultElection -> fetch_assoc();
                    $electionName = $election['electionName'];



                    array_push($electionNames, $electionName);

                    $checkElection = "SELECT `voterID` FROM `$electionName`;";
                    $checkElections = mysqli_query($conn, $checkElection);



                    $voted = false;
                    for($e = 0; $e < $checkElections -> num_rows; ++ $e){
                        $checkElectionResult = $checkElections -> fetch_assoc();
                        if(password_verify($voterID, $checkElectionResult['voterID']) == true){
                            $voted = true;
                            break 1 ;
                        }
                    }
                    array_push($electionVoted, $voted);
                }

               $electionAvailable = [];
               for($i = 0; $i< count($electionNames); ++$i){
                   if($electionVoted[$i] == false){
                       array_push($electionAvailable, $electionNames[$i]);
                   }
                }
            }else{
                echo "<script>alert('Unfortunately, no elections in your area are scheduled.')</script>";
            }
        }else{
            echo "<script>alert('Unfortunately you have not been approved for voting yet. Please have patience for getting verified')</script>";
        }
    }else{
        echo "<script>alert('Being an Admin, means you are not allowed to vote. Sorry!'); window.location = 'home.php'; </script>";
    }
?>

<html>
    <head>

        <script>
            function voting(){
                window.location = "voting.php";
            }
        </script>
        <title>Election List</title>
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

    <?php require_once "template/nav.php" ?>

    <div class="jumbotron text-center" style="margin-bottom:0px">
        <h1>List of Elections</h1>
        <p>Choose your election...</p>
    </div>

    <div class="container" style="margin-top: 5%">
    <table class="table table-hover">
            <tr>
                <th>Election Name</th>
                <th>Election Type</th>
                <th>Election Start Time</th>
                <th>Election End Time</th>
                <th style="color: white">Select Election</th>
            </tr>
            <?php
                for($i = 0; $i < count($electionAvailable); ++$i){
                    $electionSelect = $electionSelect."`electionName` = '".$electionAvailable[$i]."' OR ";
                }

                $electionSelect = substr($electionSelect, 0,-4);
                $electionListSelect = "SELECT * FROM `electionClass` WHERE $electionSelect;";
                $electionListResult = mysqli_query($conn, $electionListSelect);

                for($i = 0; $i < $electionListResult -> num_rows; ++$i){
                    $electionList = $electionListResult -> fetch_assoc();

                    $startTime = $electionList['startTime'];
                    $explodeStart = explode('-', $startTime);
                    $explodeStartTime = explode(' ', $explodeStart[2]);

                    $concatStartTime = $explodeStartTime[0]."-".$explodeStart[1]."-".$explodeStart[0]." ".$explodeStartTime[1];

                    $endTime = $electionList['endTime'];
                    $explodeEnd = explode('-', $endTime);
                    $explodeEndTime = explode(' ', $explodeEnd[2]);
                    $concatEndTime = $explodeEndTime[0]."-".$explodeEnd[1]."-".$explodeEnd[0]." ".$explodeEndTime[1];


                    echo "<tr>";
                        echo "<td>".$electionList['electionName']."</td>";
                        echo "<td>".$electionList['electionType']."</td>";
                        echo "<td>".$concatStartTime."</td>";
                        echo "<td>".$concatEndTime."</td>";
                        $now = date("Y-m-d H:i:s");
                        if(strtotime($now ) > strtotime($electionList['startTime']) && strtotime($electionList['endTime']) > strtotime($now)){
                            echo "<td>";
                                ?>
                                <form action="voting.php" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" value="<?php echo $electionList['electionName']; ?>" class="btn btn-primary" name="electionName" id="electionName" />
                                    <input type="submit" value = "Vote in Election" class="btn btn-primary" name="voteButton" id="voteButton" onclick="voting()"/>
                                </form>
                                <?php
                            echo "</td>";

                        }else{
                            echo "<td>";
                                echo "<input type='submit' disabled='disabled' class=\"btn btn-primary\" value='vote in Election' /> ";
                            echo "</td>";
                        }
                    echo "</tr>";
                }
            ?>
        </table>
    </div>
    </body>
</html>
