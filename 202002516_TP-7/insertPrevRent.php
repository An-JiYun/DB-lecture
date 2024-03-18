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

$cno = $_POST['cno5'];   #js에서 데이터 넘겨오는 것 (학번 & 차량번호)
$licenseplateno = $_POST['licenseplateno5'];
$daterented = $_POST['daterented5'];
$datereturned = $_POST['returndate5'];
$payment = $_POST['payment5'];

try {
    $conn = new PDO($dsn, $username, $password);
    
    $sql = "INSERT INTO PREVIOUSRENTAL
            VALUES (:licenseplateno, :daterented, :datereturned, :payment, :cno)"; 
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":licenseplateno", $licenseplateno);
    $stmt->bindValue(":daterented", $daterented);
    $stmt->bindValue(":datereturned", $datereturned);
    $stmt->bindValue(":payment", $payment);
    $stmt->bindValue(":cno", $cno);
    $stmt->execute();

    $sql2 = "UPDATE RENTCAR 
    SET DATERENTED = NULL, RETURNDATE = NULL, CNO = NULL
    WHERE CNO = :cno AND LICENSEPLATENO = :licenseplateno AND DATERENTED = :daterented";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bindValue(":cno", $cno);
    $stmt2->bindValue(":licenseplateno", $licenseplateno);
    $stmt2->bindValue(":daterented", $daterented);
    $stmt2->execute();



    #메일 보내기위해 이메일 정보 rent.js로 보냄
    $sql3 = "SELECT NAME, EMAIL FROM CUSTOMER WHERE CNO=:cno";
    $stmt3 = $conn->prepare($sql3);
    $stmt3->bindValue(":cno", $cno);
    $stmt3->execute();
    $row = $stmt3->fetch(); #select문 정보들 받아옴 (엑셀처럼)

    if (empty($row)) {
        echo "부적합";    #echo : js로 보낸다
    } else {
        echo $row['NAME'] . " ";
        echo $row['EMAIL'] . " ";
    }

    
} catch (PDOException $e) {
    echo "에러 내용: " . $e->getMessage();
};

?>

