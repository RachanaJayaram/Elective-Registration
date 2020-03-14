<!DOCTYPE html>
<html>
<head>
    <title>Send Link</title>
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

    $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
    if(isset($db))
    {
      $str="SELECT SRN,EMAIL_ID FROM STUDENT_LINK_LOGIN WHERE completed = false";
    
      $check=mysqli_query($db,$str);
      $COUNT=mysqli_num_rows($check);
      for ($i=0; $i <mysqli_num_rows($check) ; $i++) { 
          # code...
      
        $v=mysqli_fetch_array($check);
        
        mysqli_query($db,"DELETE FROM STUDENT_LINK_LOGIN WHERE SRN=\"".$v['SRN']."\";");

        $bytes = openssl_random_pseudo_bytes(9, $cstrong); //9 FOR 72 BIT OF ENTROPY
        $hex   = bin2hex($bytes); //creating token
        $hashed_token=hash("sha256",$hex);
        // echo $hashed_token;
        $insert_token_deets="INSERT INTO STUDENT_LINK_LOGIN VALUES(\"".$v['SRN']."\",\"".$v['EMAIL_ID']."\",false,\"$hashed_token\");";
        mysqli_query($db, $insert_token_deets);
   
        $to = $v['EMAIL_ID'];
        $subject = "Password Setting";
        $txt = "Follow this link for elective registration : http://localhost:8080/dbms/Student.php?token=$hex";
        $headers = "From: poornimaprerana32@gmail.com" . "\r\n";
        $result =mail($to,$subject,$txt,$headers);
      }
      echo $COUNT;
    }

?>
<div class="container">
                    <div class="d-flex justify-content-center h-100 align-items-center">
                        <div class="card"  style="background-color: rgba(3, 8, 75, 0.719);">
                                <div class="card-header">
                                        <h2>Return to Admin View Page </h2>
                                        <hr id="seperator">
                                </div>
                            <div class="card-body">
                                   
                                    <div class="form-group" >
                                    <a href="admin_view.php" class="btn login_btn" id="login_btn" >Go Back </a> 
                                    </div>

                            </div>

                        </div>
                    </div>
</body>
