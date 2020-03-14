<?php
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    
    $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
    if(isset($db))
    {
      $hashed_token=hash("sha256",$_POST['pass']);

      $str="SELECT FACULTY_EMAIL FROM TEACHER_LOGIN WHERE FACULTY_EMAIL = \"".$_POST['email']."\" AND FACULTY_PASSWORD IS NOT NULL AND FACULTY_PASSWORD=\"".$hashed_token."\";";
      // echo $str;
      $check=mysqli_query($db,$str);

    }

      if(mysqli_num_rows($check)==1)
      {
        $Email_id=mysqli_fetch_array($check);
        $Email_id=$Email_id['FACULTY_EMAIL'];
        session_start();
        $_SESSION["email_id"] = $Email_id;                            
        // Redirect user to welcome page
        header("location: teacher_view.php");

      }
      else
      {
        echo "<script type='text/javascript'>alert('Wrong Password');window.location.href = 'http://localhost:8080/dbms/teacher_login.html';</script>";
        // header("location : teacher_login.html");
      }
}
?>