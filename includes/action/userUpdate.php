<?php

    //    This entire class has been defined so that the voter can uplaod their
    //    documents to the system for the admin to approve or reject them.
    //    The function name is uploadFile which has the MySQL connection as a parameter
    //    for the function.
    //    The userID is retrieved from the session (the user has to be logged in for this function to be executed)
    //    define error as an array.

    //    Line 26 to 30, stores the directory path in a variable and check if it exists. If it doesn't, it makes a new one


    session_name('votingSystem');
    session_start();
    error_reporting(0);


    class userUpdate
    {
        function uploadFile($conn)
        {

            $userID = $_SESSION['userID'];
            $error = [];

            $dir = "./includes/attachments/" . $userID . "/";

            if (!is_dir($dir)) {
                mkdir($dir);
            }

            // Storing various file information in different variables

            $filePath = $dir . basename($_FILES["file"]["name"]);

            $fileName = basename($_FILES["file"]["name"]);
            $fileNames = $_FILES["file"]["name"];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Check if the file already exists, if it does, store the error to the array

            if (file_exists($filePath)) {
                array_push($error, "The File already exists, please rename the file and try again");
            }

            // check for file type. If the fileType is not, png, jpeg, jpg or pdf, store the error in the array

            if ($fileExtension !== "png" && $fileExtension !== "jpeg" && $fileExtension !== "jpg" && $fileExtension !== "pdf") {
                array_push($error, "Please make sure that you are sending the correct file.");
                array_push($error, "Please make sure that you are not submitting a word file. The ideal format is JPEG, JPG, PNG or PDF");
            }

            // if filename does not exists, store the error in the array

            if (!$fileName) {
                array_push($error, "Please select a file to upload");
            }

            // if the error array is empty, Insert the necessary information to the documentation table.

            if (!$error) {
                $uploadFile = "INSERT INTO `documentation` (userID, docName, docType, docPath) values ('$userID', '$fileNames', '$fileExtension', '$filePath')";

                mysqli_autocommit($conn, FALSE);  // Turn off autoCommit
                mysqli_query($conn, $uploadFile);

                // If there is an error in the execution of the SQL statement, revert the changes and store the error in the array
                if (mysqli_error($conn)) {
                    mysqli_rollback($conn);

                    array_push($error, "There was an error uploading the file. If you experience this issue persistently, please contact the administration team.");
                } else {

                    // If there is no error in the execution, commit the statement and move the file to the appropriate location.

                    mysqli_commit($conn);
                    move_uploaded_file($_FILES['file']['tmp_name'], $filePath);
                    array_push($error, "success");
                }
            }

            // return array information to the profile.php page.
            return $error;
        }
    }
?>