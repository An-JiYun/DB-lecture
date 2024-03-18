let cnoInfo = localStorage.getItem("cno");

let rentSearchBtn = document.getElementById("rentSearchBtn");
rentSearchBtn.addEventListener("click", rentSearch);


function rentSearch() {
    console.log("ddd");
    let prevrentDiv = document.getElementById("rentSearchResult");
    while( prevrentDiv.hasChildNodes() ){
        prevrentDiv.removeChild(prevrentDiv.firstChild);
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

    if (cnoInfo != null) {
        $.ajax({
            url				: 'prevRent.php',
            type			: 'POST',
            data			: {
            cno3	        : cnoInfo
            },
            success : function(data){
                let prevRentArr = data.split(" ");
    
                let count = parseInt(prevRentArr.length / 5);
                for(let i = 0; i<count; i++){
                    let divtag3 = document.createElement("div");
                    let ptag3 = document.createElement("p");
                    
                    if(selectedValue == prevRentArr[1 + i*5] || selectedValue=="전체"){
                        ptag3.innerHTML = "차량번호 : " + prevRentArr[0 + i*5] +
                                        " , 자동차 모델 이름 :" + prevRentArr[1 + i*5] +
                                        " , 대여 날짜 : " + prevRentArr[2 + i*5] +
                                        " , 반납 날짜 : " + prevRentArr[3 + i*5] +
                                        " , 최종 결제 금액 : " + prevRentArr[4 + i*5];

                        divtag3.appendChild(ptag3);
                        prevrentDiv.appendChild(divtag3);
                    }
                   
    
                }   
                
            }
        });
    }

    
}



