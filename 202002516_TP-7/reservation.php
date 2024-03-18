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
$dsn = "oci:dbname=" . $tns . ";charset=utf8";
$username = 'd202200000';
$password = '1234';

$cno = $_POST['cno1'];   #js에서 데이터 넘겨오는 것

try {
    $conn = new PDO($dsn, $username, $password);
    $stmt = $conn->prepare("SELECT * FROM RESERVATION WHERE CNO = :cno");
    //WHERE 절에서 CNO를 기준으로 예약 정보를 필터링
    $stmt->bindValue(":cno", $cno);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo $row['LICENSEPLATENO'] . " ";
        echo $row['STARTDATE'] . " ";
        echo $row['RESERVEDATE'] . " ";
        echo $row['ENDDATE'] . " ";
    }
} catch (PDOException $e) {
    echo ("에러 내용: " . $e->getMessage());
}

?>