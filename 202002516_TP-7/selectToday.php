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

$cno = $_POST['cnoToday'];   #js에서 데이터 넘겨오는 것

try {
    $conn = new PDO($dsn, $username, $password);
    
    date_default_timezone_set('Asia/Seoul');

    $today = date("Y/m/d");
    $sql = "SELECT LICENSEPLATENO, STARTDATE, ENDDATE
    FROM RESERVATION 
    WHERE CNO = :cno AND STARTDATE = :today";  
    //해당 고객의 오늘 예약된 차량 정보를 조회

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":cno", $cno);
    $stmt->bindValue(":today", $today);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['LICENSEPLATENO'] . " ";
        echo $row['STARTDATE'] . " ";
        echo $row['ENDDATE'] . " ";
    }
    
} catch (PDOException $e) {
    echo "에러 내용: " . $e->getMessage();
};

?>