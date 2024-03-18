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

try {
    $conn = new PDO($dsn, $username, $password);

    $sql = "SELECT r.licenseplateno, c.cno, c.name, r.startdate, rank() over (order by r.startdate) rank,
    row_number() over (order by r.startdate) row_number
    from Reservation r, customer c
    where r.cno = c.cno";
/* Reservation과 Customer 테이블을 조인하여 예약 정보를 가져오고, 날짜에 따른 순위와 행 번호 계산
    FROM 절에서는 Reservation과 Customer 테이블을 조인하고
    SELECT 절에서 r.licenseplateno, c.cno, c.name, r.startdate, rank, row_number를 선택
    WHERE 절에서는 Reservation과 Customer 테이블을 cno를 기준으로 연결한다. */


    $stmt = $conn->prepare($sql);
    $stmt->execute(array());

    $row = $stmt->fetchAll(PDO::FETCH_NUM);

    for($i=0; $i<count($row); $i++){
        echo $row[$i][0]."*".$row[$i][1]."*".$row[$i][2]."*".$row[$i][3]."*".$row[$i][4]."*".$row[$i][5]."\n";
    }

} catch (PDOException $e) {
    echo("접속 오류: ".$e->getMessage());
}
?>