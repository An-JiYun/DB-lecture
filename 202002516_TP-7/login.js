let loginBtn = document.getElementById("loginBtn");
let logoutBtn = document.getElementById("logoutBtn");

let loginBefore = document.getElementById("loginBeforeTable");
let loginAfter = document.getElementById("loginAfterTable");

let notManager = document.getElementById("notManager");
let manager = document.getElementById("manager");

loginBtn.addEventListener("click", login);
logoutBtn.addEventListener("click", logout);

manager.addEventListener("click", recordCheck);

if (localStorage.getItem("cno") == null) {
    // 세션 스토리지 확인 후 login이 되어있지 않으면 로그인 창
    notManager.style.display = 'none';
    manager.style.display = 'none';
    loginBefore.style.display = 'block';
    loginAfter.style.display = 'none';
}else {
    //login이 되어있으면
    let name = localStorage.getItem("name");
    document.getElementById("cname").innerHTML = name+" 님";

    if(name == "관리자"){
        manager.style.display = 'block';
        notManager.style.display = 'none';
    }else{
        manager.style.display = 'none'
        notManager.style.display = 'block';
    }

    loginBefore.style.display = 'none';
    loginAfter.style.display = 'block';
}

let cno = document.getElementById("cno");
let passwd = document.getElementById("passwd");

function login(){
    let cnoVal = cno.value;
    let pwVal = passwd.value;
    console.log(cnoVal+" "+pwVal);
    if(cnoVal != "" && pwVal != ""){
        $.ajax({
            url				: 'login.php',
            type			: 'POST',
            data			: {
            cno	            : cnoVal
            },
            success : function(data){
                let dataArr = data.split(" ");
                if(pwVal == dataArr[2]){
                    localStorage.setItem("cno", dataArr[0]);
                    localStorage.setItem("name", dataArr[1]);

                    document.getElementById("cname").innerHTML = dataArr[1]+"님";
                    
                    let name = localStorage.getItem("name");
                    if(name == "관리자"){
                        notManager.style.display = 'none';
                        manager.style.display = 'block';
                    }else {
                        notManager.style.display = 'block';
                        manager.style.display = 'none';
                    }
                    loginBefore.style.display = 'none';
                    loginAfter.style.display = 'block';

                } else{
                    alert("아이디와 비밀번호가 일치하지 않습니다");
                }     
            }
        });
    }else{
        alert("아이디와 비밀번호를 모두 입력해주세요.");
    }
}

function logout(){
    alert("로그아웃 되었습니다.");
    
    notManager.style.display = 'none';
    manager.style.display = 'none';

    if(recordTable.style.display =='block'){
        recordTable.style.display = 'none';
    }
    if(recordTable2.style.display =='block'){
        recordTable2.style.display = 'none';
    }
    if(recordTable3.style.display =='block'){
        recordTable3.style.display = 'none';
    }

    localStorage.removeItem("cno");
    localStorage.removeItem("name");
    cno.value = "";
    passwd.value = "";

    loginBefore.style.display = 'block';
    loginAfter.style.display = 'none';
}


// 관리자 레코드
let recordTable = document.getElementById("recordTable");
let recordTable2 = document.getElementById("recordTable2");
let recordTable3 = document.getElementById("recordTable3");

function recordCheck(){
    $.ajax({
        url				: 'recode.php',
        type			: 'POST',
        success : function(data){
            let html = createTable(data);
            recordTable.innerHTML = html;

            if(recordTable.style.display == 'none'){
                recordTable.style.display = 'block';
            }else{
                recordTable.style.display = 'none';
            }
        }
    });

    $.ajax({
        url				: 'recode2.php',
        type			: 'POST',
        success : function(data){
            let html = createTable2(data);
            recordTable2.innerHTML = html;

            if(recordTable2.style.display == 'none'){
                recordTable2.style.display = 'block';
            }else{
                recordTable2.style.display = 'none';
            }
        }
    });

    
    $.ajax({
        url				: 'recode3.php',
        type			: 'POST',
        success : function(data){
            let html = createTable3(data);
            recordTable3.innerHTML = html;

            if(recordTable3.style.display == 'none'){
                recordTable3.style.display = 'block';
            }else{
                recordTable3.style.display = 'none';
            }
        }
    });
}

function createTable(data){
    html = "<h3>현재 대여된 차량의 정보</h3>";
    html += "<table style=\"border-collapse: collapse; text-align: center; border: solid 1px black; width: 1000px\">\n";
    html += "<tr style=\"border: solid 1px black;\"><th>대여한 사람</th>";
    html += "<th>차량 번호</th>";
    html += "<th>대여 시작 날짜</th>";
    html += "<th>대여 종료 날짜</th>";
    html += "<th>대여 기간</th>";
    html += "<th>대여료</th>";
    html += "<th>차종</th>";
    html += "<th>차모델 이름</th></tr>";

    let cars = data.split("\n");
    console.log(cars);

    for(let i=0; i<cars.length-1; i++){
        let info = cars[i].split("*");

        html+="<tr>\n";
        for(let j=0; j<info.length; j++){
            html+="<td>"+info[j]+"</td>\n";
        }
        html+="</tr>\n";
    }
    html+="</table>";

    return html;
}

function createTable2(data){
    html = "<h3>렌트카 별 누적 렌트 횟수</h3>";
    html += "<table style=\"border-collapse: collapse; text-align: center; border: solid 1px black; width: 350px\">\n";
    html += "<tr style=\"border: solid 1px black;\"><th>차량 번호</th>";
    html += "<th>누적 렌트 횟수</th></tr>";

    let cars = data.split("\n");
    console.log(cars);

    for(let i=0; i<cars.length-1; i++){
        let info = cars[i].split("*");

        html+="<tr>\n";
        for(let j=0; j<info.length; j++){
            html+="<td>"+info[j]+"</td>\n";
        }
        html+="</tr>\n";
    }
    html+="</table>";

    return html;
}

function createTable3(data){
    html = "<h3>예약 된 차 중 대여 날짜가 빠른 순위</h3>";
    html += "<table style=\"border-collapse: collapse; text-align: center; border: solid 1px black; width: 700px\">\n";
    html += "<tr style=\"border: solid 1px black;\"><th>차량 번호</th>";
    html += "<th>예약자 id</th>";
    html += "<th>예약자 명</th>";
    html += "<th>대여 시작 날짜</th>";
    html += "<th>순위</th>";
    html += "<th>일련번호</th></tr>";

    let cars = data.split("\n");
    console.log(cars);

    for(let i=0; i<cars.length-1; i++){
        let info = cars[i].split("*");

        html+="<tr>\n";
        for(let j=0; j<info.length; j++){
            html+="<td>"+info[j]+"</td>\n";
        }
        html+="</tr>\n";
    }
    html+="</table>";

    return html;
}