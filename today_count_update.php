<?php 

    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('../dbcon.php');


    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");


    if( (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit'])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $userId=$_POST['userId'];

        try{
            // SQL문을 실행하여 person 테이블에서 조회합니다.
            $query = "SELECT goal_cnt FROM goal WHERE userId = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($userId));
            $result = $stmt->fetchAll(PDO::FETCH_NUM);

            $goal_cnt = $result[0][0] + 1;
            
            $stmt = $con->prepare('UPDATE goal SET goal_cnt = :goal_cnt WHERE userId = :userId');
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':goal_cnt', $goal_cnt);
            $stmt->execute();

            if($stmt->execute())
            {
                $response["success"] = true;
            }
            else
            {
                $response["success"] = false;
            }

            echo json_encode($response);

        } catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }

    }

?>