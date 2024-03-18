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

$cno = $_POST['cno'] ?? '';

if(!empty($cno)) {
    try {
        $conn = new PDO($dsn, $username, $password);        #로그인
        $sql = "SELECT * FROM customer WHERE cno = :cno";        #쿼리문
        $stmt = $conn->prepare($sql);
        $stmt-> bindValue(":cno", $cno);    #:cno에 post로 받은 값 바인딩해서 쿼리어쩌구 다함
        $stmt->execute();
        $row = $stmt->fetch(); #select문 정보들 받아옴 (엑셀처럼)

        if (empty($row)) {
            echo "부적합";    #echo : js로 보낸다
        } else {
            echo $row['CNO'] . " ";
            echo $row['NAME'] . " ";
            echo $row['PASSWD'] . " ";
            echo $row['EMAIL'] . " ";
        }
    } catch (PDOException $e) {
        echo "에러 내용: ".$e->getMessage();
    }
}
?>


