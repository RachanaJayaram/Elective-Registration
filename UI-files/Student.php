<?php

if(isset($_GET['token']))
  {
      $token=$_GET['token'];
      // echo $token;
      $hashed_token=hash("sha256",$token);
      $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
      if(isset($db))
      {
  
          $str="SELECT SRN,EMAIL_ID FROM STUDENT_LINK_LOGIN WHERE TOKEN = \"".$hashed_token."\";";
          $check=mysqli_query($db,$str);
          $Email_id=mysqli_fetch_array($check);
          $Email_id=$Email_id['EMAIL_ID'];
          $update="UPDATE  TEACHER_LOGIN SET completed=true, TOKEN='NULL' WHERE FACULTY_EMAIL=\"".$Email_id."\";";
          mysqli_query($db, $update);
          // echo $Email_id;

      }
  }

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
      $str="SELECT ELECTIVE_NAME,CORRESPONDING_YEAR,COURSE_CODE,NUMBER_OF_CREDITS,FACULTY_NAME,ELECTIVE.DEPARTMENT_ID,DEPARTMENT_NAME,FACULTY.FACULTY_ID,ELECTIVE.COURSE_CODE FROM ELECTIVE,FACULTY,DEPARTMENT WHERE ELECTIVE.FACULTY_ID=FACULTY.FACULTY_ID AND ELECTIVE.DEPARTMENT_ID=DEPARTMENT.DEPARTMENT_ID";
      $check=mysqli_query($db,$str);
      while($rows=mysqli_fetch_assoc($check))
      {
        $data=$data.$rows['ELECTIVE_NAME'].":::".$rows['CORRESPONDING_YEAR'].":::".$rows['COURSE_CODE'].":::".$rows['NUMBER_OF_CREDITS'].":::".$rows['FACULTY_NAME'].":::".$rows['DEPARTMENT_ID'].":::".$rows['DEPARTMENT_NAME'].":::".$rows['FACULTY_ID']."||||";
      }
      #print_r($data);
      $str1="SELECT DISTINCT CORRESPONDING_YEAR FROM ELECTIVE";
      $check1=mysqli_query($db,$str1);
      while($rows=mysqli_fetch_assoc($check1))
      {
          $data1.=$rows['CORRESPONDING_YEAR']."||||";
      }
      #print_r($data1);
    }
  
}
?>




