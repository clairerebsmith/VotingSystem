<?php

    include "includes/config/opendb.php";
    include "includes/config/validate.php";
    error_reporting(0);

    $validate = new validate();
    $validate -> confirmVoter();
    $validate -> inactivityLogOut();

    if($_SESSION['admin'] == '1'){
        $validate -> log_user_out();
    }

    $electionName = $conn -> real_escape_string($_POST['electionName']);

    $selectElection = "SELECT * FROM `electionClass` WHERE `electionName` = '$electionName';";
    $electionResult = mysqli_query($conn, $selectElection) -> fetch_assoc();

    $electionType = $electionResult['electionType'];
    $electionOption = $electionResult['availableOptions'];

    if($electionType == 'referendum'){
        $optionList = 'basicOption';
    }else if($electionType == 'general'){
        $optionList = 'politicalParty';
    }else if($electionType == 'local'){
        $optionList = 'candidate';
    }

    $optionArray = explode(',', $electionOption);

    for($i = 0; $i < count($optionArray); ++$i){
        $optionConfig = $optionConfig."`ID` = '".$optionArray[$i]."' OR ";
    }

    $optionConfig = substr($optionConfig, 0, -4);
    $selectOption = "SELECT * FROM `$optionList` WHERE $optionConfig;";
?>

<html>
    <head>
        <title>Vote</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">

        <link href="includes/css/style.css" rel="stylesheet" type="text/css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="./includes/js/pageScroll.js" type="text/javascript"></script>

    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

        <button onclick="topFunction()" style="display: none; border-radius: 50%;" id="myBtn" title="Go to top">Top</button>

        <?php include "template/nav.php" ?>

        <div class="jumbotron text-center" style="margin-bottom:0px">
            <h1>Vote Now</h1>
        </div>

        <div class="container">
            <?php
                if($electionType == 'referendum'){
                    ?>
                        <form action="includes/action/vote.php" method="POST">
                            <table class="table table-hover">
                                <tr>
                                    <th>Option</th>
                                    <th>Vote for</th>
                                </tr>
                                <?php
                                    $selectOptionResult = mysqli_query($conn, $selectOption);
                                    for($i = 0; $i < $selectOptionResult -> num_rows; ++$i){
                                        $option = $selectOptionResult -> fetch_assoc();
                                        $id = $option['ID'];
                                        echo "<tr>";
                                            echo "<td>".$option['Option']."</td>";
                                            echo "<td>";
                                                ?>
                                                    <input type="hidden" value ='<?php echo $electionName; ?>' name="hiddenElectionName" id="hiddenElectionName" />
                                                    <input type="radio" name="option" value="<?php echo $id; ?>" />
                                                <?php
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </table>
                            <input type='submit' class="btn btn-lg btn-primary"  style="float: right" name='submitReferendum' value='Cast your vote' />
                        </form>
                    <?php
                }else if($electionType == 'local'){
                    ?>
                        <form action="includes/action/vote.php" method="POST">
                            <table class="table table-hover">
                                <tr>
                                    <th>Candidate Name</th>
                                    <th>Party</th>
                                    <th>Vote for</th>
                                </tr>
                                <?php
                                    $selectOptionResult = mysqli_query($conn, $selectOption);
                                    for($i = 0; $i < $selectOptionResult -> num_rows; ++$i){
                                        $option = $selectOptionResult -> fetch_assoc();
                                        $id = $option['ID'];
                                        if($option['m_name'] != ""){
                                            $name = $option['f_name']." ".$option['m_name']." ".$option['l_name'];
                                        }else{
                                            $name = $option['f_name']." ".$option['l_name'];
                                        }

                                        $candidatePartyID = $option['partyID'];
                                        $candidatePartySelect = "SELECT `partyName` FROM `politicalParty` WHERE `ID` = '$candidatePartyID';";
                                        $candidateParty = mysqli_query($conn, $candidatePartySelect) -> fetch_assoc();
                                        $partyName = $candidateParty['partyName'];

                                        echo "<tr>";
                                            echo "<td>".$name."</td>";
                                            echo "<td>".$partyName."</td>";
                                            echo "<td>";
                                                ?>
                                                    <input type="hidden" value ='<?php echo $electionName; ?>' name="hiddenElectionName" id="hiddenElectionName" />
                                                    <input type="radio" name="option" value="<?php echo $id; ?>" />
                                                <?php
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </table>
                            <input type='submit' class="btn btn-lg btn-primary" style="float: right" name='submitReferendum' value='Cast your vote' />
                        </form>
                    <?php
                }else if($electionType == 'general'){
                   ?>
                        <form action="includes/action/vote.php" method="POST">
                            <table class="table table-hover">
                                <tr>
                                    <th>Party Name</th>
                                    <th>Party Leader</th>
                                    <th>Vote For</th>
                                </tr>
                                <?php
                                    $selectOptionResult = mysqli_query($conn, $selectOption);

                                    for($i = 0; $i < $selectOptionResult -> num_rows; ++$i){
                                        $option = $selectOptionResult -> fetch_assoc();
                                        $id = $option['ID'];
                                        $partyName = $option['partyName'];
                                        $partyLeaderID = $option['partyLeaderID'];

                                        $candidatePartySelect = "SELECT * FROM `candidate` WHERE `ID` = '$partyLeaderID';";
                                        $candidateParty = mysqli_query($conn, $candidatePartySelect) -> fetch_assoc();

                                        if($candidateParty['m_name'] != ""){
                                            $name = $candidateParty['f_name']." ".$candidateParty['m_name']." ".$candidateParty['l_name'];
                                        }else{
                                            $name = $candidateParty['f_name']." ".$candidateParty['l_name'];
                                        }

                                        echo "<tr>";
                                            echo "<td>".$partyName."</td>";
                                            echo "<td>".$partyLeaderID."</td>";
                                            echo "<td>";
                                            ?>
                                            <input type="hidden" value ='<?php echo $electionName; ?>' name="hiddenElectionName" id="hiddenElectionName" />
                                            <input type="radio" name="option" value="<?php echo $id; ?>" />
                                            <?php
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </table>
                            <input type='submit' name='submitReferendum' class="btn btn-lg btn-primary" style="float: right" value='Cast your vote' />
                        </form>
                    <?php
                }
            ?>
        </div>
    </body>
</html>
