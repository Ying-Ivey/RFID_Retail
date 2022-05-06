<?php

session_start();

include "db.php";

if (isset($_POST['username']) && isset($_POST['password'])) {

    function validate($data)
    {

        $data = trim($data);

        $data = stripslashes($data);

        $data = htmlspecialchars($data);

        return $data;
    }

    $username = validate($_POST['username']);

    $pass = validate($_POST['password']);

    if (empty($username)) {

        header("Location: login.php?error=User Name is required");

        exit();
    } else if (empty($pass)) {

        header("Location: login.php?error=Password is required");

        exit();
    } else {
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "SELECT * FROM user WHERE username='$username' ";

        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result) == 1) {

            $row = mysqli_fetch_assoc($result);
            if ($row['username'] === $username && password_verify($pass, $row['password'])) {
                // if ($row['username'] === $username && $row['password'] === $pass) {

                echo "Logged in!";

                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['name'];

                header("Location: index.php");

                exit();
            } else {

                header("Location: login.php?error=Incorect User name or password");

                exit();
            }
        } else {

            header("Location: login.php?error=Incorect User name or password");

            exit();
        }
    }
} else {

    header("Location: login.php");

    exit();
}