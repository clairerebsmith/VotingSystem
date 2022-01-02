<?php

    require "includes/config/opendb.php";                                                                                   // Include database connection File
    include "includes/action/ZC.php";                                                                                       // Include the external API file
    use ZingChart\PHPWrapper\ZC;

    $today = date("Y-m-d H:i:s");                                                                                    // Get today date in the System format

    if(!isset($_GET['electionName'])){                                                                                      // If no election has not been choosed
        $electionSelect = "SELECT * FROM `electionClass` WHERE `startTime` < '$today' ORDER BY `startTime` DESC LIMIT 1;";  // SQL statement to select the most recent election and to only give one result
        $electionResult = mysqli_query($conn, $electionSelect) -> fetch_assoc();                                            // Fetch that information
        $electionNameLoad = $electionResult['electionName'];                                                                // Name of election is stored in a variable

        header("location: results.php?electionName=$electionNameLoad");                                              // Page is now refreshed with the latest election that is being run
    }
?>

<!DOCTYPE html>
<html>
<!-- Including all the external CSS, JS files and also including internal CSS file-->
    <head>
        <title>Voting Results Page</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <script src="//cdn.zingchart.com/zingchart.min.js" ></script>

        <link href="includes/css/home.css" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <link href="includes/css/style.css" rel="stylesheet" type="text/css">
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">
        <?php require_once "template/nav.php" ?>

        <div class="jumbotron text-center" style="margin-bottom:0px">
            <h1>Results Page</h1>
        </div>

        <div class="bg-grey">
            <div id="votingMay" class="container-fluid text-center">
                <h2>THE RESULTS OF THE ELECTION AT THE MOMENT </h2>
                <div class="row">
                    <div class="col-sm-2">
                        <div style="overflow: scroll; height: 340px; margin-top: 40px;font-weight: normal;line-height: 25px " class="h5">
                            <?php
                                $electionsSelect = "SELECT * FROM `electionClass` WHERE `startTime` < '$today';";           // Set SQL Command to select all elections that have happened in the past
                                $electionsResult = mysqli_query($conn, $electionsSelect);                                   // Execute command

                                for($i = 0; $i < $electionsResult ->num_rows; ++$i){                                        // For loop for all results
                                    $elections = $electionsResult->fetch_assoc();                                           // Fetch individual information

                                    $electionName = $elections['electionName'];                                             // Store electionName in the variable

                                    $link = "results.php?electionName=".$elections['electionName'];                         // Create string using variables and concatenating it together

                                    echo "<a href='$link'>$electionName</a><br>";                                           // Create hyperlink using the above string
                                }
                            ?>
                        </div>
                    </div>
                    <div id="myChart" class="col-sm-10"></div>
                    <?php
                    if(isset($_GET['electionName'])){                                                                       // Check if an election has been selected to be displayed in the graph

                        $electionName = $conn -> real_escape_string($_GET['electionName']);                                 // Make sure that the electionName has no scope of SQL injection

                        $electionInfoSelect = "SELECT * FROM `electionClass` WHERE `electionName` = '$electionName';";      // Select everything from the electionClass table where the electionName is the chosen election
                        $electionInfo = mysqli_query($conn, $electionInfoSelect) -> fetch_assoc();                          // Execute SQL command


                        if($electionInfo['electionType'] == 'general'){                                                     // If the election type is general set the following variable
                            $tableName = "politicalParty";
                            $optionName = "partyName";
                        }else if($electionInfo['electionType'] == 'local'){                                                 // IF the election type is local set the following variables
                            $tableName = "candidate";
                            $optionName = "f_name";
                        }else{
                            $tableName = 'basicOption';                                                                     // If none of the above set the variable as following
                            $optionName = 'Option';
                        }

                        $electionOption = $electionInfo['availableOptions'];                                                // Get all of the available options from the electionClass table
                        $options = explode(', ', $electionOption);                                                 // Separate the string into individual components

                        $optionSort = sort($options);                                                               // sort the data from high to low

                        for($i = 0; $i < count($options); ++$i){                                                           // for each individual option,
                            $optionConfig = $optionConfig."`ID` = '".$options[$i]."' OR ";                                 // Create a string from the individual options
                        }
                        $optionConfig = substr($optionConfig, 0, -4);                                         // Remove the access space and OR from the string that has been created on line 92

                        $optionSelect = "SELECT * from `$tableName` WHERE $optionConfig;";                                 // SELECT everything from the option table with the condition created from line 94
                        $optionResult = mysqli_query($conn, $optionSelect);                                                // Execute SQL command

                        $optionArray = [];                                                                                  // Define empty array

                        for($i = 0; $i < $optionResult -> num_rows; ++$i){                                                  // for loop for SQL execution
                            $option = $optionResult -> fetch_assoc();                                                       // Fetch the result from the query

                            array_push($optionArray, $option[$optionName]);                                          // Store the data into the array defined on line 99
                        }

                        $optionDisplay = substr($optionDisplay, 0, -2);                                         // Remove the last two characters of this string

                        $voteCount =[];                                                                                     // Define empty array
                        $optionCountSelect = "SELECT  voted, count(*) as voteCount FROM `$electionName` GROUP BY voted";    // SQL command to select everything from the election table and to count how many votes each option has received

                        $optionCountResult = mysqli_query($conn, $optionCountSelect);                                       // Execute SQL command

                        for($i = 0; $i < $optionCountResult ->num_rows; ++$i) {                                             // for loop
                            $optionCounts = $optionCountResult->fetch_assoc();                                              // Fetch result data

                            array_push($voteCount, (int)$optionCounts['voteCount']);                                // Store result in array
                        }

                        $displayDifference = count($optionArray) - count($voteCount);                                       // Check whether there are options that have not been voted for

                        for($i = 0; $i < $displayDifference; ++$i){                                                         // for loop
                            array_push($voteCount, (int)'0');                                                       // store additional data to the voteCount array as 0
                        }

                        $chart = new ZC("myChart");                                                                    // define which div the chart needs to be displayed in
                        $chart -> setTitle("Election Result");                                                       // Set title of chart
                        $chart -> setChartType("bar");                                                               // Set Chart type
                        $chart  -> setSeriesData(0, $voteCount);                                                           // Use gathered data to make the bars
                        $chart -> setSeriesText(["Number of Votes: "]);                                                     // Set Text
                        $chart -> setScaleXLabels($optionArray);                                                            // Set the X-axis labels
                        $chart -> setScaleYTitle("Number of Votes");                                                   // Set the Y-axis label
                        $chart -> render();                                                                                 // Render the graph
                    }
                    ?>
                </div>
            </div>
        </div>
        <h2>WATCH THE LIVE FEED OF SKY NEWS: </h2><br>
        <div id="news" style="background-color: black" class="carousel slide text-center" data-ride="carousel">
            <iframe width="100%" height="550"  style="display:flex;" src="https://www.youtube.com/embed/lrX6ktLg8WQ?autoplay=1&mute=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe> <!-- Include YouTube live broadcast-->
        </div>
        <div class="tradingview-widget-container">                                                                                                                                                                                                     <!--Include stock prices using external widget injected to the website-->
            <div class="tradingview-widget-container__widget"></div>
            <div class="tradingview-widget-copyright"><a href="https://uk.tradingview.com" rel="noopener" target="_blank"><span class="blue-text">Financial Markets</span></a> by TradingView</div>
            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-ticker-tape.js" async>
                // The below data is in the jason format which can tell the widget which stocks to show
                {
                    "symbols": [
                    {
                        "description": "BARC/GBP",
                        "proName": "LSE:BARC"
                    },
                    {
                        "description": "LSE/GBP",
                        "proName": "LSE:LSE"
                    },
                    {
                        "description": "HSPA/GBP",
                        "proName": "LSE:HSBA"
                    },
                    {
                        "description": "LLOY/GBP",
                        "proName": "LSE:LLOY"
                    },
                    {
                        "description": "BP/USD",
                        "proName": "LSE:BP."
                    },
                    {
                        "description": "RDSB/EUR",
                        "proName": "LSE:RDSB"
                    }
                ],
                    "theme": "dark",
                    "isTransparent": false,
                    "displayMode": "adaptive",
                    "locale": "uk"
                }
            </script>
        </div>
    </body>
</html>