let cnoInfo = localStorage.getItem("cno");

var reserveBtn = document.querySelectorAll('.reserveBtn');
console.log(reserveBtn);

reserveBtn.forEach(function(btn) {
    btn.addEventListener('click', function(event) {
        var clickedElement = event.target;
        let idx = clickedElement.id;

         // 해당 버튼을 클릭한 시점의 차량 번호, 시작일, 종료일을 가져옴
        let plateEle = document.getElementById(idx + "plate");
        let startEle = document.getElementById(idx + "start");
        let endEle = document.getElementById(idx + "end");
        console.log(plateEle);
        console.log(startEle);
        console.log(endEle);

        // AJAX를 통해 reserve.php 파일에 데이터를 전송
        $.ajax({
                url				: 'reserve.php',
                type			: 'POST',
                data			: {
                    plateNo : plateEle.value,
                    startDate : startEle.value,
                    endDate : endEle.value,
                    cnoRserv	    : cnoInfo
                },
                success : function(data){
                     // 성공적으로 예약이 완료되면 알림을 표시하고 login.html 페이지로 이동

                    alert("자동차가 예약되었습니다");
                    location.href="login.html";
                }
            });
    })
});
