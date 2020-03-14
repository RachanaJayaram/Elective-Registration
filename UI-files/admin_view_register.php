<?php
  $display="";
  $data="";
  $data1="";
  $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
  if(isset($db))
  {
    $str="SELECT * FROM FACULTY;";
    $check=mysqli_query($db,$str);
    if(mysqli_num_rows($check)>=1)
    {
      $str="SELECT ELECTIVE_NAME,CORRESPONDING_YEAR,COURSE_CODE,NUMBER_OF_CREDITS,ELECTIVE.FACULTY_ID,ELECTIVE.DEPARTMENT_ID,DEPARTMENT_NAME,FACULTY.FACULTY_ID,ELECTIVE.COURSE_CODE FROM ELECTIVE,FACULTY,DEPARTMENT WHERE ELECTIVE.FACULTY_ID=FACULTY.FACULTY_ID AND ELECTIVE.DEPARTMENT_ID=DEPARTMENT.DEPARTMENT_ID";
      #echo $str;
      $check=mysqli_query($db,$str);
      while($rows=mysqli_fetch_assoc($check))
      {
        $data=$data.$rows['ELECTIVE_NAME'].":::".$rows['CORRESPONDING_YEAR'].":::".$rows['COURSE_CODE'].":::".$rows['NUMBER_OF_CREDITS'].":::".$rows['FACULTY_ID'].":::".$rows['DEPARTMENT_ID'].":::".$rows['DEPARTMENT_NAME'].":::".$rows['FACULTY_ID']."||||";
      }
      print_r($data);
      $str1="SELECT DISTINCT CORRESPONDING_YEAR FROM ELECTIVE";
      $check1=mysqli_query($db,$str1);
      while($rows=mysqli_fetch_assoc($check1))
      {
          $data1.=$rows['CORRESPONDING_YEAR']."||||";
      }
    }
  }
?>




<?php
$display="";
$final_data="";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
 extract($_POST);
 #print_r($_POST);
 if($DEPARTMENT!="SELECT" && $YEAR_OF_STUDY!="SELECT" &&  $ELECTIVE!="SELECT" && $TEACHER!="SELECT")
 {
   $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
   $str="SELECT * FROM STUDENT WHERE DEPARTMENT_ID='".$DEPARTMENT."' AND YEAR_OF_STUDY=".$YEAR_OF_STUDY." AND ((COURSE_CODE_1='".$ELECTIVE."' AND FACULTY_ID_1='".$TEACHER."') OR (COURSE_CODE_2='".$ELECTIVE."' OR FACULTY_ID_2='".$TEACHER."'));";
   #print_r($str);
   $check=mysqli_query($db,$str);
   while($rows=mysqli_fetch_assoc($check))
   {
    # print_r($rows);
     $final_data.="<tr><td>".$rows['NAME_OF_THE_STUDENT']."</td><td>".$rows['SRN']."</td><td>".$rows['EMAIL_ID']."</td></tr>";
   }
  # print_r($final_data);
  }
 else
 {
   if($DEPARTMENT=="SELECT")
   {
     $display="<script type='text/javascript'>alert('SELECT DEPARTMENT');</script><br>";
   }
   else if($YEAR_OF_STUDY=="SELECT")
   {
     $display="<script type='text/javascript'>alert('SELECT YEAR');</script><br>";
   }
   else if($ELECTIVE=="SELECT")
   {
     $display="<script type='text/javascript'>alert('SELECT ELECTIVE');</script><br>";
   }
   else if($TEACHER=="SELECT")
   {
     $display="<script type='text/javascript'>alert('SELECT TEACHER');</script><br>";
   }
 }
}
?>




