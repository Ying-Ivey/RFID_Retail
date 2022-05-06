<?php

session_start();

include "db.php";

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name'])) {

    function validate($data)
    {

        $data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return $data;
    }

    $username = validate($_POST['username']);

    $pass = validate($_POST['password']);
    $name = validate($_POST['name']);

    if (empty($username)) {

        header("Location: login.php?error=User Name is required");

        exit();
    } else if (empty($pass)) {

        header("Location: login.php?error=Password is required");

        exit();
    } else {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "INSERT INTO `user`(`username`, `password`, `name`, `role`) VALUES ('$username','$hashed_password','$name','user') ";

        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) === 1) {

            $row = mysqli_fetch_assoc($result);
        } else {

            header("Location: login.php?error=Incorect User name or password");

            exit();
        }
    }
} else {

    header("Location: login.php");

    exit();
}