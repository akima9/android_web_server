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


    $today_goal_cnt = array();
    $cnt = 0;
    $goal_cnt = 0;

    for($i = 0; $i < count($result); $i++) {
        
        $userId = $result[$i][0];
        
        // 각각의 userId에 대응하는 goal_cnt 받아오기
        // $query = "SELECT goal_cnt FROM goal WHERE userid = ?";
        $query = "SELECT todayCnt FROM person WHERE userid = ?";
        $stmt = $con->prepare($query);
        $stmt->execute(array($userId));
        while ($row = $stmt->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
            if($row[0] > 0) {
                $today_goal_cnt[$cnt] = $row[0] - 1;
            } else {
                $today_goal_cnt[$cnt] = $row[0];
            }
            $cnt++;
        }

        // goal 업데이트
        $stmt = $con->prepare('UPDATE person SET goal = :pre_goal_cnt, todayCnt = :goal_cnt WHERE userId = :userId');
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':pre_goal_cnt', $today_goal_cnt[$i]);
        $stmt->bindParam(':goal_cnt', $goal_cnt);
        $stmt->execute();

        $stmt = $con->prepare('INSERT INTO history(userId, todayCnt, goal, crdate) VALUES(:userId, :todayCnt, :goal, now())');
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':todayCnt', $todayCnt);
        $stmt->bindParam(':goal', $lsGoal);
        $stmt->execute();
    }
    

?>