<?php
$tns = "
    (DESCRIPTION=
        (ADDRESS_LIST=   (ADDRESS=(PROTOCOL=TCP)(HOST=10.4.32.35)(PORT=1521)))
        (CONNECT_DATA=   (SERVICE_NAME=XE))
    )
";
$dsn = "oci:dbname=".$tns.";charset=utf8";
$username = 'd202000192';
$password = '010625';

$cno = $_POST['cno22'];   #js에서 데이터 넘겨오는 것
$email = $_POST['email'];
$name = $_POST['name'];
$licenseplateno = $_POST['licenseplateno6'];

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\OAuth;
use PHPMailer\PHPMailer\POP3;
require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
require './PHPMailer/src/OAuth.php';
require './PHPMailer/src/POP3.php';


$mail = new PHPMailer(true);
$mail->isSMTP();

$mail->CharSet = "utf-8"; //한글이 안깨지게 CharSet 설정
$mail->Encoding = "base64";
$mail->Host = "smtp.naver.com"; // email 보낼때 사용할 서버를 지정
$mail->SMTPAuth = true; // SMTP 인증을 사용함
$mail->Port = 465; // email 보낼때 사용할 포트를 지정
$mail->SMTPSecure = "ssl"; // SSL을 사용함
$mail->Username = 'jij09123@naver.com';
$mail->Password = 'VD8GHVURQXL5';
$mail->SetFrom('jij09123@naver.com', 'cnu rentcar'); // 보내는 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
$mail->AddAddress($email); // 받을 사람 email 주소와 표시될 이름 (표시될 이름은 생략가능)
$mail->Subject = 'CNU RENTCAR 반납 메일'; // 메일 제목
$mail->Body = '안녕하세요 '.$name.'님!  '.
                $licenseplateno.' 렌트카의 반납이 완료 되었습니다.'; // 내용
$mail->Send(); // 발송
echo 'Message has been sent';


?>