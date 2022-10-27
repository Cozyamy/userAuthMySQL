<?php 
session_start();

require_once "../config.php";

//register users
function registerUser($fullnames, $country, $email, $gender, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
   //check if user with this email already exist in the database
    $checkdb = "SELECT * FROM students WHERE email = '$email'";
    $result = mysqli_query($conn, $checkdb);
    if(mysqli_num_rows($result) > 0){
        echo "<script> alert('User Email Already Taken')</script>";
        header("refresh: 2; url=../forms/register.html");
        return false;
    }
    else{
        $sql = "INSERT INTO  students (`full_names`, `country`, `email`, `gender`, `password`) 
                VALUES ('$fullnames', '$country', '$email', '$gender', '$password')";
        if ($conn->query($sql) === TRUE) {
            echo "<script> alert('User Successfully Registered!!')</script>";
            header("refresh: 2; url=../forms/login.html");
        }
        else{
            echo "<script> alert('An Error Occured please try again'</script>";
        }
    }
    $conn->close();
}

//login users
function loginUser($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    //open connection to the database and check if username exist in the database
    //if it does, check if the password is the same with what is given
    //if true then set user session for the user and redirect to the dasbboard
    $checkdb = "SELECT * FROM students WHERE email = '$email'";
    $result = mysqli_query($conn, $checkdb);
    if(mysqli_num_rows($result) > 0){
        while($row = mysqli_fetch_assoc($result)) {
            if($row["password"] === $password){
                $_SESSION["email"] = $row["email"];
                $_SESSION["username"] = $row["full_names"];
                header("Location: ../dashboard.php");
            }else{
                echo "Password is Wrong";
                echo '<meta http-equiv="refresh" content="2; url=../forms/login.html">';
            }
        }
    }else{
        echo "No Such User Found";
        echo '<meta http-equiv="refresh" content="2; url=../forms/login.html">';
    }
    $conn->close();
}


function resetPassword($email, $password){
    //create a connection variable using the db function in config.php
    $conn = db();
    //open connection to the database and check if username exist in the database
    //if it does, replace the password with $password given
    $checkdb = "SELECT * FROM students WHERE email = '$email'";
    $result = mysqli_query($conn, $checkdb);
    if(mysqli_num_rows($result) > 0){
        $update = "UPDATE students SET password = '$password' WHERE email = '$email'";
        if (mysqli_query($conn, $update)) {
            echo "<script> alert('Password Successfully updated!')</script>";
            echo '<meta http-equiv="refresh" content="2; url=../forms/login.html">';
        }
        else{
            echo "<script> alert('An Error Occured please try again')</script>";
            echo '<meta http-equiv="refresh" content="3; url=../forms/resetpassword.html">';
        }
    }
    else{
        echo "User not found";
        echo '<meta http-equiv="refresh" content="2; url=../forms/login.html">';
    }
}

function getusers(){
    $conn = db();
    $sql = "SELECT * FROM Students";
    $result = mysqli_query($conn, $sql);
    echo"<html>
    <head></head>
    <body>
    <center><h1><u> ZURI PHP STUDENTS </u> </h1> 
    <table border='1' style='width: 700px; background-color: magenta; border-style: none'; >
    <tr style='height: 40px'><th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th></tr>";
    if(mysqli_num_rows($result) > 0){
        while($data = mysqli_fetch_assoc($result)){
            //show data
            echo "<tr style='height: 30px'>".
                "<td style='width: 50px; background: blue'>" . $data['id'] . "</td>
                <td style='width: 150px'>" . $data['full_names'] .
                "</td> <td style='width: 150px'>" . $data['email'] .
                "</td> <td style='width: 150px'>" . $data['gender'] . 
                "</td> <td style='width: 150px'>" . $data['country'] . 
                "</td>
                <form action='action.php' method='post'>
                <input type='hidden' name='id'" .
                 "value=" . $data['id'] . ">".
                "<td style='width: 150px'> <button type='submit', name='delete'> DELETE </button>".
                "</tr>";
        }
        echo "</table></table></center></body></html>";
    }
    //return users from the database
    //loop through the users and display them on a table
}

function logout(){
    if ($_SESSION['username']) {
        session_unset();
        session_destroy();
        header("Location: ../index.php?message=logout");
    }
    else{
        header("Location: ../forms/login.php");
    }
}

 function deleteaccount($id){
     $conn = db();
     //delete user with the given id from the database
     $delete = "DELETE FROM students WHERE id = '$id'";
     if (mysqli_query($conn, $delete)) {
            echo "<script>alert('User Record Deleted Successfully')</script>";
            header("refresh:0.5; url=action.php?all=");
     }else{
        echo "Error deleting record: " . mysqli_error($conn);
    }
}