<?php
    session_start();
    $email=$_SESSION["email_id"];   
    $db=mysqli_connect('localhost:3306','root','','ELECTIVE');
    if(isset($db))
    {
        $str="SELECT * FROM faculty WHERE FACULTY_EMAIL = \"".$email."\";";
        // echo $str;
        $check=mysqli_query($db,$str);
        $deets=mysqli_fetch_array($check);
        
        $subjects="SELECT ELECTIVE_NAME FROM elective WHERE FACULTY_ID=\"".$deets['FACULTY_ID']."\";";
        // $subjects="SELECT ELECTIVE_NAME FROM elective WHERE FACULTY_ID=CSF16";

        $check=mysqli_query($db,$subjects);        
        $count_electives=mysqli_num_rows($check);

    }   

?>
<!DOCTYPE html>
<html>
<head>
	<title>Elective Registration</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="teacher_view.css">
</head>
    <body>
            <div style="background-color:white; opacity:0.9">
                    <img src="./images/logo.jpg" height="60px" alt="PES UNIVERSITY">
            </div>
            <div class="container-fluid" id="full_window">
                <div class="row">
                    <div class="col-sm-2" id="side_bar">
                    <div>
                    <img src="./images/pes.png" height="80px" width="130px" alt="PES UNIVERSITY">
                    <br><br>
                    </div>
                    <div class="table-responsive">
    
                    <table id="deet" class="table table-striped table-borderless " >
                        <thead>
                        </thead>
                        <tbody style=";">
                            <tr>    <td style="overflow:auto;word-wrap: break-word;">
                            <?php echo$deets['FACULTY_NAME'] ?>
                            </td>  </tr>
                           
                            <tr> <td style="overflow:auto;word-wrap: break-word;">
                            <?php echo$deets['FACULTY_ID'] ?>
                            </td> </tr>

                            <tr> <td style="overflow:auto;word-wrap: break-word;">
                            <small>	<?php echo$deets['FACULTY_EMAIL'] ?></small>
                            </td> </tr>

                            <tr> <td style="overflow:auto;word-wrap: break-word;">
                            <br> 
                            </td> </tr>
                                
                            <tr> <td style="overflow:auto;word-wrap: break-word;">
                            <small>Electives undertaken : <?php echo $count_electives?></small>
                            </td></tr>

                            <tr><td style="overflow:auto;word-wrap: break-word;">
                            <big>Courses : </big>
                            </td></tr>
                            <?php
                                for ($i=0; $i <$count_electives ; $i++) { 
                                   $elective = mysqli_fetch_array($check);
                                   echo "<tr><td style='overflow:auto;word-wrap: break-word;'><small>".$elective['ELECTIVE_NAME']."</td></tr>";
                                    
                                } 
                                $query="
                                SELECT COUNT(*)
