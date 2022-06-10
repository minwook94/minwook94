document.getElementById('name').addEventListener('keyup', function(e){
    if(e.keyCode === 13){
        document.getElementById('loginBtn').click();
    }
});
document.getElementById('loginBtn').addEventListener('click', function(){
    var c_num = document.getElementById('c_num').value;
    var name = document.getElementById('name').value;
    var url = BASE_URL+'candidate/login';

    if(name == '' || c_num == ''){
        Swal.fire({
            title: '입력 오류',
            text: '수험번호와 이름을 입력해주세요.',
            icon: 'error',
            confirmButtonText: '확인',
        });
        return false;
    }

    if(LOGIN_ACC == ''){
        Swal.fire({
            title: '로그인 가능한 시간이 아닙니다.',
            text: '담당자에게 문의해주세요.',
            icon: 'error',
            confirmButtonText: '확인',
        });
        return false;
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            name: name,
            c_num: c_num,
            rs_id: RS_ID,
        })
    }).then(function(response){
        response.json().then(function(data){
            if(data.code == 200){
                Swal.fire({
                    title: '로그인 완료',
                    text: '로그인이 완료되었습니다.',
                    icon: 'success',
                    confirmButtonText: '확인',
                    allowOutsideClick: false,
                }).then(function(){
                    Swal.fire({
                        // title: '해당하는 시험 장소를 확인해주세요.',
                        html: `<h3>해당하는 시험 장소를 확인해주세요.</h3>
                               <br>
                               <p style="text-align:left;">시험 장소 : <b>${data.p_name}</b></p>
                               <p style="text-align:left;">주소 : ${data.addr}</p>
                               <p style="text-align:left;">고사실 : <b>${data.c_class}</b></p>
                               <br>
                               <p>시험 장소 및 주소가 잘못되었을 경우 문의를 해주세요.</p>`,
                        icon: 'info',
                        showCancelButton: true,
                        cancelButtonText: '취소',
                        confirmButtonText: '확인',
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.value) {
                            window.location.href = BASE_URL+'candidate/question';
                        }
                    })
                        
                });
            }else if(data.code == 201){
                Swal.fire({
                    text: '정보가 일치하지 않습니다.',
                    icon: 'error',
                    confirmButtonText: '확인',
                    allowOutsideClick: false,
                });
            }else if(data.code == 202){
                Swal.fire({
                    text: '이미 확인이 완료된 응시자입니다. 입실하여주시기 바랍니다.',
                    icon: 'success',
                    confirmButtonText: '확인',
                    allowOutsideClick: false,
                })
            }
        });
    })
});