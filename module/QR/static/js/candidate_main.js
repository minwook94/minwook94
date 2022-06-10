window.addEventListener('load', () => {
    let today = new Date();

    let year = today.getFullYear();
    let month = today.getMonth() + 1;
    let day = today.getDate();
    let todayStr = year + "년 " + month + "월 " + day + "일";

    let todayText = document.getElementById('today');
    todayText.innerText = todayStr;
    todayText.style.fontWeight = 'bold';

});

document.getElementsByClassName('logoutBtn')[0].addEventListener('click', () => {
    location.href = BASE_URL + 'candidate/' + RS_ID;
});
document.getElementsByClassName('logoutBtn')[1].addEventListener('click', () => {
    location.href = BASE_URL + 'candidate/' + RS_ID;
});


var canvas = document.getElementById('signature-pad');

function resizeCanvas() {
    // const ratio =  Math.max(window.devicePixelRatio || 1, 1);
    // canvas.getContext("2d").scale(ratio, ratio);
}
window.addEventListener("resize", resizeCanvas);
resizeCanvas();

var signaturePad = new SignaturePad(canvas, {
    backgroundColor: 'rgba(255, 255, 255, 0.1)' // necessary for saving image as JPEG; can be removed is only saving as PNG or SVG
});

let sign_img = '';

document.getElementById('save').addEventListener('click', function () {

    if (signaturePad.isEmpty()) {
        Swal.fire({ text: "서명을 해주세요.", icon: "error", confirmButtonText: '확인', });
        return false;
    }else{
        let data = signaturePad.toDataURL('image/png');
        sign_img = data;
    }

    let sign_div = document.getElementById('sign');

    sign_div.innerHTML = '';

    var data = signaturePad.toDataURL('image/jpg');

    var img = document.getElementById('signImg');
    img.src = data;

    var myModal = document.getElementById('signModal');

    Swal.fire({ text: "서명이 완료되었습니다.", icon: "success", confirmButtonText: '확인', }).then((value) => {
        myModal.style.display = "none";
        myModal.ariaHidden = "true";
        myModal.removeAttribute("aria-hidden");
        document.querySelector('.modal-open').style = "";
        document.querySelector('.modal-backdrop').remove();
    });

});

document.getElementById('clear').addEventListener('click', function () {
    signaturePad.clear();
});

let agree_text = document.getElementsByClassName('agree_text');

for (let i = 0; i < agree_text.length; i++) {
    agree_text[i].addEventListener('click', (e) => {
        document.getElementsByClassName('agree_text')[i].children[0].click();
    });
}

// let first_img;

// const containerShot = function containerShot(send_data) {

    // let survay2 = document.getElementById('survay2');

    // html2canvas(survay2).then(canvas => {


    // });
// }

// document.getElementById('nextBtn').addEventListener('click', function () {

    // let survay1 = document.getElementById('survay1');

    // html2canvas(survay1).then(canvas => {
    //     first_img = canvas.toDataURL('image/png').replace("data:image/png;base64,", "")
    // });
// });

document.getElementById('addBtn').addEventListener('click', function () {

    
    let loading = document.createElement('div');
    loading.id = 'loading';
    let loading_img = document.createElement('img');
    loading_img.src = BASE_URL + 'static/img/loading.gif';
    loading_img.classList.add('loading-image');

    let loading_text = document.createElement('div');
    loading_text.classList.add('loading-text');
    loading_text.innerText = '입력 중입니다. 잠시만 기다려주세요.';

    loading.append(loading_img, loading_text);

    document.getElementsByTagName('body')[0].append(loading);

    let input_data = document.getElementsByClassName('input_data');
    let agree = document.getElementsByClassName('agree');
    let send_data = new Object();

    let question_arr = new Array();

    let j = 0;

    for (let i = 0; i < input_data.length; i++) {
        let value = input_data[i].options[input_data[i].selectedIndex].value;
        if (value) {
            question_arr.push(input_data[i].options[input_data[i].selectedIndex].value);
            j++;
        }
    }

    send_data.question = question_arr;

    for (let i = 0; i < agree.length; i++) {
        if (agree[i].checked) { 
            j++;
         }
    }

    let count_answer = document.getElementsByClassName('answer').length;

    if (j < count_answer) {
        Swal.fire({ text: "전체 문항에 대해 답을 해주세요.", icon: "error", confirmButtonText: '확인', });
        loading.remove();

        return false;
    };

    if (document.getElementById('signImg').src == '') {
        Swal.fire({ text: "이름 옆에 서명을 해주세요.", icon: "error", confirmButtonText: '확인', });
        loading.remove();

        return false;
    }

    Swal.fire({
        title: '저장 하시겠습니까?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '저장',
        cancelButtonText: '취소'
    }).then((result) => {
        if(result.isDismissed){
            loading.remove();
            return false;
        }else if(result.isConfirmed){
            let post_obj = {
                // first_img: first_img,
                // second_img: canvas.toDataURL('image/png').replace("data:image/png;base64,", ""),
                user_data: send_data,
                sign_img: sign_img,
                rs_id: RS_ID,
            };
             
            let xhr = new XMLHttpRequest();
        
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    let result = JSON.parse(xhr.responseText);
                    if (result.code == 200) {
                        Swal.fire({
                            title: "문진 작성이 완료되었습니다.",
                            icon: "success",
                            confirmButtonText: '확인',
                        }).then((value) => {
                            loading.remove();
                            location.href = BASE_URL + 'candidate/result';
                        });
                    } else if (result.code == 201) {
        
                        loading.remove();
                        
                        Swal.fire({
                            title: "데이터베이스에 문제가 있습니다. 관리자에게 문의해주세요.",
                            icon: "error",
                            confirmButtonText: '확인',
                        })
                    } else if (result.code == 601) {
                        location.href = BASE_URL + 'candidate/' + RS_ID;
                    }
                }
            }
        
            xhr.open('POST', BASE_URL + 'candidate/save_img');
            xhr.setRequestHeader('Content-Type', 'application/json');
            xhr.send(JSON.stringify(post_obj));
        }
    });
});