<?php

    require "../includes/config/opendb.php";
    if( empty($_POST['county'])){

    }else{
        $county = trim($_POST['county']);

        // Set SQL statement and execute it

        $sql = "SELECT  `ID`, `District` FROM `location` where `County` LIKE '$county'; ";
        $result = mysqli_query($conn, $sql);

        // first option is disabled

        echo "<option disabled='disabled' value='' selected='selected'>Please Select</option>";
        for($i = 0; $i< $result ->num_rows; ++$i){          // Run for loop for all the results found
            $results = $result -> fetch_assoc();            // Fetch all individual results
            $city = $results['District'];                   // save the information in the variable
            $id = $results['ID'];                           // Save the information in the variable
            echo "<option value='$id'>$city</option>";      // populate the options for the dropdown list accordingly.
        }
    }
?>

<!--Like the selectOption Page, the selectCity page has pretty much the exact same function. The only difference is
that this only gets triggered  when a local election is selected from electionType in createElection page. -->