<script type="text/javascript">
<!--
  data="<?php echo $data;?>";
  department="SELECT";
  function onload()
  {
    var data1="<?php echo $data1;?>"
    data1=data1.split("||||");
    console.log(data1);
          previous_year=document.getElementsByClassName("selected_semester");
          for(var i=0;i<previous_year.length;i++)
          {
              previous_year[i].style.display="none";
          }
          var selectedyear=document.getElementById("yearofstudy");
          newelement=document.createElement("option");
          newelement.className="selected_semester";
          newelement.value="SELECT";
          newelement.innerHTML="SELECT";
          selectedyear.appendChild(newelement);
          selectedyear.value=dummy.value;
      for(var i=0;i<data1.length-1;i++)
      {
        newelement=document.createElement("option");
        newelement.className="selected_semester";
        newelement.value=data1[i];
        newelement.innerHTML=data1[i];
        selectedyear.appendChild(newelement);
      }
  }
  function Department(sel)
  {
    onload();
    year_change();
    elective_change();
  }
  function year_change(sel)
  {
    var data2=data.split("||||");
    //console.log(data2);
    var yearofstudy=document.getElementById("yearofstudy");
    var elective=document.getElementById("elective_chosen");
    elective.value=dummy.value;
    var previous_elective=document.getElementsByClassName("chosen_elective");
    for(var i=0;i<previous_elective.length;i++)
    {
      previous_elective[i].style.display="none";
    }
    for(var i=0;i<data2.length-1;i++)
    {
      var ele=data2[i].split(":::");
      console.log(ele);
      if(yearofstudy.value==ele[1] && department_selected.value==ele[5])
      {
          var newelement=document.createElement("option");
          newelement.className="chosen_elective";
          newelement.value=ele[2];
          newelement.innerHTML=ele[0];
          elective.appendChild(newelement);
      }
    }
    elective_change();
  }
  function elective_change(sel)
  {
    var data2=data.split("||||");
    var teacher=document.getElementById("teacher_chosen");
    teacher.value=dummy.value;
    var previous_teachers=document.getElementsByClassName("teacher_available");
    for(var i=0;i<previous_teachers.length;i++)
    {
      previous_teachers[i].style.display="none";
    }
    for(var i=0;i<data2.length-1;i++)
    {
      var ele=data2[i].split(":::");
      if(yearofstudy.value==ele[1] && elective_chosen.value==ele[2])
      {
          var newelement=document.createElement("option");
          newelement.className="teacher_available";
          newelement.value=ele[7];
          newelement.innerHTML=ele[4];
          teacher.appendChild(newelement);
      }
    }

  }
-->
</script>
<html>
<head>
<link rel="javascript" href="student.js" type="text/javascript"/>
<link rel="stylesheet" type="text/css" media="screen" href="admin_view_register.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>
  Student Login
</title>
</head>
<body onload="onload()">
<div style="background-color:white; opacity:0.9">
                    <img src="./images/logo.jpg" height="60px" alt="PES UNIVERSITY">
</div>
<div class="container" id="part1">
<div class="row">
  <?php echo $display; ?>
  <form class="form-inline" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <div style="margin-top:3%">
    <div class="col-sm-3"> DEPARTMENT</div>
    <div class="col-sm-3"> &nbsp;YEAR </div>
    <div class="col-sm-3"> ELECTIVE </div>
    <div class="col-sm-3"> TEACHER </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-3">
      <select class="input_modify" id="department_selected" onChange="Department(this)" name="DEPARTMENT">
        <option value="SELECT">SELECT</option>
        <option value="1">CSE</option>
        <option value="2">ECE</option>
        <option value="3">ME</option>
        <option value="4">EEE</option>
      </select>
    </div>
    <div class="col-sm-3">
      <select class="input_modify" id="yearofstudy" name="YEAR_OF_STUDY" onChange="year_change(this)">
      </select>
    </div>
    <div class="col-sm-3">
      <select class="input_modify" id="elective_chosen" onChange="elective_change(this)"NAME="ELECTIVE">
      <option value="SELECT">SELECT</option>
      </select>
    </div>
    <div class="col-sm-3">
      <select class="input_modify" id="teacher_chosen"  NAME="TEACHER">
      <option value="SELECT">SELECT</option>
      </select>
      <select style="display:none;"class="form-control" id="dummy">
        <option value="SELECT">SELECT</option>
      </select>
    </div>
</div>
  <br>
  <div class="row">
    <div class="col-sm-offset-4">
    <div class="col-sm-4">
      <button type="submit" class="btn btn-default" id="Submit">Submit</button>
    </div>
  </div>
  </form>
  </div>
</div>
<div class="row">
  <br>
  <br>
  <br>
  <div class="col-sm-offset-2">
  <div class="col-sm-10">
  <table  class="table table-hover custom_table">
    <tr>
    <th >NAME</th>
    <th>SRN</th>
    <th>EMAIL_ID</th>
    </tr>
    <?php echo $final_data;?>
  </table>
</div>
</div>
</div>
</body>
</html>
