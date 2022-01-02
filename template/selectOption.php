<?php
    include "../includes/config/opendb.php";

    if( empty($_POST['type'])){
        echo "<option disabled='disabled' value='' selected='selected'>Please Select</option>";
    }else{
        $type = $_POST['type'] ;

        // Depending what the admin has choosen from the createElection.php page, this triggers a different if statement.
        // Which sets a different SQL Statement and executes it accordingly.
        //
        if($type =='referendum'){
            $selectOption = "SELECT * FROM `basicOption`;";
        }else if($type == 'general' ){
            $selectOption = "SELECT * From `politicalParty`;";
        }else if($type == 'local'){
            $selectOption = "SELECT * FROM `candidate`;";
        }

        $result = mysqli_query($conn, $selectOption);

        // First option is disabled.

        echo "<option disabled='disabled' value='' selected='selected'>Please Select</option>";
        for($i = 0; $i< $result ->num_rows; ++$i){                              // Run a for loop for all the found results.
            $results = $result -> fetch_assoc();                                // Fetch all of the results individually

            if($type =='referendum'){                                           // Depending what the admin has chosen, change the option variable accordingly
                $option = $results['Option'];
            }else if($type == 'general' ){
                $option = $results['partyName'];
            }else if($type == 'local'){
                $option = $results['f_name']." ".$results['l_name'];
            }

            $id = $results['ID'];
            echo "<option value='$id'>$option</option>";                        // Populate each individual option for the dropdown list and send it back to the createElection.php page.
        }
    }
?>

<!--This page has been created to populate the multi dropdown list in the createElection page. -->
<!--Depending what the admin has chosen the dropdown list changes its option accordingly. -->
