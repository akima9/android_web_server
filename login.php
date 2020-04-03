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
        $userYn = "Y";

        try{
            // SQL문을 실행하여 person 테이블에서 조회합니다.
            // $query = "SELECT userId, userPw FROM person WHERE userId = ? and userYn = ?";
            $query = "SELECT userId, userPw, userYn FROM person WHERE userId = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($userId));
            $result = $stmt->fetchAll(PDO::FETCH_NUM);

            //var_dump($result);
            $response = array();

            if($result[0][2] == "N"){
                $response["status"] = "N";
            } else {
                $response["status"] = "Y";
                if( password_verify($userPw, $result[0][1]) ) {
                    $response["success"] = true;
                    $response["userId"] = $userId;
                    
                    session_start();
                    $_SESSION['userId'] = $userId;
                } else {
                    $response["success"] = false;
                }
            }

            echo json_encode($response);

        } catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }

    }

?>