<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if(isset($_GET['token']))
    {
        $token=$_GET['token'];
        // echo $token;
        $hashed_token=hash("sha256",$token);
        $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
        if(isset($db))
        {
    
            $str="SELECT FACULTY_EMAIL FROM TEACHER_LOGIN WHERE TOKEN = \"".$hashed_token."\";";
            $check=mysqli_query($db,$str);
            $Email_id=mysqli_fetch_array($check);
            $Email_id=$Email_id['FACULTY_EMAIL'];
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // print_r($_POST);
    $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
    $hashed_password=hash("sha256",$_POST['password']);

    $update="UPDATE  TEACHER_LOGIN SET FACULTY_PASSWORD=\"".$hashed_password."\",TOKEN='NULL' WHERE FACULTY_EMAIL=\"".trim($_POST['email'])."\";";
    echo $update;
    mysqli_query($db, $update);
    header("Location: http://localhost:8080/dbms/teacher_login.html");
    die();
}


?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="teacher_login.css">
</head>
<body>
    <div style="background-color:white; opacity:0.9">
        <img src="./images/logo.jpg" height="60px" alt="PES UNIVERSITY">
    </div>
    <div class="container">
        <div class="d-flex justify-content-center h-100 align-items-center">
            <div class="card">
                <div class="card-header">
                    <h2>Enter new Password</h2>
                    <hr id="seperator">
                </div>
                <div class="card-body">
                    <form  action="change_password.php" method="POST">
                        <div style="text-align: center;padding-bottom: 2%"> Enter New Password 
                            <br> 
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input  name="email" type="text" class="form-control" readonly = "readonly" placeholder="Email Id" value=" <?php echo $Email_id; ?>">
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input  name="password" type="password" class="form-control" placeholder="Password">     
                        </div>
                        <div class="form-group" >
                            <input type="submit" value="Submit" class="btn login_btn" id="login_btn"> 
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
                