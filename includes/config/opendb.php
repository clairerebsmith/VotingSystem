<?php

    require "cred.php";

    $conn = mysqli_connect($db_conn_hostname, $db_conn_username, $db_conn_password, $db_name );


    if(!$conn) {
        die("Connection failed:" . mysqli_connect_error());
    }