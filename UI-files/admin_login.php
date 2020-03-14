<?php
// echo "SAdfadf";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
  // echo "<script type='text/javascript'>alert('jsdkjdsffzl');</script>";

    
    $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
    if(isset($db))
    {
      // echo "<script type='text/javascript'>alert('sdaaaaaaa');</script>";

      $hashed_token=hash("sha256",$_POST['pass']);
      
      $str="SELECT ADMIN_EMAIL FROM ADMIN_LOGIN WHERE ADMIN_EMAIL = \"".$_POST['email']."\" AND ADMIN_PASSWORD IS NOT NULL AND ADMIN_PASSWORD=\"".$hashed_token."\";";
      $check=mysqli_query($db,$str);

    
      echo $str;
      if(mysqli_num_rows($check)==1)
      {
        $Email_id=mysqli_fetch_array($check);
        $Email_id=$Email_id['ADMIN_EMAIL'];
        session_start();
        $_SESSION["admin_email"] = $Email_id;                            
        // Redirect user to welcome page
        echo "<script type='text/javascript'>alert('Logged In');window.location.href = 'http://localhost:8080/dbms/admin_view.php';</script>";

      }
      else
      {
        echo "<script type='text/javascript'>alert('Wrong Password');console.log('".$str."');window.location.href = 'http://localhost:8080/dbms/Admin_login.html';</script>";
        // header("location : teacher_login.html");
      }
    }
}

?>