<?php
$display="";
if($_SERVER["REQUEST_METHOD"] == "POST")
{
 //print_r($_POST);
 extract($_POST);
 if($Name!="" && $SRN!="" &&  $EMAIL!="" && $DEPARTMENT!="SELECT" && $YEAR_OF_STUDY!="SELECT" && $SECTION!="SELECT" && $ELECTIVE1!="SELECT" && $TEACHER1!="SELECT" && $ELECTIVE2!="SELECT" && $TEACHER2!="SELECT")
 {
   $query_srn="SELECT SRN FROM STUDENT_LINK_LOGIN WHERE SRN='".$SRN."'";
   $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
   $check="SELECT * FROM STUDENT WHERE EMAIL_ID='".$EMAIL."';";
   $check=mysqli_query($db,$check);
   $check_srn=mysqli_query($db,$query_srn);
   //print_r($check);
   $check1="SELECT * FROM STUDENT WHERE SRN='".$SRN."';";
   $check1=mysqli_query($db,$check1);
   //print_r($check1);
   if (!filter_var($EMAIL, FILTER_VALIDATE_EMAIL))
   {
     $display = "<script type='text/javascript'>alert('INVALID EMAIL');</script><br>";
   }
   else if(preg_match("/[|!#$%^&*(){}\':?<>;]+/",$SRN))
   {
    $display = "<script type='text/javascript'>alert('INVALID SRN')</script><br>";
   }
   else if(mysqli_num_rows($check)>0 || mysqli_num_rows($check1)>0)
   {
     $display = "<script type='text/javascript'>alert('YOU HAVE ALREADY REGISTERED')</script><br>";
   }
   elseif (mysqli_num_rows($check_srn)==0) 
   {
    $display = "<script type='text/javascript'>alert('Enter YOUR SRN')</script><br>";

   }
   else
   {
     $insert="INSERT INTO STUDENT(NAME_OF_THE_STUDENT,SRN,EMAIL_ID,YEAR_OF_STUDY,SECTION,DEPARTMENT_ID,COURSE_CODE_1,COURSE_CODE_2,FACULTY_ID_1,FACULTY_ID_2) VALUES('".$Name."','".$SRN."','".$EMAIL."',".$YEAR_OF_STUDY.",'".$SECTION."','".$DEPARTMENT."','".$ELECTIVE1."','".$ELECTIVE2."','".$TEACHER1."','".$TEACHER2."');";
     $result=mysqli_query($db,$insert);
     $display = "<script type='text/javascript'>alert('YOU HAVE SUCCESSFULLY REGISTERED')</script><br>";
     $insert="UPDATE STUDENT_LINK_LOGIN SET TOKEN='NULL',completed=true WHERE SRN=\"".$SRN."\";"; 
     $result=mysqli_query($db,$insert);
    //  echo $insert;

    }
 }
 else
 {
   if($Name=="")
   {
     $display="<script type='text/javascript'>alert('FILL IN THE NAME PLEASE');</script><br>";
   }
   else if($SRN=="")
   {
     $display="<script type='text/javascript'>alert('FILL IN THE SRN ');</script><br>";
   }
   else if($EMAIL=="")
   {
     $display="<script type='text/javascript'>alert('FILL IN THE EMAIL');</script><br>";
   }
   else if($DEPARTMENT=="")
   {
     $display="<script type='text/javascript'>alert('PLEASE SELECT A DEPARTMENT');</script><br>";
   }
   else if($YEAR_OF_STUDY=="")
   {
     $display="<script type='text/javascript'>alert('PLEASE SELECT A YEAR OF STUDY');</script><br>";
   }
   else if($SECTION=="")
   {
     $display="<script type='text/javascript'>alert('PLEASE SELECT A SECTION');</script><br>";
   }
   else if($ELECTIVE1=="")
   {
     $display="<script type='text/javascript'>alert('PLEASE SELECT THE CORRECT 1ST ELECTIVE');</script><br>";
   }
   else if($ELECTIVE2=="")
   {
     $display="<script type='text/javascript'>alert('PLEASE SELECT THE CORRECT 2ND ELECTIVE');</script><br>";
   }
   else if($TEACHER1=="")
   {
     $display="<script type='text/javascript'>alert('PLEASE CHOOSE A TEACHER FOR 1ST ELECTIVE');</script><br>";
   }
   else if($TEACHER2=="")
   {
     $display="<script type='text/javascript'>alert('PLEASE CHOOSE A TEACHER FOR THE 2ND ELECTIVE');</script><br>";
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
    //console.log(data1);
    data1=data1.split("||||");
          var selectedyear=document.getElementById("yearofstudy");
          newelement=document.createElement("option");
          newelement.className="selected_semester";
          newelement.value="SELECT";
          newelement.innerHTML="SELECT";
          selectedyear.appendChild(newelement);
      for(var i=0;i<data1.length-1;i++)
      {
        newelement=document.createElement("option");
        newelement.className="selected_semester";
        newelement.value=data1[i];
        newelement.innerHTML=data1[i];
        selectedyear.appendChild(newelement);
      }
  }
  function Teachers()
  {
  }
  function Department(sel) //WHEN DEPARTMENT IS SELECTED
  {
    department=sel.options[sel.selectedIndex].text;
    var yearelement=document.getElementsByClassName("elective1stOption");
    for(var j=0;j<yearelement.length;j++)
    {
      yearelement[j].style.display="none";
    }
      var dummy=document.getElementById("dummy");
      var selectedyear=document.getElementById("yearofstudy");  selectedyear.value=dummy.value;
      var elective1=document.getElementById("elective1");       elective1.value=dummy.value;
      var elective2=document.getElementById("elective2");       elective2.value=dummy.value;
      var teacherop1=document.getElementById("teacherop1");     teacherop1.value=dummy.value;
      var teacherop2=document.getElementById("teacherop2");     teacherop2.value=dummy.value;
  }
  function myNewFunction(sel) //WHEN A YEAR IS SELECTED
  {
    selectedyear=sel.options[sel.selectedIndex].text;


    var data="<?php echo $data;?>";
    var elements=data.split("||||");
    var elective1=document.getElementById("elective1");
    var elective2=document.getElementById("elective2");
    var dummy=document.getElementById("dummy");             elective1.value=dummy.value;      teacherop1.value=dummy.value;     elective2.value=dummy.value;    teacherop2.value=dummy.value;


    var teachers=new Array();
    var count=0;
    for(var i=0;i<elements.length-1;i++)
    {
      ele=elements[i].split(":::");
      teachers[count++]=ele;
    }
    if(selectedyear=="SELECT")
    {
      var yearelement=document.getElementsByClassName("elective1stOption");
      for(var j=0;j<yearelement.length;j++)
      {
        yearelement[j].style.display="none";
      }
    }
    else
    {
        var yearelement=document.getElementsByClassName("elective1stOption");
        for(var j=0;j<yearelement.length;j++)
        {
          yearelement[j].style.display="none";
        }
        var set1= new Set();
        for(var j=0;j<teachers.length;j++)
        {
          console.log(teachers[j]);

          if(teachers[j][6])
          {
            if(teachers[j][1]==selectedyear && department==teachers[j][6] && teachers[j][2][7]==3)
            {
              if(set1.has(teachers[j][0]))
              {
                  ;
              }
              else {
                    set1.add(teachers[j][0])
                    var elective1=document.getElementById("elective1");
                    newelement=document.createElement("option");
                    newelement.className="elective1stOption";
                    newelement.value=teachers[j][2];
                    newelement.innerHTML=teachers[j][0];
                    elective1.appendChild(newelement);}
            }
        }
      }
    }
  }
  function teacher1(sel)
  {
    var selected=sel.options[sel.selectedIndex].text;
    var teacherop1=document.getElementById("teacherop1");
    teacherop1.value=dummy.value;
    var elements=data.split("||||");
    var subjects=new Array();
    var count=0;
    courseelement=document.getElementsByClassName("teacherop1");
    for(var i=0;i<courseelement.length;i++)
    {
      courseelement[i].style.display="none";
    }
    for(var i=0;i<elements.length-1;i++)
    {
      ele=elements[i].split(":::");
      subjects[count++]=ele;
      if(selected==subjects[i][0])
      {
        newelement=document.createElement("option");
        newelement.className="teacherop1";
        newelement.innerHTML=subjects[i][4];
        newelement.value=subjects[i][7];
        teacherop1.appendChild(newelement);
      }
    }
    myNewFunction2();
  }
function myNewFunction2()
{
  var data="<?php echo $data;?>";
  var elements=data.split("||||");
  var elective2=document.getElementById("elective2");
  var dummy=document.getElementById("dummy");
  elective2.value=dummy.value;
  teacherop2.value=dummy.value;
  var teachers=new Array();
  var count=0;
  for(var i=0;i<elements.length-1;i++)
  {
    ele=elements[i].split(":::");
    teachers[count++]=ele;
  }
  if(selectedyear=="SELECT")
  {
    var yearelement=document.getElementsByClassName("elective2ndOption");
    for(var j=0;j<yearelement.length;j++)
    {
      yearelement[j].style.display="none";
    }
    var yearelement=document.getElementsByClassName("elective2ndOption");
    for(var j=0;j<yearelement.length;j++)
    {
      yearelement[j].style.display="none";
    }
  }
  else
  {
      var yearelement=document.getElementsByClassName("elective2ndOption");
      for(var j=0;j<yearelement.length;j++)
      {
        yearelement[j].style.display="none";
      }
      var set1= new Set();
      for(var j=0;j<teachers.length;j++)
      {
        //console.log(teachers[j]);
        if(teachers[j][6])
        {
          //console.log(teachers[j][2][7]);
          if(teachers[j][1]==selectedyear && department==teachers[j][6] && teachers[j][2][7]==4)
          {
            if(set1.has(teachers[j][0]))
            {
                    ;
            }
            else{
                      set1.add(teachers[j][0]);
                      var elective2=document.getElementById("elective2");
                      newelement=document.createElement("option");
                      newelement.className="elective2ndOption";
                      newelement.value=teachers[j][2];
                      newelement.innerHTML=teachers[j][0];
                      elective2.appendChild(newelement);}
          }
        }
      }
  }
}
function teacher2(sel)
{
  var selected=sel.options[sel.selectedIndex].text;
  var teacherop2=document.getElementById("teacherop2");
  var elements=data.split("||||");
  var elective1=document.getElementById("elective1");
  var elective2=document.getElementById("elective2");
  var subjects=new Array();
  teacherop2.value=dummy.value;
  var count=0;
  courseelement=document.getElementsByClassName("teacherop2");
  for(var i=0;i<courseelement.length;i++)
  {
    courseelement[i].style.display="none";
  }
  for(var i=0;i<elements.length-1;i++)
  {
    ele=elements[i].split(":::");
    subjects[count++]=ele;
    if(selected==subjects[i][0])
    {
      newelement=document.createElement("option");
      newelement.className="teacherop2";
      newelement.innerHTML=subjects[i][4];
      newelement.value=subjects[i][7];
      teacherop2.appendChild(newelement);
    }
  }
}
-->
</script>
<html>
<head>
<link rel="javascript" href="student.js" type="text/javascript"/>
<link rel="stylesheet" type="text/css" media="screen" href="Student.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<title>
  Student Login
</title>
</head>
<body onload="onload()">
<div class= "row login-header">
    <div class="col-sm-12" style="background-color:white; opacity:0.9">
      <img src="./images/logo.jpg" alt="PES UNIVERSITY" id="logo">
      </div>
    </div>
</div>
<div class="row login-body" id="part1">
<div class="col-sm-6 lgn-wrapper-half" id="login-half">
  <div class="col-sm-12">
    <div class="col-sm-offset-1">
    <div class="form-inline"><span style="font-size:30px;">STUDENT FORM<?php echo $display; ?></span>
    <hr id="seperator"></div>
  </div>
    <div class="col-sm-offset-1">
      <form class="form-inline" action="" method="post">
      <div class="form-group"> <br>
        &emsp;<span style="10%">NAME :</span>
        <input type="text" class="fill-box " id="Name" placeholder=" Enter Name" name="Name">
      </div>
      <div class="form-group"><br>
      &emsp;<span style="10%">S.R.N: </span>
        <input type="text" class="fill-box  " id="SRN" placeholder=" Enter SRN" name="SRN">
      </div>
      <br>
      <div class="form-group"><br>
      &emsp;<span style="10%">EMAIL:<span>
        <input type="email" class="fill-box"  readonly = "readonly" id="EMAIL" placeholder=" Enter EMAIL" name="EMAIL" value=" <?php echo $Email_id; ?>">
      </div>
      <br>
      <div style="margin-top:3%">
      <div class="col-sm-8">DEPARTMENT:</div>
      <div class="col-sm-4">
        <select class="input_modify" id="department" onChange="Department(this)" name="DEPARTMENT">
          <option value="SELECT">SELECT</option>
          <option value="01">CSE</option>
          <option value="02">ECE</option>
          <option value="03">ME</option>
          <option value="04">EEE</option>
        </select>
      </div>
      <div class="col-sm-8"> YEAR OF STUDY:</div>
      <div class="col-sm-4">
        <select class="input_modify" id="yearofstudy" onChange="myNewFunction(this)" name="YEAR_OF_STUDY">
        </select>
      </div>
        <div class="col-sm-8">  SECTION:</div>
        <div class="col-sm-4"><select class="input_modify" id="select" name="SECTION">
          <option value="A">A</option>
          <option value="B">B</option>
          <option value="C">C</option>
          <option value="D">D</option>
          <option value="E">E</option>
          <option value="F">F</option>
          <option value="G">G</option>
          <option value="H">H</option>
        </select>
      </div>
        <div class="col-sm-8">ELECTIVE 1:</div>
        <div class="col-sm-4">
          <select class="input_modify" id="elective1" onChange="teacher1(this)" NAME="ELECTIVE1">
          <option value="SELECT">SELECT</option>
        </select>
      </div>
          <div class="col-sm-8">TEACHER 1:</div>
        <div class="col-sm-4"><select class="input_modify" id="teacherop1" NAME="TEACHER1">
          <option value="SELECT">SELECT</option>
        </select>
      </div>
      <br>
        <div class="col-sm-8">ELECTIVE 2:</div>
        <div class="col-sm-4"><select class="input_modify" id="elective2" onChange="teacher2(this)" NAME="ELECTIVE2">
        <option value="SELECT">SELECT</option>
      </select>
    </div>
        <div class="col-sm-8">TEACHER 2:</div>
      <div class="col-sm-4"><select class="input_modify" id="teacherop2" NAME="TEACHER2">
        <option value="SELECT">SELECT</option>
      </select>
    </div>
  </div>
    <br>
    <select style="display:none;"class="form-control" id="dummy">
      <option value="SELECT">SELECT</option>
    </select>
      <button type="submit" class="btn btn-default" id="Submit">Submit</button>
    </form>
    </div>
  </div>
</div>
  <div class="col-sm-6" id="login-half-even">
    <div class="col-sm-12" id="extra">
    <div class="col-sm-2"></div>
    <div class="col-sm-8" id="links">
      <a href="">Course Info</a>
      <br>
      <a href="">Study Material</a>

      <br>
      <a href="">Contact-Us</a>
    </div>
  </div>
</div>
</div>
</body>
</html>