FROM  (SELECT * FROM STUDENT WHERE (COURSE_CODE_2,FACULTY_ID_2) in (SELECT COURSE_CODE ,FACULTY_ID FROM elective WHERE FACULTY_ID= ( SELECT FACULTY_ID FROM FACULTY WHERE FACULTY_EMAIL =\"".$email."\")) UNION SELECT * FROM STUDENT WHERE (COURSE_CODE_1,FACULTY_ID_1) in (SELECT COURSE_CODE ,FACULTY_ID FROM elective WHERE FACULTY_ID= ( SELECT FACULTY_ID FROM FACULTY WHERE FACULTY_EMAIL =\"".$email."\"))) as T;";
                                
                                // echo $query;
                                $check=mysqli_query($db,$query);
                                $total_count=mysqli_fetch_array($check);
                                // print_r($total_count);
                                echo "<tr><td style='overflow:auto;word-wrap: break-word;'>Total no of students:<br>".$total_count[0]."</td></tr>";
                        ?>

                        </tbody>
                    </table>

                    </div>
                      <a href="teacher_logout.php" class="btn login_btn" id="login_btn" >Logout </a> 

                    </div>
                    <div class="col-sm-10" id="main">
                        <div class="container-fluid" id="dashboard">
                            <div class="row">
                                
                            <div class="col-sm-12" >
                                    Your Courses
                                    <hr>
                                </div>

                                <?php
                                    $query ="SELECT * FROM ELECTIVE WHERE FACULTY_ID= (SELECT FACULTY_ID FROM FACULTY WHERE FACULTY_EMAIL=\"".$email."\");";
                                    $all_electives=mysqli_query($db,$query);
                                    $count_electives=mysqli_num_rows($all_electives);
                                    for($i=0;$i<$count_electives;$i++)
                                    {
                                        $this_elective=mysqli_fetch_array($all_electives);
                                        $query1 ="SELECT FACULTY_NAME FROM FACULTY WHERE FACULTY_ID IN (SELECT FACULTY_ID  
                                            FROM ELECTIVE WHERE COURSE_CODE=\"".$this_elective['COURSE_CODE']."\"
                                        )  
                                         and FACULTY_EMAIL!=\"".$email."\"; ";
                                        //  echo $query1;
                                         $all_teachers=mysqli_query($db,$query1);
                                        echo "
                                        
                                        <table class='table table-striped table-borderless'>

                                            <thead style='background-color:azure;font-size:12px;'>
                                                <tr>
                                                <th style='color:azure;background-color:#0091cd;'>
                                                <h4>".$this_elective['ELECTIVE_NAME']."<h4> 
                                                </th>
                                                </tr>
                                                <tr>
                                                <th>
                                                Course Code : ".$this_elective['COURSE_CODE']."</th><th>Max Students : ".$this_elective['MAX_NO_OF_STUDENTS']."</th>
                                                <th>Credits : ".$this_elective['NUMBER_OF_CREDITS']."</th>";
                                                $count=mysqli_num_rows($all_teachers)+1;
                                                $string ="";
                                                if(mysqli_num_rows($all_teachers)>=1)
                                                {
                                                    $co=mysqli_fetch_array($all_teachers);
                                                    $string=$co['FACULTY_NAME'];
                                                for($j=0;$j<mysqli_num_rows($all_teachers)-1;$j++)
                                                {
                                                    $co=mysqli_fetch_array($all_teachers);
                                                    
                                                    $string =$string." , ".$co['FACULTY_NAME'];
                                                }
                                                }

                                                else    $string= "None";  

                                                echo "</tr><tr style='background-color:azure'>
                                                <th>No of teachers : ".$count."</th>
                                                <th colspan='2'>
                                                Co teachers : ";
                                                echo $string;
                                                echo "</th></tr><tr><th colspan='3' style='background-color:azure;color:#0091cd;'> No of Students Enrolled : " ;
                                                $query2="SELECT SRN,NAME_OF_THE_STUDENT,EMAIL_ID FROM STUDENT 
                                                WHERE
                                                (COURSE_CODE_1,FACULTY_ID_1) in(
                                                SELECT COURSE_CODE,faculty.FACULTY_ID
                                                FROM ( elective INNER JOIN FACULTY on elective.FACULTY_ID =faculty.FACULTY_ID )
                                                WHERE FACULTY_EMAIL=\"".$email."\" and ELECTIVE_NAME='".$this_elective['ELECTIVE_NAME']."' 
                                            
                                                )
                                                UNION
                                                SELECT SRN,NAME_OF_THE_STUDENT,EMAIL_ID  FROM STUDENT 
                                                WHERE
                                                (COURSE_CODE_2,FACULTY_ID_2) in(
                                                SELECT COURSE_CODE,faculty.FACULTY_ID
                                                FROM ( elective INNER JOIN FACULTY on elective.FACULTY_ID =faculty.FACULTY_ID)
                                                WHERE FACULTY_EMAIL=\"".$email."\"and ELECTIVE_NAME='".$this_elective['ELECTIVE_NAME']."'
                                                );";
                                                // echo $query2;
                                                $student=mysqli_query($db,$query2);
                                                echo mysqli_num_rows($student)."</th></tr><tr  style='color:azure;background-color:#0091cd;'> ";

                                                echo "<br><th scope='col'>SRN</th>
                                                <th scope='col'>Name</th>
                                                <th scope='col'>Email</th> </tr>
                                               </thead>
                                               <tbody style='font-size:12px;'>";

                                                for ($k=0; $k < mysqli_num_rows($student); $k++) 
                                                { 
                                                    $student_list=mysqli_fetch_array($student);
                                                    echo "<tr><td colspan='1'>".$student_list['SRN']."</td><td>".$student_list['NAME_OF_THE_STUDENT']."</td><td>".$student_list['EMAIL_ID']."</td></tr>";
                                                }
                                                echo "</tbody> ";
                                            }
                                            

                                            ?>
                                    

                                    

                                                                                
                            
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </body>
</html>
                