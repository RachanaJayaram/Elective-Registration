<?php
$display="";
$teacher="";
$department="";
session_start();
$db=mysqli_connect('localhost:3306','root','','ELECTIVE');
if (isset($_SESSION['admin_email']))
  echo "<script>console.log('Logged in as ".$_SESSION['admin_email']."');</script>";
else
  echo "<script>window.location='Admin_login.html';</script>";
if(isset($db))
{
  $str="SELECT * FROM FACULTY;";
  $check=mysqli_query($db,$str);
  if(mysqli_num_rows($check)>=1)
  {
    $str="SELECT department.DEPARTMENT_ID,FACULTY_NAME,FACULTY_ID FROM department,faculty WHERE department.DEPARTMENT_ID=faculty.DEPARTMENT_ID";
    $check=mysqli_query($db,$str);
    while($rows=mysqli_fetch_assoc($check))
    {
      $teacher.=$rows['FACULTY_ID'].":::".$rows['FACULTY_NAME'].":::".$rows['DEPARTMENT_ID']."||||";
    }

    $str="SELECT DISTINCT DEPARTMENT_ID,DEPARTMENT_NAME FROM DEPARTMENT";
    $check=mysqli_query($db,$str);
    while($rows=mysqli_fetch_assoc($check))
    {
      $department.=$rows['DEPARTMENT_ID'].":::".$rows['DEPARTMENT_NAME']."||||";
    }
  }
}
?>

