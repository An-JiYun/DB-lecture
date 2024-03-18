<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form method="post" action="./search.php">
    <a href="login.html">
        <h1>CNU RENTCAR</h1>
    </a>
    <div>
        
        <form method="POST">
            <span>대여 시작 날짜 :</span>
            <input type="date" name="startDate"/> ~ 
            <span>대여 종료 날짜 :</span>
            <input type="date" name="endDate"/>
            <label for="vehicletype[]">차종:</label>
            <input type="checkbox" onchange="checkAll(this.checked)">전체
            <input type="checkbox" class="type" name="vehicletype[]" value="소형">소형
            <input type="checkbox" class="type" name="vehicletype[]" value="중형">중형
            <input type="checkbox" class="type" name="vehicletype[]" value="대형">대형
            <input type="checkbox" class="type" name="vehicletype[]" value="승합차">승합차
            <input type="checkbox" class="type" name="vehicletype[]" value="SUV">SUV
            <input type="checkbox" class="type" name="vehicletype[]" value="전기차">전기차            
            <button>검색</button>
       </form>
    </div">


    <?php
include($_SERVER["DOCUMENT_ROOT"]. "/TP/AccessPOD.php");

$startDate = $_POST["startDate"] ?? '';
$endDate = $_POST["endDate"] ?? '';
$vehicletype = $_POST["vehicletype"] ?? [];
if (!empty($vehicletype)) {
    foreach($vehicletype as $arr){
        //선택한 차량 종류(vehicletype)에 따라 예약 가능한 차량 정보를 조회
        $sql = "SELECT DISTINCT c.licenseplateno, m.modelname, m.vehicletype, m.rentrateperday, m.fuel, m.numberofseats, (SELECT LISTAGG(o.optionname, ',') FROM options o WHERE c.licenseplateno = o.licenseplateno) AS options
        FROM rentcar c
        INNER JOIN carmodel m ON c.modelname = m.modelname
        LEFT JOIN reservation r 
        ON c.licenseplateno = r.licenseplateno
        WHERE NOT EXISTS (
          SELECT 1
          FROM reservation r2
          WHERE c.licenseplateno = r2.licenseplateno
            AND ((r2.startdate <= :endDate AND :endDate <= r2.enddate) 
                OR (r2.startdate <= :startDate AND :startDate <= r2.enddate) )
                OR ((c.DATERENTED <= :endDate AND :endDate <= c.RETURNDATE) 
                OR (c.DATERENTED <= :startDate AND :startDate <= c.RETURNDATE) )
        ) AND m.vehicletype = :vehicleType
        ORDER BY c.licenseplateno ASC";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":vehicletype", $arr);
        $stmt->bindParam(":startDate", $startDate);
        $stmt->bindParam(":endDate", $endDate);
        $stmt->execute();
        
        $today = date("Y/m/d");
        
        $idx = 0;
        while($rows = $stmt->fetch(PDO::FETCH_NUM)){
             // 조회된 차량 정보를 출력
            echo "<br><br><br>";
            echo "차량 번호: " . $rows[0] . "<br>";
            echo "차량 종류: " . $rows[1] . "<br>";
            echo "차종: " . $rows[2] . "<br>";
            echo "일일 대여 요금: " . $rows[3] . "<br>";
            echo "연료: " . $rows[4] . "<br>";
            echo "좌석수: " . $rows[5] . "<br>";
            echo "옵션: " . $rows[6] . "<br>";

            // 예약 정보를 전송하기 위한 hidden input과 예약 버튼을 출력
            echo "<input type='hidden' id = '", $idx, "plate" ,"' name='plateNo' value='" . $rows[0] . "'>";
            echo "<input type='hidden' id = '", $idx, "start" ,"' name='startDate' value='" . $startDate . "'>";
            echo "<input type='hidden' id = '", $idx, "end" ,"' name='endDate' value='" . $endDate . "'>";
            echo "<button class='reserveBtn' id = '", $idx,"'>예약하러 가기</button>";
            $idx += 1;
        }
    }
}
?>

<!-- 예약 관련 JavaScript 파일을 로드 -->
    <script src="reserve.js"></script>
    <script>
        function checkAll(checked) {
            var checkboxes = document.querySelectorAll('.type');
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = checked;
                }
        }

    </script>
</body>
</html> 
