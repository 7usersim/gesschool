<?php
namespace App\Functions;

    class ConfigService {

        public static function getAllUsersWithRole(){
            $sql = " SELECT
                    u.first_name AS FirstName,
                    u.matricule AS Matricule,
                    u.email AS Email,
                    u.phone_number AS Phone,
                    u.last_name AS LastName,
                    r.role_name AS Role,
                    u.status AS STATUS
                FROM
                    gsc_users AS u INNER JOIN gsc_roles AS r ON u.roles_id=r.id
                WHERE
                    r.status_role='Actif' AND 1=1 ";
                    return $sql;
        }

        public static function getAllFilieresWithCycle(){
            $sql = " SELECT
                    f.id AS id,
                    f.nom AS NameFiliere,
                    f.code AS CodeFiliere,
                    f.description AS DescriptionFiliere,
                    c.name AS NameCycle,
                    c.id AS IDCycle,
                    e.name AS NameSchool,
                    e.id AS IDSchool,
                    u.first_name AS FirstNameUser,
                    u.id AS IDUser
                FROM
                    gsc_filiere AS f INNER JOIN gsc_cycle AS c
                    INNER JOIN gsc_etablissement AS e
                    INNER JOIN gsc_users AS u
                    ON f.cycle_id=c.id AND f.school_id = e.id AND f.user_id = u.id
                 AND 1=1 ";
                    return $sql;
        }

        public static function getAllClasses(){
               $sql = " SELECT
                        c.id AS id,
                        c.name AS NameClass,
                        c.school_fees AS SchoolFees,
                        f.nom AS NameField,
                        f.id AS IDField,
                        u.first_name AS NameUser,
                        u.id AS UserID
                    FROM
                        gsc_classes As c INNER JOIN gsc_filiere AS f
                        INNER JOIN gsc_users AS u ON c.field_id = f.id AND c.user_id = u.id
                    AND  1=1";
                    return $sql;
                  }

        public static function getAllStudents(){
               $sql = " SELECT
                        s.id AS id,
                        s.matricule AS Matricule,
                        s.firstname AS FirstName,
                        s.lastname AS LastName,
                        s.sexe AS Sexe,
                        s.date_birth AS DateBirth,
                        s.email AS Email,
                        s.parent_name AS ParentName,
                        s.address AS Address,
                        c.name AS CycleName,
                        c.id AS IDCycle,
                        f.nom AS NameField,
                        f.id AS IDField,
                        e.name AS NameClass,
                        e.id AS IDClass
                        FROM gsc_students AS s INNER JOIN gsc_cycle AS c
                        INNER JOIN gsc_filiere AS f
                        INNER JOIN gsc_classes AS e ON s.id_cycle = c.id
                        AND s.id_field = f.id AND s.id_classe = e.id
                        AND 1=1";
                        return $sql;
                 }

            public static function getFees(){
                  $sql = " SELECT
                        f.id AS id,
                        f.paid AS Paid,
                        f.left_to_pay AS LeftToPay,
                        f.payment_date AS PaymentDate,
                        f.payment_method AS PaymentMethod,
                        f.payment_status AS PaymentStatus,
                        f.payment_reference AS PaymentReference,
                        s.firstname AS StudentFirstname,
                        s.lastname AS StudentLastname,
                        s.sexe AS StudentSexe,
                        s.id AS idStudent,
                        c.name AS NameClass,
                        c.school_fees AS SchoolFees,
                        c.id AS IDClass
                    FROM
                        gsc_frais As f INNER JOIN gsc_students AS s
                        ON f.student_id = s.id
                        INNER JOIN gsc_classes AS c
                        ON f.class_id = c.id
                    AND  1=1";
                    return $sql;
                }

            public static function getAllCourses(){
              $sql = " SELECT
                        c.id AS id,
                        c.name AS NameCourse,
                        c.code AS Code,
                        c.description AS Description

                    FROM
                        gsc_courses As c
                    WHERE 1=1";
                    return $sql;
                }

            public static function getCoursesByClasses(){
            $sql = " SELECT
                        m.id AS id,
                        m.credit AS Credit,
                        cl.name AS NameClass,
                        c.name AS NameCourse,
                        cl.id AS ClasseID,
                        c.id AS CoursesId,
                        u.first_name AS FirstNameTeacher,
                        u.last_name AS LastNameTeacher,
                        u.id AS TeacherID
                    FROM gsc_classes_courses AS m
                    INNER JOIN
                        gsc_courses AS c ON m.course_id = c.id
                    INNER JOIN
                        gsc_classes AS cl ON m.class_id = cl.id
                    INNER JOIN gsc_users AS u ON m.teacher_id = u.id

                    AND 1=1";
                    return $sql;
                }

            public static function getNotesStudents(){
                $sql = "SELECT
                n.id AS id,
                n.note AS Note,
                e. NAME AS NameExam,
                e.id AS IdExam,
            --   cl.name AS NameClass,
            -- 	cl.id AS IdClass,
                crs. NAME AS CourseName,
                crs.id AS CoursesId,
                s.firstname AS FirstNameStudent,
                s.lastname AS LastNameStudent,
                s.id AS StudentID
            FROM
             gsc_notes AS n
            -- INNER JOIN gsc_classes_courses AS c ON n.id_courses = c.course_id
            INNER JOIN gsc_evaluation AS e ON n.id_evaluation = e.id
            INNER JOIN gsc_courses AS crs ON n.id_courses = crs.id
            INNER JOIN gsc_students AS s ON n.id_students = s.id
            -- INNER JOIN gsc_classes AS cl ON c.class_id = cl.id
            AND 1 = 1";
                    return $sql;
                }
            public static function getTimeClasses(){
                $sql = " SELECT
                        t.id AS id,
                        t.date AS Date,
                        t.starting_hour AS Start,
                        t.closing_hour AS End,
                        -- cl.name AS NameClass,
                        -- cl.id AS IdClass,
                        crs.name AS CourseName,
                        crs.id AS CoursesId

                        FROM gsc_time_tables AS t
                        -- INNER JOIN gsc_classes_courses AS c ON t.id_course = c.course_id
                        INNER JOIN gsc_courses AS crs ON t.id_course = crs.id
                        -- INNER JOIN gsc_classes AS cl ON c.class_id = cl.id
                        AND 1=1";
                    return $sql;
                }

          }

?>
