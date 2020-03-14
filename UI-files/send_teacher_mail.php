<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <link rel="stylesheet" type="text/css" href="teacher_login.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    </head>
    <body style="height=100%;  vertical-align: middle">
    <div style="background-color:white; opacity:0.9">
                    <img src="./images/logo.jpg" height="60px" alt="PES UNIVERSITY">
        </div>
    <?php

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
if($_SERVER["REQUEST_METHOD"] == "POST")
{  


    $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
    if(isset($db))
    {
      $str="SELECT FACULTY_EMAIL FROM FACULTY WHERE FACULTY_EMAIL = \"".$_POST['email']."\";";
    //   echo $str;
      $check=mysqli_query($db,$str);
    //   while($row = mysqli_fetch_array($check)) {
    //     print_r ($row);
    //   }
      if(mysqli_num_rows($check)==1)
      {
        mysqli_query($db,"DELETE FROM TEACHER_LOGIN WHERE FACULTY_EMAIL=\"".$_POST['email']."\";");

        $bytes = openssl_random_pseudo_bytes(9, $cstrong); //9 FOR 72 BIT OF ENTROPY
        $hex   = bin2hex($bytes); //creating token
        $hashed_token=hash("sha256",$hex);
        // echo $hashed_token;
        $insert_token_deets="INSERT INTO TEACHER_LOGIN VALUES(\"".$_POST['email']."\",null,\"$hashed_token\");";
        mysqli_query($db, $insert_token_deets);
   
        $to = $_POST['email'];
        $subject = "Password Setting";
        $txt = "Follow this link to change your elective registration password: http://localhost:8080/dbms/change_password.php?token=$hex";
        $headers = "From: poornimaprerana32@gmail.com" . "\r\n";
        $result =mail($to,$subject,$txt,$headers);
    //    $result=1;
        if(!$result) {   
            // echo "Error";   
            // echo phpinfo();
            alert("Error");


        } else {
            // echo "Success";
            alert("Mail Sent. Check your inbox." );

        }
      }
      else
        alert("Mail Id not in our database");
    }
}
?>
<div class="container">
                    <div class="d-flex justify-content-center h-100 align-items-center">
                        <div class="card"  style="background-color: rgba(3, 8, 75, 0.719);">
                                <div class="card-header">
                                        <h2>Return to Login Page </h2>
                                        <hr id="seperator">
                                </div>
                            <div class="card-body">
                                   
                                    <div class="form-group" >
                                    <a href="teacher_login.html" class="btn login_btn" id="login_btn" >Go Back </a> 
                                    </div>

                            </div>

                        </div>
                    </div>
</body>
