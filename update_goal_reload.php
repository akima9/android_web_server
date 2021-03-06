<?php 

    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('../dbcon.php');


    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

    // userId 받아오기
    $query = "SELECT userId FROM person";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_NUM);


    $today_goal_cnt = array(); // 업데이트 될 goal을 담을 배열
    $cnt = 0; // 업데이트 될 goal을 담을 배열의 인덱스
    $todayCnt = 0; // 업데이트 될 todayCnt

    $history_goal_arr = array(); // history table의 goal을 담을 배열
    $history_todayCnt_arr = array(); // history table의 todayCnt을 담을 배열

    for($i = 0; $i < count($result); $i++) {
        
        $userId = $result[$i][0];
        
        // 각각의 userId에 대응하는 todayCnt, goal 받아오기
        // todayCnt : 오늘 핀 개수
        // goal : 목표 개수
        $query = "SELECT todayCnt, goal FROM person WHERE userid = ?";
        $stmt = $con->prepare($query);
        $stmt->execute(array($userId));
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
            
            // var_dump($row);
            
            // array(2) { 
            //     [0]=> string(1) "0" // todayCnt 
            //     [1]=> string(1) "7" // goal
            // } 
            // array(2) { 
            //     [0]=> string(1) "0" // todayCnt
            //     [1]=> string(1) "9" // goal
            // }
            
            // todayCnt가 0보다 크면 -1 그렇지 않으면 그대로
            if($row[0] > 0) {
                $today_goal_cnt[$cnt] = $row[0] - 1;
                $total_goal_cnt[$cnt] = $row[0];
            } else {
                $today_goal_cnt[$cnt] = $row[0];
                $total_goal_cnt[$cnt] = $row[0];
            }

            $history_todayCnt_arr[$cnt] = $row[0];
            $history_goal_arr[$cnt] = $row[1];

            $cnt++;
        }

        // goal 업데이트
        $stmt = $con->prepare('UPDATE person SET goal = :goal, todayCnt = :todayCnt WHERE userId = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':goal', $today_goal_cnt[$i]);
        $stmt->bindParam(':todayCnt', $todayCnt);
        $stmt->execute();

        $crdate = date("Y-m-d H:i:s");

        $stmt = $con->prepare('INSERT INTO history(userId, todayCnt, goal, crdate) VALUES(:userId, :todayCnt, :goal, :crdate)');
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':todayCnt', $history_todayCnt_arr[$i]);
        $stmt->bindParam(':goal', $history_goal_arr[$i]);
        $stmt->bindParam(':crdate', $crdate);
        $stmt->execute();
    }
    

?>