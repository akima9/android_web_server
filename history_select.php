<?php 

    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('../dbcon.php');


    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");


    if( (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit'])) || $android )
    {

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        $userId=$_POST['userId'];
        $year=$_POST['year'];
        $month=$_POST['month'];
        $dayOfMonth=$_POST['dayOfMonth'];

        // var_dump($userId);
        // var_dump($year);
        // var_dump($month);
        // var_dump($dayOfMonth);

        $crdate = $year."-".$month."-".$dayOfMonth;

        try{
            $query = "SELECT goal, todayCnt FROM history WHERE userId = :userId and DATE(crdate) = :crdate";
            $stmt = $con->prepare($query);
            $stmt -> bindValue(":userId", $userId, PDO::PARAM_STR);
            $stmt -> bindValue(":crdate", $crdate, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_NUM);

            // var_dump($result);

            // array(1) {
            //     [0]=>
            //     array(2) {
            //       [0]=>
            //       string(2) "10"
            //       [1]=>
            //       string(1) "0"
            //     }
            //   }
            // $result[0][0] // goal
            // $result[0][1] // todayCnt

            $response = array();

            if(isset($result[0][0])) {
                $response["goal"] = $result[0][0];
                $response["todayCnt"] = $result[0][1];
                $response["status"] = 200;
                //$response["userId"] = $userId;
                
                // session_start();
                // $_SESSION['userId'] = $userId;
            } else {
                $response["status"] = 999;
            }

            echo json_encode($response);

        } catch(PDOException $e) {
            die("Database error: " . $e->getMessage()); 
        }

    }

?>