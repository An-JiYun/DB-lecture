let cnoInfo = localStorage.getItem("cno");

let rentBtn = document.getElementById("rentBtn");
let prevRentBtn = document.getElementById("prevRentBtn");

rentBtn.addEventListener("click", function() {
    window.location.href = "rent.html";
});
prevRentBtn.addEventListener("click", function() {
    window.location.href = "prevRent.html";
});

if (cnoInfo != null) {
    $.ajax({
        url				: 'mypage.php',
        type			: 'POST',
        data			: {
        cno0	        : cnoInfo
        },
        success : function(data){
            let dataArr = data.split(" ");
            console.log(dataArr);

            document.getElementById("cno").innerHTML = "학번 : "+dataArr[0];
            document.getElementById("name").innerHTML = "이름 : "+dataArr[1];
            document.getElementById("email").innerHTML = "이메일 : "+dataArr[2];
        }
    });

    $.ajax({
        url				: 'selectToday.php',
        type			: 'POST',
        data			: {
        cnoToday	    : cnoInfo
        },
        success : function(data){
            let dataArr = data.split(" ");
            console.log(dataArr);

            let flag = 0;   // flag : 0으로 유지 되는 경우 삭제된 행이 없음
            if(data != null){
                let count = parseInt(dataArr.length / 3);
                for(let i = 0; i<count; i++){
                    let lin = dataArr[0 + i*3];
                    let startdata = dataArr[1 + i*3];
                    let enddata = dataArr[2 + i*3];
                    console.log(lin + " " + startdata + " "+enddata);

                    // 삭제된 행의 수만큼 return이 오므로 flag의 업데이트 수행
                    flag += autoToReservFromRent(cnoInfo, lin, startdata, enddata);
                }
                
            }

            console.log(flag);
            if (flag != 0){
                // 삭제된 행이 존재하므로 새로고침이 필요.
                location.reload();
            }
        }
    });

    $.ajax({
        url				: 'reservation.php',
        type			: 'POST',
        data			: {
        cno1	        : cnoInfo
        },
        success : function(data){
            console.log("현재 확인");
            let reserveArr = data.split(" ");
            let reserveDiv = document.getElementById("reservationinfo");
            // console.log(reserveArr);

            let count = parseInt(reserveArr.length / 4);
            for(let i = 0; i<count; i++){
                let divtag = document.createElement("div");
                let ptag = document.createElement("p");
                let cancleBtn = document.createElement("button");

                let lin = reserveArr[0 + i*4];
                let startdata = reserveArr[1 + i*4];

                console.log(startdata);

                cancleBtn.innerHTML = "취소";
                ptag.innerHTML = "차량번호 : " + reserveArr[0 + i*4] +
                                " , 예약 날짜 : " + reserveArr[2 + i*4] +
                                " , 시작 날짜 : " + reserveArr[1 + i*4] +
                                " , 끝 날짜 : " + reserveArr[3 + i*4];
                
                cancleBtn.addEventListener("click", function () {
                    deleteReserve(cnoInfo, lin, startdata);
                });

                divtag.appendChild(ptag);
                divtag.appendChild(cancleBtn);
                reserveDiv.appendChild(divtag);

            }   
            
        }
    });
    
}

function deleteReserve(cnoInfo4, lin4, st4){
    $.ajax({
        url: 'delreservation.php',
        type: 'POST',
        data: {
            cno4: cnoInfo4,
            licenseplateno4: lin4, // 학번과 차량번호를 php에 전송
            startdata4 : st4
        },
        success: function(data) {
            console.log(data);
            // AJAX 요청이 성공했을 때 수행할 작업
            // 예를 들어, 서버에서 응답으로 받은 데이터를 처리하거나 추가적인 동작 수행 가능
            // console.log(data); // 예약 정보 삭제 결과 또는 기타 데이터 출력
           location.href="mypage.html";
        }

    });
}



function autoToReservFromRent(cnoInfo10, lin10, startdata10, enddata10){
    $.ajax({
        url				: 'autoRent.php',
        type			: 'POST',
        data			: {
        cnoAuto10	    : cnoInfo10,
        licenAuto10     : lin10,
        startAuto10     : startdata10,
        endAuto10       : enddata10
        },
        success : function(auto){
            return auto;
            // location.href="mypage.html";
        }
    });
}
