<?php 

    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('../dbcon.php');


    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");


    if( (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit'])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $userId=$_POST['userId'];
        $userPw=$_POST['userPw'];
        $lsGoal=$_POST['goalCnt'];
        $todayCnt = 0;
        $userYn = "Y";

        // 비밀번호 암호화
        $encrypted_passwd = password_hash($userPw, PASSWORD_DEFAULT);
        
        try{

            // SQL문을 실행하여 person 테이블에서 조회합니다.
            $query = "SELECT userId FROM person WHERE userId = ? and userYn = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($userId,$userYn));
            $result = $stmt->fetchAll(PDO::FETCH_NUM);

            $response = array();

            if(!isset($result[0][0])) {
                // SQL문을 실행하여 데이터를 MySQL 서버의 person 테이블에 저장합니다. 
                $stmt = $con->prepare('INSERT INTO person(userId, userPw, userYn, todayCnt, goal) VALUES(:userId, :userPw, :userYn, :todayCnt, :goal)');
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':userPw', $encrypted_passwd);
                $stmt->bindParam(':userYn', $userYn);
                $stmt->bindParam(':todayCnt', $todayCnt);
                $stmt->bindParam(':goal', $lsGoal);

                if($stmt->execute())
                {
                    $response["success"] = true;
                }
                else
                {
                    $response["success"] = false;
                }

                //echo json_encode($response);
                
                // $pre_goal_cnt = (int) $lsgoalCnt;
                // $goal_cnt = 0;

                // $stmt = $con->prepare('INSERT INTO goal(userId, pre_goal_cnt, goal_cnt) VALUES(:userId, :pre_goal_cnt, :goal_cnt)');
                // $stmt->bindParam(':userId', $userId);
                // $stmt->bindParam(':pre_goal_cnt', $pre_goal_cnt);
                // $stmt->bindParam(':goal_cnt', $goal_cnt);
                // $stmt->execute();

            } else {
                $response["success"] = false;
                //$resultMSG = "DUPE";
            }

            echo json_encode($response);

        } catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }

    }

?>