<?php
$display="";
// echo "FDASSSSSSSSSSSSS";
if($_SERVER["REQUEST_METHOD"] == "POST")
{

 $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
 #print_r($_POST);
 extract($_POST);
 if(isset($_POST['Faculty_Id']))
 {
   if($Faculty_Id=="" || $Faculty_Name=="" || $Faculty_Email==""  || $faculty_department=="SELECT")
   {
     if($Faculty_Id=="")
     {
       $display="<script type='text/javascript'>alert('ENTER Faculty Id');</script>";
     }
     elseif($Faculty_Name=="")
     {
       $display="<script type='text/javascript'>alert('ENTER Faculty Name');</script>";
     }
     elseif($Faculty_Email=="")
     {
       $display="<script type='text/javascript'>alert('ENTER Faculty Email');</script>";
     }
     
     elseif($faculty_department=="SELECT")
     {
       $display="<script type='text/javascript'>alert('SELECT A VALID DEPARTMENT');</script>";
     }
   }
   else
   {
     $str="SELECT * FROM FACULTY WHERE FACULTY_ID='".$Faculty_Id."';";
     $check=mysqli_query($db,$str);
     if(mysqli_num_rows($check)>0)
     {
       $display="<script type='text/javascript'>alert('FACULTY ALREADY EXISTS');</script>";
       if (isset($_SESSION['reload']) && $_SESSION['reload']=='true')
       {
        $_SESSION['reload']='false';
        $display='';
       }
     }
     else
     {
       $str="INSERT INTO FACULTY(FACULTY_NAME,FACULTY_ID,FACULTY_EMAIL,DEPARTMENT_ID)VALUES('".$Faculty_Name."','".$Faculty_Id."','".$Faculty_Email."','".$faculty_department."');";
       $check=mysqli_query($db,$str);
       $display="<script type='text/javascript'>alert('FACULTY ADDED SUCCESSFULLY');parent.window.location.reload(true);</script>";
       $_SESSION['reload']='true';
       echo "<script type='text/javascript'>console.log(\"".$str."\");</script>";
      //  print_r($str);
     }
   }
 }
 elseif (isset($_POST['course_code']))
 {
   if($elective_name=="" || $course_code=="" || $number_of_credits=="" || $corresponding_year=="" || $course_department=="SELECT" || $handling_faculty=="SELECT")
   {
     if($elective_name=="")
     {
       $display="<script type='text/javascript'>alert('ENTER Elective name');</script>";
     }
     elseif($course_code=="")
     {
       $display="<script type='text/javascript'>alert('ENTER Course Code');</script>";
     }
     elseif($number_of_credits=="")
     {
       $display="<script type='text/javascript'>alert('ENTER Number Of Credits');</script>";
     }
     elseif($corresponding_year=="")
     {
       $display="<script type='text/javascript'>alert('ENTER CORRESPONDING YEAR');</script>";
     }
     elseif($course_department=="SELECT")
     {
       $display="<script type='text/javascript'>alert('SELECT A VALID DEPARTMENT');</script>";
     }
     elseif($handling_faculty=="SELECT")
     {
       $display="<script type='text/javascript'>alert('SELECT A VALID FACULTY');</script>";
     }
   }
   else
   {
     $str="SELECT * FROM ELECTIVE WHERE COURSE_CODE='".$course_code."' AND FACULTY_ID='".$handling_faculty."';" ;
     $check=mysqli_query($db,$str);
     $display="<script type='text/javascript'>console.log(\"".$str."\");</script>";
    //  echo $str;
     #print_r($str);
     if(mysqli_num_rows($check)>0)
     {
      $display="<script type='text/javascript'>alert('FACULTY TEACHING THE COURSE ALREADY EXISTS');</script>";

      // $display="<script type='text/javascript'>alert('FACULTY ALREADY EXISTS');</script>";
      if (isset($_SESSION['reload']) && $_SESSION['reload']=='true')
      {
       $_SESSION['reload']='false';
       $display='';
      }
     }
     else
     {
       $str="INSERT INTO ELECTIVE(ELECTIVE_NAME,COURSE_CODE,NUMBER_OF_CREDITS,CORRESPONDING_YEAR,DEPARTMENT_ID,FACULTY_ID,MAX_NO_OF_STUDENTS)VALUES('".$elective_name."','".$course_code."',".$number_of_credits.",".$corresponding_year.",'".$course_department."','".$handling_faculty."','".$_POST['max_no']."');";
       $check=mysqli_query($db,$str);
       $display="<script type='text/javascript'>alert('COURSE SUCCESSFULLY ADDED');parent.window.location.reload(true);</script>";
       echo "<script type='text/javascript'>console.log(\"".$str."\");</script>";
       $_SESSION['reload']='true';

       #print_r($str);
     }
   }
 }
 elseif (isset($_POST['Remove_Student'])) {
  $Remove_Student=$_POST['Remove_Student'];

   if($Remove_Student=="")
   {
     $display="<script type='text/javascript'>alert('ENTER STUDENT TO REMOVE');</script>";
    //  echo $display;

    }
   else
   {
     $str="SELECT * FROM STUDENT WHERE SRN='".$Remove_Student."'";
    //  echo $str;
     $check=mysqli_query($db,$str);
     if(mysqli_num_rows($check)==0)
     {
      $display="<script type='text/javascript'>alert('STUDENT DOESNT EXIST');</script>";
      if (isset($_SESSION['reload']) && $_SESSION['reload']=='true')
      {
       $_SESSION['reload']='false';
       $display='';
      }
        $str1="DELETE FROM STUDENT WHERE SRN='".$Remove_Student."';";
        // echo $str1;

        $check1=mysqli_query($db,$str1);
        $str1="UPDATE STUDENT_LINK_LOGIN SET completed=false where  SRN='".$Remove_Student."';";
        $check1=mysqli_query($db,$str1);
        // echo $str1;

        $display="<script type='text/javascript'>alert('STUDENT REMOVED SUCCESSFULLY');parent.window.location.reload(true);</script>";
        $_SESSION['reload']='true';

      }
     else
     {
        $display="<script type='text/javascript'>alert('STUDENT DOES NOT EXIST');</script>";
     }
     
    //  echo $display;

   }

 }
 elseif (isset($_POST['new_department_id'])) {
   if($new_department_id=="" || $new_department_name=="")
   {
     if($new_department_id=="")
     {
       $display="<script type='text/javascript'>alert('ENTER DEPARTMENT ID');</script>";
     }
     elseif($new_department_name=="")
     {
       $display="<script type='text/javascript'>alert('ENTER DEPARTMENT NAME');</script>";
     }
   }
   else
   {
     $str="SELECT * FROM DEPARTMENT WHERE DEPARTMENT_ID='".$new_department_id."';" ;
     $check=mysqli_query($db,$str);
    # print_r($str);
     if(mysqli_num_rows($check)>0)
     {
      $display="<script type='text/javascript'>alert('DEPARTMENT ALREADY EXISTS');</script>";
      // $display="<script type='text/javascript'>alert('FACULTY ALREADY EXISTS');</script>";
      if (isset($_SESSION['reload']) && $_SESSION['reload']=='true')
      {
       $_SESSION['reload']='false';
       $display='';
      }
     }
     else
     {
       $str="INSERT INTO DEPARTMENT(DEPARTMENT_ID,DEPARTMENT_NAME,FACULTY_ID_MANAGE)VALUES('".$new_department_id."','".$new_department_name."','".$new_department_manage."');";
       $str="INSERT INTO DEPARTMENT(DEPARTMENT_ID,DEPARTMENT_NAME,FACULTY_ID_MANAGE)VALUES('".$new_department_id."','".$new_department_name."','".$new_department_manage."');";
       $check=mysqli_query($db,$str);
       $display="<script type='text/javascript'>console.log('".$str."');</script>";

       #print_r($str);
       $display="<script type='text/javascript'>alert('DEPARTMENT ADDED SUCCESSFULLY');parent.window.location.reload(true);</script>";
       $_SESSION['reload']='true';

       echo "<script type='text/javascript'>console.log(\"".$str."\");</script>";
      //  echo $display;
      }
   }
  //  echo $display;



 }
}
?>


