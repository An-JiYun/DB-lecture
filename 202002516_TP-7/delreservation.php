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

$cno = $_POST['cno4'];   #js에서 데이터 넘겨오는 것 (학번 & 차량번호)
$licenseplateno = $_POST['licenseplateno4'];
$startdata = $_POST['startdata4'];

try {
    $conn = new PDO($dsn, $username, $password);
    // 선택한 데이터에 해당하는 예약 정보 삭제 
    $sql = "DELETE FROM RESERVATION WHERE CNO = :cno and LICENSEPLATENO = :licenseplateno and STARTDATE = :startdata";
    $stmt = $conn->prepare($sql); // FROM 키워드 추가

    $stmt->bindValue(":cno", $cno);                 // 학번 변수에 바인딩
    $stmt->bindValue(":licenseplateno", $licenseplateno); // 차량번호 변수에 바인딩
    $stmt->bindValue(":startdata", $startdata);     // 시작일 변수에 바인딩
    $stmt->execute();                               // 쿼리 실행
    echo "sucesse";
    
} catch (PDOException $e) {
    echo "에러 내용: " . $e->getMessage();
};

?>


