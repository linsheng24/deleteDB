<?php
require_once("./lib/dbconfig.php");



    $son_table = explode(",",$_POST['son']);
    $db = new PDO("mysql:host=localhost;dbname=".$dsn,$user,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    $sql = "SELECT * FROM ".$_POST['main']." WHERE isDel=1 AND modifyDatetime<:date ";
    $stmt1 = $db->prepare($sql);    
    $stmt1->bindParam(":date",$_POST['date']);

    try{
	
        $db->beginTransaction();
        $row1 = $stmt1->execute();
        if(!$row1){
            throw new Exception('error execute stmt1');
        }else{
            echo 'execute stmt1 success<br>';
        }
    echo "------------------<br>";
    echo "刪除中...<br>";
    echo "------------------<br>";
    while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){

        for($i=0;$i<count($son_table);$i++){
            $sql = "DELETE FROM ".$son_table[$i]." WHERE ".$_POST['s_id']."=:id ";
            $stmt2 = $db->prepare($sql);
            $stmt2->bindParam(':id',$row1[$_POST['m_id']]);
            $row2 = $stmt2->execute();
            if(!$row2){
                throw new Exception('error execute stmt2');
            
            }
            
        }

    }

    echo 'execute stmt2 success<br>';

    $sql =" DELETE FROM ".$_POST['main']." WHERE isDel=1 AND modifyDatetime<:date";

    $stmt3 = $db->prepare($sql);
    $stmt3->bindParam(':date',$_POST['date']);
    $row3 = $stmt3->execute();
    if(!$row3){
        throw new Exception('error execute stmt3');
    }

    else{
        echo 'execute stmt3 success<br>';
        
    }

    
    $db->commit();

    }
    
    
    catch (Exception $e){
        
        $db->rollBack();
    
        echo $e->getMessage();
    }


?>