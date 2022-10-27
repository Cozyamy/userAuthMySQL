<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
<?php
session_start();
if(isset($_SESSION["email"])){
    logout();
}else{
    echo "
    <body class='bg-success'>
    <div class='alert alert-danger' role='alert' style='margin:5% auto;'>
    <h4><center>No Session Found... 
    <br> Redirecting in 3</center></h4>
    </div>
    </body>";
    echo '<meta http-equiv="refresh" content="3; url=../forms/login.html">';
}

function logout(){
    session_destroy();
    echo "
    <body class='bg-success'>
    <div class='alert alert-danger' role='alert' style='margin:5% auto;'>
    <h3><center>Session Destroyed... 
    <br> Redirecting in 3</center></h3>
    </div>
    </body>";
    echo '<meta http-equiv="refresh" content="3; url=../forms/login.html">';
}