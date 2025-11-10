<?php

function getDbConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "Tung@2005";
    $dbname = "khoa_hoc_online";
    $port = 3306;

    $conn = mysqli_connect($servername, $username, $password, $dbname, $port);

    if (!$conn) {
        die("Kết nối database thất bại: " . mysqli_connect_error());
    }
    
    mysqli_set_charset($conn, "utf8");
    return $conn;
}

?>