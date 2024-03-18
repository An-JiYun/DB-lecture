<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>예약 정보</title>
</head>
<body>
    <form method="post" action="./reserve.php">
        <a href="login.html">
            <h1>CNU RENTCAR</h1>
        </a>
        <h1>예약 정보</h1>
        <p>차량 번호: <?php echo $_POST['plateNo']; ?></p>
        <p>시작 날짜: <?php echo $_POST['startDate']; ?></p>
        <p>종료 날짜: <?php echo $_POST['endDate']; ?></p>
    </form>
    <script src="reserve.js"></script>
</body>
</html>

<?php include($_SERVER["DOCUMENT_ROOT"]. "/TP/AccessPOD.php");

$cno = $_POST['cnoRserv'];          #js에서 데이터 넘겨오는 것
$plateNo = $_POST['plateNo'];       #search.php에서 넘어온 데이터
$startDate = $_POST['startDate'];   #search.php에서 넘어온 데이터
$endDate = $_POST['endDate'];       #search.php에서 넘어온 데이터

$today = date("Y/m/d");
echo "$plateNo, $startDate, $today, $endDate, $cno";
$stmt = $conn->prepare("INSERT INTO Reservation VALUES (:plateNo, :startDate, :today, :endDate, :cno)");
//INSERT INTO 문을 사용하여 Reservation 테이블에 데이터를 삽입
$stmt->bindValue(":cno", $cno);
$stmt->execute(array($plateNo, $startDate, $today, $endDate, $cno));
 //바인딩된 매개변수에 값을 할당하고 execute() 함수를 호출하여 쿼리를 실행
?>