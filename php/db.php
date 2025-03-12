<?php
$env = parse_ini_file(".env");
$connection = mysqli_connect(
    $env["hostname"],
    $env["username"],
    $env["password"],
    $env["database"])
?>