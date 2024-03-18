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

$cno = $_POST['cno3'];   #js에서 데이터 넘겨오는 것

if(!empty($cno)){
    try {
        $conn = new PDO($dsn, $username, $password);
        $sql = "SELECT p.LICENSEPLATENO, r.MODELNAME, p.DATERENTED, p.DATERETURNED, p.PAYMENT
                FROM PREVIOUSRENTAL p, RENTCAR r
                WHERE p.LICENSEPLATENO = r.LICENSEPLATENO AND p.CNO = :cno
                ORDER BY p.DATERENTED DESC";
                 // PREVIOUSRENTAL과 RENTCAR 테이블을 조인하고 내림차순으로 정렬
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":cno", $cno);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['LICENSEPLATENO'] . " ";      // 차량 번호 출력
            echo $row['MODELNAME'] . " ";           // 모델 이름 출력
            echo $row['DATERENTED'] . " ";          // 대여일 출력
            echo $row['DATERETURNED'] . " ";        // 반납일 출력
            echo $row['PAYMENT'] . " ";             // 결제 정보 출력
        }
    } catch (PDOException $e) {
        echo ("에러 내용: " . $e->getMessage());
    }
}

?>