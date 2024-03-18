let cnoInfo = localStorage.getItem("cno");

let rentSearchBtn = document.getElementById("rentSearchBtn");
rentSearchBtn.addEventListener("click", rentSearch);


function rentSearch() {
    console.log("ddd");
    let rentDiv = document.getElementById("rentSearchResult");
    while( rentDiv.hasChildNodes() ){
        rentDiv.removeChild(rentDiv.firstChild);
    }


    let selectedValue = document.getElementById("modelname").value; // 선택된 검색어 가져오기

    if(selectedValue == "a"){
        selectedValue = "아반떼"
    } else if (selectedValue == "sso") {
        selectedValue = "쏘나타"
    } else if (selectedValue == "ca") {
        selectedValue = "카니발"
    } else if (selectedValue == "ssan") {
        selectedValue = "싼타페"
    } else if (selectedValue == "tu") {
        selectedValue = "투싼"
    } else {
        selectedValue = "전체"
    }

    // 선택된 검색어를 기반으로 검색 결과 처리
    if (cnoInfo != null) {
        $.ajax({
            url				: 'rent.php',
            type			: 'POST',
            data			: {
            cno2	        : cnoInfo,
            },
            success : function(data){
                let rentArr = data.split(" ");
                console.log(rentArr);
    
                let count = parseInt(rentArr.length / 5);
                for(let i = 0; i<count; i++){
                    let divtag2 = document.createElement("div");
                    let ptag2 = document.createElement("p");
                    let payBtn = document.createElement("button");
                    payBtn.innerHTML = "결제";

                    if(selectedValue == rentArr[1 + i*5] || selectedValue=="전체"){
                        ptag2.innerHTML = "차량번호 : " + rentArr[0 + i*5] +
                        " , 자동차 모델 이름 : " + rentArr[1 + i*5] +
                        " , 대여 날짜 : " + rentArr[2 + i*5] +
                        " , 반납 예정일 : " + rentArr[3 + i*5] +
                        " , 결제 예정 금액 : " + rentArr[4 + i*5];
                        
                        let carItem = rentArr[0 + i*5]+" "+rentArr[1 + i*5]+" "+rentArr[2 + i*5]+" "+rentArr[3 + i*5]+" "+rentArr[4 + i*5];
                        console.log(carItem);
                        payBtn.addEventListener("click", function () {
                            //결제버튼 눌렀을 때 이메일 보내기
                            //결제하면 prevRent에 넣기 
                            insertPrevRent(carItem);
                        });

                        divtag2.appendChild(ptag2);
                        divtag2.appendChild(payBtn);

                        rentDiv.appendChild(divtag2);

                    }
    
                }   
                
            }
        });
    
    }
}



function insertPrevRent(data){
    let dataArr = data.split(" ");
    console.log(dataArr);
    $.ajax({
        url: 'insertPrevRent.php',
        type: 'POST',
        data: {
            cno5: cnoInfo,
            licenseplateno5: dataArr[0],
            daterented5 : dataArr[2],
            returndate5 : dataArr[3],
            payment5 : dataArr[4]
        },

        success: function(getData) {
            console.log(getData);
            let getDataArr = getData.split(" ");
            $.ajax({
                url: 'Mailer.php',
                type: 'POST',
                data: {
                    cno22: cnoInfo,
                    licenseplateno6: dataArr[0],
                    name: getDataArr[0],
                    email: getDataArr[1],
                },
                success: function(maildata) {
                    console.log(maildata);
                    
                }
                
            });
            
            alert("자동차가 반납되었습니다");
            location.href="rent.html";
        }
    }); 
}