<script>
<!--
  function onload()
  {
    var department="<?php echo $department;?>"
    department=department.split("||||");
          var faculty_department=document.getElementById("faculty_department");
          var course_department=document.getElementById("course_department");
          newelement=document.createElement("option");
          newelement.className="selected_semester";
          newelement.value="SELECT";
          newelement.innerHTML="SELECT";


          newelement1=document.createElement("option");
          newelement1.className="selected_semester";
          newelement1.value="SELECT";
          newelement1.innerHTML="SELECT";


          faculty_department.appendChild(newelement1);
          course_department.appendChild(newelement);
      for(var i=0;i<department.length-1;i++)
      {
        ele=department[i].split(":::");
        newelement=document.createElement("option");
        newelement.className="selected_semester";
        newelement.value=ele[0];
        newelement.innerHTML=ele[1];

        newelement1=document.createElement("option");
        newelement1.className="selected_semester";
        newelement1.value=ele[0];
        newelement1.innerHTML=ele[1];


        faculty_department.appendChild(newelement1);
        course_department.appendChild(newelement);
      }
  }

  function department_change(sel)
  {
    var department_selected=document.getElementById("course_department");
    var teacher_select=document.getElementById("handling_faculty");
    var teacher="<?php echo $teacher;?>"
    teacher=teacher.split("||||");
    var teacher_previous=document.getElementsByClassName("selected_teacher");
    for(var j=0;j<teacher_previous.length;j++)
    {
      teacher_previous[j].style.display="none";
    }
    for(var i=0;i<teacher.length-1;i++)
    {
        ele=teacher[i].split(":::");
        console.log(department_selected.value,ele[2],department_selected.value==ele[2]);
        if(department_selected.value==ele[2])
        {
          var newelement=document.createElement("option");
          newelement.className="selected_teacher";
          newelement.value=ele[0];
          newelement.innerHTML=ele[1];
          teacher_select.appendChild(newelement);
        }
    }
  }
-->
</script>

<html>
<head>
<link rel="javascript" href="student.js" type="text/javascript"/>
<link rel="stylesheet" type="text/css" media="screen" href="admin_view.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<title>
  Admin View
</title>
<script type="text/javascript">
<!--
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
-->
</script>
</head>
<body onload="onload()">
<div class= "row login-header">
    <div class="col-sm-12" style="color:white;background-color:white; opacity:0.9">
     
      <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="#">About</a>
        <a href="#">Logout</a>
      </div>
      <div style="color:white;background-color:black; opacity:0.9">
    </div>
      <div style="font-size:3vw;cursor:pointer;margin-left:1vw;color:black;" onclick="openNav()">&#9776;
                    <img src="./images/logo.jpg" height="60px" alt="PES UNIVERSITY">
                    </div>
      </div>
  </div>
