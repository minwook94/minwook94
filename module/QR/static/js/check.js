
document.addEventListener("DOMContentLoaded", function() {
    var video = document.createElement("video");
    var canvasElement = document.getElementById("canvas");
    var canvas = canvasElement.getContext("2d");
    var loadingMessage = document.getElementById("loadingMessage");
    var outputContainer = document.getElementById("output");

    function drawLine(begin, end, color) {
        
        canvas.beginPath();
        canvas.moveTo(begin.x, begin.y);
        canvas.lineTo(end.x, end.y);
        canvas.lineWidth = 4;
        canvas.strokeStyle = color;
        canvas.stroke();
    }
    // 카메라 사용시

    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
        video.srcObject = stream;
        video.setAttribute("playsinline", true);      // iOS 사용시 전체 화면을 사용하지 않음을 전달
        video.play();

        requestAnimationFrame(tick);
    });

    function tick() {

        loadingMessage.innerText = "⌛ 스캔 기능을 활성화 중입니다."

        if(video.readyState === video.HAVE_ENOUGH_DATA) {
            
            loadingMessage.hidden = true;
            canvasElement.hidden = false;
            outputContainer.hidden = false;
            
            // 읽어들이는 비디오 화면의 크기
            canvasElement.height = video.videoHeight;
            canvasElement.width = video.videoWidth;

            canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

            var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);

            var code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts : "dontInvert",
            });
            if(code) {
                    // QR코드 인식에 성공한 경우
                    // 인식한 QR코드의 영역을 감싸는 사용자에게 보여지는 테두리 생성
                    drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF0000");
                    drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF0000");
                    drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF0000");
                    drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF0000");

                    if(code.data == ""){
                        tick();
                        return;
                    }

                    fetch(BASE_URL+'checker/qrCheck', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: code.data,
                        button: "확인",
                    }).then(function(response){
                        response.json().then(function(data){
                            
                            if(data.code == 200){
                                document.getElementById('confirmSound').play();

                                fetch(BASE_URL+'checker/negativeConfirm', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: code.data,
                                        }).then(function(response){
                                            response.json().then(function(data){
                                                if(data.code == 200){

                                                    let confirmText = document.getElementById('confirmText');
                                                    confirmText.style.visibility = 'visible';
                                                    
                                                    setTimeout(() => {
                                                        confirmText.style.visibility = 'hidden';
                                                    }, 3000);

                                                    tick();
                                                }
                                            })
                                        })

                            }else if(data.code == 201){
                                document.getElementById('errorSound').play();
                                Swal.fire({
                                    title: '문진표 작성을 완료하지 않았습니다.',
                                    icon: 'error',
                                    confirmButtonText: '확인'
                                }).then(
                                    (confirm) => {
                                        if(confirm.isConfirmed){
                                            tick();
                                        }
                                    }
                                );
                            }else if(data.code == 202){

                                var q_content = '';

                                for(var i = 0; i < data.q_content.length; i++){
                                    q_content += `<p style="text-align:left; margin-top: 30px;">${i+1}. ${data.q_content[i]}</p>`
                                }
                                
                                document.getElementById('errorSound').play();
                                
                                Swal.fire({
                                    html: '<h5><b>예라고 응답한 문진내용이 있습니다.</b></h5>'+q_content+'<br><p>위치할 격리실 번호를 선택해주세요.</p>',
                                    confirmButtonText: '1격리실',
                                    showDenyButton: true,
                                    denyButtonText: '2격리실',
                                    showCancelButton: true,
                                    cancelButtonText: '아니오',
                                    allowOutsideClick: false,
                                }).then((confirm) =>{

                                    let isolation_room = '';
                                    if(confirm.isConfirmed){
                                        isolation_room = '1';
                                    }else if(confirm.isDenied){
                                        isolation_room = '2';
                                    }else{
                                        Swal.fire({
                                            title: '수정 후 다시 진행해주세요.',
                                            icon: 'warning',
                                            confirmButtonText: '확인',
                                            allowOutsideClick: false
                                        }).then(
                                            (confirm) => {
                                                if(confirm.isConfirmed){
                                                    tick();

                                                    return false;
                                                }
                                            }
                                        )
                                    }
                                    code.data = JSON.parse(code.data);
                                    code.data.isolation_room = isolation_room;

                                    if(isolation_room != ''){

                                        fetch(BASE_URL+'checker/positiveConfirm', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify(code.data),
                                        }).then(function(response){
                                            response.json().then(function(data){
                                                if(data.code == 200){
                                                    
                                                    Swal.fire({
                                                        html: '<h5>승인이 완료되었습니다.</h5>',
                                                        allowOutsideClick: false,
                                                        timer: 1000,
                                                        timerProgressBar: true,
                                                        showConfirmButton: false,
                                                        willClose: () => {
                                                            tick();
                                                        },
                                                    })
                                                }
                                            })
                                        })
                                    }

                                })
                            }else if(data.code == 203){

                                document.getElementById('errorSound').play();
                                Swal.fire({
                                    html: `<h5>${data.name}님은 문진표 확인 완료되었습니다.</h5>`,
                                    icon: 'info',
                                    allowOutsideClick: false,
                                    timer: 1000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    willClose: () => {
                                        tick();
                                    },
                                });
                            }else if(data.code == 601){
                                Swal.fire({
                                    html: '<h5>세션이 만료되었습니다.</h5>',
                                    icon: 'error',
                                    confirmButtonText: '확인',
                                    allowOutsideClick: false,
                                }).then((confirm) => {
                                    location.href = BASE_URL + 'checker/' + RS_ID;
                                })
                            }else {
                                document.getElementById('errorSound').play();
                                Swal.fire({
                                    html: '<h5>잘못된 정보의 QR 코드입니다. 다시 촬영해주세요.</h5>',
                                    icon: 'error',
                                    confirmButtonText: '확인',
                                    timer: 1000,
                                    timerProgressBar: true,
                                    showConfirmButton: false,
                                    willClose: () => {
                                        tick();
                                    },
                                });
                            }
                        })
                    })
                    return;
                }
            }
          requestAnimationFrame(tick);
    }
});

document.getElementById('logout').addEventListener('click', ()=>{
    Swal.fire({
        html: '<h5>로그아웃 하시겠습니까?</h5>',
        icon: 'warning',
        confirmButtonText: '로그아웃',
        showCancelButton: true,
        cancelButtonText: '취소',
        allowOutsideClick: false
    }).then((confirm) => {

        fetch(BASE_URL+'checker/logout', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            },
        }).then(function(response){
            response.json().then(function(data){
                if(data.code == 200){
                    Swal.fire({
                        html: '<h5>로그아웃 되었습니다.</h5>',
                        icon: 'success',
                        confirmButtonText: '확인',
                        allowOutsideClick: false,
                        willClose: () => {
                            location.href = BASE_URL+'checker/'+RS_ID;
                        },
                    })
                }
            })
        });
    })
});

var qrlink = document.getElementById('qrlink');
qrlink.addEventListener('keyup', (e) => {
    var qrcode = new QRious({
        element: document.getElementById('qrcode'),
        value: qrlink.value,
        size: 200,
        level: 'H',
        background: '#DDDDDD',
        backgroundAlpha: 0.5,
    });
});