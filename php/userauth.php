<?php

require_once "../config.php";

//register users
function registerUser($fullnames, $email, $password, $gender, $country)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    //check if user with this email already exist in the database
    $sqlSelect = "SELECT * FROM Students WHERE email = '$email'";
    $result = mysqli_query($conn, $sqlSelect);
    if (mysqli_num_rows($result) > 0) {
        echo "User already exists";
    } else {
        //insert the user details into the database
        $sqlInsert = "INSERT INTO Students (`full_names`, `email`, `password`, `gender`, `country`)
            VALUES ('$fullnames', '$email', '$password', '$gender', '$country');";

        if ($conn) {
            if (mysqli_query($conn, $sqlInsert)) {
                echo "User Successfully registered";
            } else {
                echo "Error creating account";
            }
        }
    }
    mysqli_close($conn);
}


//login users
function loginUser($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'> LOG ME IN (IMPLEMENT ME) </h1>";
    //open connection to the database and check if username exist in the database
    $sqlSelect = "SELECT * FROM Students WHERE email = '$email'";
    $result = mysqli_query($conn, $sqlSelect);
    //if it does, check if the password is the same with what is given
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['password'] == $password) {
            //if true then set user session for the user and redirect to the dasbboard
            session_start();
            $_SESSION['username'] = $row['email'];
            header("Location: ../dashboard.php");
        } else {
            //if false then redirect back to login page
            header("Location: ../forms/login.html");
        }
    } else {
        //if it does not exist then redirect back to login page
        header("Location: ../forms/login.html");
    }
    mysqli_close($conn);
}


function resetPassword($email, $password)
{
    //create a connection variable using the db function in config.php
    $conn = db();
    // echo "<h1 style='color: red'>RESET YOUR PASSWORD (IMPLEMENT ME)</h1>";
    //open connection to the database and check if username exist in the database
    $sqlSelect = "SELECT * FROM Students WHERE email = '$email'";
    $result = mysqli_query($conn, $sqlSelect);
    //if it does, replace the password with $password given
    if (mysqli_num_rows($result) > 0) {
        $sqlUpdate = "UPDATE Students SET password = '$password' WHERE email = '$email'";
        $query = mysqli_query($conn, $sqlUpdate);
        if ($query) {
            echo "Password successfully updated";
        } else {
            echo "Error updating password";
        }
    } else {
        //if it does not exist then redirect back to login page
        echo "User does not exist";
    }
    mysqli_close($conn);
}

function getusers()
{
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo "<html>
    <head>
    <meta charset='UTF-8'>
    <title>welcome</title>
    <link rel='stylesheet' href='../assets/style.css'>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css' rel='stylesheet' integrity='sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor' crossorigin='anonymous'>
</head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: teal; border-style: none;' >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
            //show data
            echo "<tr style='height: 30px;'>" .
                "<td style='width: 50px; background: grey'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] .
                "</td> <td style='width: 150px'>" . $data['country'] .
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                "value=" . $data['id'] . ">" .
                "<td style='width: 150px'> <button class='btn btn-sm btn-secondary border rounded' type='submit', name='delete'> DELETE </button>" .
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    mysqli_close($conn);
    //return users from the database
    //loop through the users and display them on a table
}

function deleteaccount($id)
{
    $conn = db();
    //delete user with the given id from the database
    $sql = "DELETE FROM Students WHERE id = $id";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo "User successfully deleted";
    } else {
        echo "Error deleting user";
    }
    mysqli_close($conn);
}
