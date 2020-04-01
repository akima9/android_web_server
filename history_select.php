<?php 

    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('../dbcon.php');


    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");


    if( (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit'])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $userId=$_POST['userId'];
        var_dump($userId);

        try{
            $query = "SELECT goal, todayCnt FROM history WHERE userId = ?";
            $stmt = $con->prepare($query);
            $stmt->execute(array($userId));
            $result = $stmt->fetchAll(PDO::FETCH_NUM);

            var_dump($result);

            // $response = array();

            // if(isset($result[0][0])) {
            //     $response["goalCnt"] = $result[0][0];
            //     //$response["userId"] = $userId;
                
            //     // session_start();
            //     // $_SESSION['userId'] = $userId;
            // } else {
            //     $response["goalCnt"] = 999;
            // }

            // echo json_encode($response);

        } catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }

    }

?>