<div class="row">
  <div class="col-sm-6">
   <div class="row">
    <div class="col-sm-12" id="extra">
      <div class="col-sm-2"></div>
      <div class="col-sm-10" id="login-half" style="  color:white;background-color: rgba(3, 8, 75, 0.9);">
        <span style="font-Size:20px;"><h2>Register New Teacher</h2> </span>
        <br>
        <form class="form-inline" style="margin-left:5%" action="" method="post">
          <div class="form-group"><br>
            Faculty ID:<?php echo $display;?>
            <input type="text" class="fill-box " id="Faculty_Id" placeholder=" Enter ID" name="Faculty_Id">
          </div>
          <div class="form-group"><br>
            Faculty Name:
            <input type="text" class="fill-box " id="Faculty_Name" placeholder=" Enter Name" name="Faculty_Name">
          </div>
          <div class="form-group"><br>
            Faculty Email:
            <input type="text" class="fill-box " id="Faculty_Email" placeholder=" Enter Email" name="Faculty_Email">
          </div>
          <div class="form-group"><br>
            Department Name:<br>
            <select class="input_modify" style="color:black;width:100%;" id="faculty_department" name="faculty_department">
            </select>
          </div>
          <button type="submit" class="btn btn-default" id="Submit">Register</button>
        </form>
      </div>
    </div>
  </div>

  <div class="row">
   <div class="col-sm-12" id="extra">
     <div class="col-sm-2"></div>
     <div class="col-sm-10" id="login-half" style="  color:white;background-color: rgba(3, 8, 75, 0.9);">
       <span style="font-Size:20px;"><h2>Add New Course</h2></span>
       <br>
       <form class="form-inline" style="margin-left:5%" action="" method="post">
         <div class="form-group"><br>
           Course Name:
           <input type="text" class="fill-box " id="elective_name" placeholder=" Enter Elective Name" name="elective_name">
         </div>
         <div class="form-group"><br>
           Course Code:
           <input type="text" class="fill-box " id="course_code" placeholder=" Enter Course Code" name="course_code">
         </div>
         <div class="form-group"><br>
           Number of Credits:
           <input type="text" class="fill-box " id="number_of_credits" placeholder=" Enter Credits" name="number_of_credits">
         </div>
         <div class="form-group"><br>
           Corresponding Year:
           <input type="text" class="fill-box " id="corresponding_year" placeholder=" Corresponding Year" name="corresponding_year">
         </div>
         <div class="form-group"><br>
           Department Name:
           <select class="input_modify"  id="course_department" name="course_department" onChange="department_change(this)">
           </select>
         </div><br><br>
          Faculty Handling:<br>
          <select class="input_modify"  style="width:50%;" id="handling_faculty" name="handling_faculty">
              <option style="width:50%;" value="SELECT">SELECT</option>
          </select>
          <div class="form-group"><br>
           Max Number of Students:
           <input type="text" class="fill-box " id="max_number" placeholder=" Enter Max No of Students" name="max_no">
         </div>
         <button type="submit" class="btn btn-default" id="Submit">Add Course</button>
       </form>
     </div>
   </div>
 </div>

          </div>
            <div class="col-sm-5" id="login-half-even">
                    <div class="row">
                     <div class="col-sm-12" id="extra">
                       <div class="col-sm-2"></div>
                       <div class="col-sm-10" id="login-half" style= " color:white;background-color: rgba(3, 8, 75, 0.9);">
                         <span style="font-Size:20px;"><h2>Remove a Student</h2></span>
                         <br>
                         <form class="form-inline" style="margin-left:5%" action="" method="post">
                           <div class="form-group"><br>
                             SRN:
                             <input type="text" class="fill-box " id="corresponding_year" placeholder=" SRN " name="Remove_Student">
                           </div>
                           <button type="submit" class="btn btn-default" id="Submit" >Delete</button>
                         </form>
                       </div>
                     </div>
                   </div>

                   <div class="row">
                    <div class="col-sm-12" id="extra">
                      <div class="col-sm-2"></div>
                      <div class="col-sm-10" id="login-half" style=  "color:white;background-color: rgba(3, 8, 75, 0.9);">
                        <span style="font-Size:20px;"><h2>Add a Department</h2></span>
                        <br>
                        <form class="form-inline" style="margin-left:5%" action="" method="post">
                          <div class="form-group"><br>
                            Department ID:
                            <input type="text" class="fill-box " id="new_department_id" placeholder=" Department ID " name="new_department_id">
                          </div>
                          <div class="form-group"><br>
                            Department Name:<br>
                            <input type="text" style="color:black;width:100%;" class="fill-box " id="new_department_name" placeholder=" Department Name " name="new_department_name">
                          </div>
                          <div class="form-group"><br>
                            Department Head:<br>
                          <input type="text" style="color:black;width:100%;" class="fill-box " id="new_department_name" placeholder=" Department Head Id " name="new_department_manage">
                          </div>
                          <button type="submit"style="color:black;width:70%;" class="btn btn-default" id="Submit" >Add </button>
                        </form>
                      </div>
                    </div>
                    <div class="row">
                     <div class="col-sm-12" id="extra">
                       <div class="col-sm-2"></div>
                       <div class="col-sm-10" id="login-half" style= " color:white;background-color: rgba(3, 8, 75, 0.9);">
                         <span style="font-Size:20px;"><h2>Send Registration Link to Unregistered Students</h2></span>
                         <br>
                         <form class="form-inline" style="margin-left:5%" action="admin_send_link.php" method="post">
                           <button type="submit" class="btn btn-default" id="Submit" >Send Link</button>
                         </form>
                       </div>
                     </div>
                   </div>
                  </div>
           </div>
    </div>
</body>
</html>
