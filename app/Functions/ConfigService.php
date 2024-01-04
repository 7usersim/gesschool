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
    }
?>
