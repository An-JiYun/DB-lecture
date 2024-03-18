<?php 
$tns = "
(DESCRIPTION=
    (ADDRESS_LIST=
        (ADDRESS=(PROTOCOL=TCP)(HOST=localhost)(PORT=1521))
    )
    (CONNECT_DATA=
        (SERVICE_NAME=XE)
    )
)
";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd202200000';
$password = '1234';

$cno = $_POST['cnoAuto10'];   #js에서 데이터 넘겨오는 것
$licenseplateno = $_POST['licenAuto10'];
$daterented = $_POST['startAuto10'];
$returndate = $_POST['endAuto10'];

try {
    $conn = new PDO($dsn, $username, $password);

    date_default_timezone_set('Asia/Seoul');

    $today = date("Y/m/d");

    // RENTCAR 테이블의 해당 차량 정보를 업데이트
    $sql = "UPDATE RENTCAR 
    SET DATERENTED = :daterented, RETURNDATE = :returndate, CNO = :cno
    WHERE LICENSEPLATENO = :licenseplateno";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":cno", $cno);
    $stmt->bindValue(":licenseplateno", $licenseplateno);
    $stmt->bindValue(":daterented", $daterented);
    $stmt->bindValue(":returndate", $returndate);
    $stmt->execute(); //쿼리 실행


    // RESERVATION 테이블에서 해당 예약 정보를 삭제
    $sql2 = "DELETE FROM RESERVATION
            WHERE CNO = :cno 
            AND STARTDATE = :daterented 
            AND LICENSEPLATENO = :licenseplateno"; 
    $stmt2 = $conn->prepare($sql2);

    $stmt2->bindValue(":licenseplateno", $licenseplateno);  //변수 바인딩
    $stmt2->bindValue(":cno", $cno);
    $stmt2->bindValue(":daterented", $daterented);
    $stmt2->execute(); //쿼리 실행
    
    $deletedRows = $stmt->rowCount();
    echo $deletedRows;
} catch (PDOException $e) {
    echo "에러 내용: " . $e->getMessage();
};

?>