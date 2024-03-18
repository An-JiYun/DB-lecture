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

    $sql = "SELECT C.NAME, R.LICENSEPLATENO, R.DATERENTED, R.RETURNDATE, (R.RETURNDATE - R.DATERENTED) AS RETALPERIOD,
    (R.RETURNDATE - R.DATERENTED) * M.RENTRATEPERDAY AS RNETALFEE, M.MODELNAME, M.VEHICLETYPE
    FROM RENTCAR R
    JOIN CUSTOMER C ON R.CNO = C.CNO
    JOIN CARMODEL M ON R.MODELNAME = M.MODELNAME
    ORDER BY R.LICENSEPLATENO";
    // RENTCAR, CUSTOMER, CARMODEL 테이블을 조인하여 M.VEHICLETYPE (차량 종류)를 선택
    // RENTCAR의 LICENSEPLATENO를 기준으로 오름차순 정렬

    $stmt = $conn->prepare($sql);
    $stmt->execute(array());

    $row = $stmt->fetchAll(PDO::FETCH_NUM);

    for($i=0; $i<count($row); $i++){
        echo $row[$i][0]."*".$row[$i][1]."*".$row[$i][2]."*".$row[$i][3]."*".$row[$i][4]."*".$row[$i][5]."*".$row[$i][6]."*".$row[$i][7]."\n";
    }

} catch (PDOException $e) {
    echo("접속 오류: ".$e->getMessage());
}
?>