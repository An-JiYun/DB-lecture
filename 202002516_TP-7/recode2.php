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

    $sql = "SELECT licenseplateNo, COUNT(*) AS rent_count
    FROM (
        SELECT licenseplateNo, cno, dateRented
        FROM RentCar
        UNION
        SELECT licenseplateNo, cno, dateRented
        FROM PreviousRental
        )
    WHERE cno IS NOT NULL
    GROUP BY GROUPING SETS (licenseplateNo, ())";
    // UNION을 사용하여 RentCar와 PreviousRental 테이블 대여 정보를 합치고
    // SELECT 절에서 licenseplateNo (차량 번호)와 COUNT(*) AS rent_count (대여 횟수)를 선택한다.
    // WHERE 절에서는 cno가 NULL이 아닌 대여 정보만 필터링하고
    // GROUP BY 절에서는 licenseplateNo를 기준으로 그룹화하여 licenseplateNo 별로 그룹화된 결과를 출력한다.

    $stmt = $conn->prepare($sql);
    $stmt->execute(array());

    $row = $stmt->fetchAll(PDO::FETCH_NUM);

    for($i=0; $i<count($row); $i++){
        echo $row[$i][0]."*".$row[$i][1]."\n";
    }

} catch (PDOException $e) {
    echo("접속 오류: ".$e->getMessage());
}
?>