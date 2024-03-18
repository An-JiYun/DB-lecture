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

$cno = $_POST['cno2'];   #js에서 데이터 넘겨오는 것

if(!empty($cno)){
    try {
        $conn = new PDO($dsn, $username, $password);
        $sql = "SELECT r.LICENSEPLATENO, r.MODELNAME, r.DATERENTED, r.RETURNDATE,
                (r.RETURNDATE-r.DATERENTED+1)*c.RENTRATEPERDAY as PAYMENT
                FROM RENTCAR r, CARMODEL c
                WHERE r.MODELNAME = c.MODELNAME AND r.CNO = :cno
                ORDER BY r.DATERENTED DESC";
/* SELECT 절에서 대여 정보를 선택하고, (반납 날짜 - 대여 날짜 + 1) * 대여 요금을 PAYMENT로 계산
* - FROM 절에서는 RENTCAR 테이블과 CARMODEL 테이블을 조인
* - WHERE 절에서는 모델 이름과 고객 번호를 기준으로 대여 정보를 필터링 후 내림차순으로 정렬 */
        
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":cno", $cno);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['LICENSEPLATENO'] . " ";
            echo $row['MODELNAME'] . " ";
            echo $row['DATERENTED'] . " ";
            echo $row['RETURNDATE'] . " ";
            echo $row['PAYMENT'] . " ";
        }
    } catch (PDOException $e) {
        echo ("에러 내용: " . $e->getMessage());
    }
}

?>