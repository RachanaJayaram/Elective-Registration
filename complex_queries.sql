-- Teacher_view : count of total students handled by the teacher
SELECT COUNT(*)
FROM  
    (SELECT * 
    FROM STUDENT 
    WHERE (COURSE_CODE_2,FACULTY_ID_2) in 
                                      (SELECT COURSE_CODE ,FACULTY_ID 
                                        FROM elective 
                                        WHERE FACULTY_ID= 
                                                      ( SELECT FACULTY_ID 
                                                        FROM FACULTY 
                                                        WHERE FACULTY_EMAIL =\"".$email."\"
                                                        )
                                     ) 
                                     
    UNION 
    SELECT * 
    FROM STUDENT 
    WHERE (COURSE_CODE_1,FACULTY_ID_1) in 
                                       (SELECT COURSE_CODE ,FACULTY_ID 
                                        FROM elective WHERE FACULTY_ID= 
                                                                    ( SELECT FACULTY_ID 
                                                                      FROM FACULTY 
                                                                      WHERE FACULTY_EMAIL =\"".$email."\"
                                                                    )
                                        )
    ) as T;

-- teacher_view select all students registered for that elective under that teacher
SELECT SRN,NAME_OF_THE_STUDENT,EMAIL_ID
FROM STUDENT 
WHERE (COURSE_CODE_1,FACULTY_ID_1) in
    (
        SELECT COURSE_CODE,faculty.FACULTY_ID
        FROM ( elective INNER JOIN FACULTY on elective.FACULTY_ID =faculty.FACULTY_ID )
        WHERE FACULTY_EMAIL=\"".$email."\" and ELECTIVE_NAME='".$this_elective['ELECTIVE_NAME']."' 
    
    )
UNION
SELECT SRN,NAME_OF_THE_STUDENT,EMAIL_ID  FROM STUDENT 
WHERE (COURSE_CODE_2,FACULTY_ID_2) in
    (
        SELECT COURSE_CODE,faculty.FACULTY_ID
        FROM ( elective INNER JOIN FACULTY on elective.FACULTY_ID =faculty.FACULTY_ID)
        WHERE FACULTY_EMAIL=\"".$email."\"and ELECTIVE_NAME='".$this_elective['ELECTIVE_NAME']."'
    );

-- teacher_view get count of all students registered for that elective under that teacher
SELECT COUNT(*)
FROM
    (SELECT SRN,NAME_OF_THE_STUDENT,EMAIL_ID
    FROM STUDENT 
    WHERE (COURSE_CODE_1,FACULTY_ID_1) in
        (
            SELECT COURSE_CODE,faculty.FACULTY_ID
            FROM ( elective INNER JOIN FACULTY on elective.FACULTY_ID =faculty.FACULTY_ID )
            WHERE FACULTY_EMAIL=\"".$email."\" and ELECTIVE_NAME='".$this_elective['ELECTIVE_NAME']."' 
        
        )
    UNION
    SELECT SRN,NAME_OF_THE_STUDENT,EMAIL_ID  FROM STUDENT 
    WHERE (COURSE_CODE_2,FACULTY_ID_2) in
        (
            SELECT COURSE_CODE,faculty.FACULTY_ID
            FROM ( elective INNER JOIN FACULTY on elective.FACULTY_ID =faculty.FACULTY_ID)
            WHERE FACULTY_EMAIL=\"".$email."\"and ELECTIVE_NAME='".$this_elective['ELECTIVE_NAME']."'
        );
    ) as T;

-- teacher_view Select faculty members that are teaching the same course as the given faculty
SELECT FACULTY_NAME 
FROM FACULTY
WHERE FACULTY_ID IN 
                    (SELECT FACULTY_ID  
                     FROM ELECTIVE 
                     WHERE COURSE_CODE=\"".$this_elective['COURSE_CODE']."\"
                    )  
      and FACULTY_EMAIL!=\"".$email."\"; 
--Count of all teachers teaching the same elective
SELECT COUNT(*) 
FROM FACULTY
WHERE FACULTY_ID IN 
                    (SELECT FACULTY_ID  
                     FROM ELECTIVE 
                     WHERE COURSE_CODE=\"".$this_elective['COURSE_CODE']."\"
                    )  
     


