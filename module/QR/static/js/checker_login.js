document.getElementById('cf_name').addEventListener('keyup', function(e){
    if(e.keyCode === 13){
        document.getElementById('loginBtn').click();
    }
});
document.getElementById('cf_pw').addEventListener('keyup', function(e){
    if(e.keyCode === 13){
        document.getElementById('loginBtn').click();
    }
});


document.getElementById('loginBtn').addEventListener('click', function(){
    var cf_name = document.getElementById('cf_name').value;
    var cf_pw = document.getElementById('cf_pw').value;
    var url = BASE_URL+'checker/login';

    if(cf_name == '' || cf_pw == ''){
        Swal.fire({
            title: '입력 오류',
            text: '아이디와 비밀번호를 입력해주세요.',
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
            cf_name: cf_name,
            cf_pw: cf_pw,
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
                }).then(function(){
                    Swal.fire({
                        html: `<h3>하단의 주소가 맞는지 확인해주세요.</h3>
                               <br>
                               <p style="text-align:left;">장소 : <b>${data.place.p_name}</b></p>
                               <p style="text-align:left;">주소 : <b>${data.place.addr}</b></p>
                               `,
                        icon: 'info',
                        showCancelButton: true,
                        cancelButtonText: '취소',
                        confirmButtonText: '확인'
                    }).then((result) => {
                        if(result.value) {
                            location.href = BASE_URL+'checker/check/' + RS_ID;
                        }
                    });
                });
            }else if(data.code == 201){
                Swal.fire({
                    text: '로그인에 실패하였습니다. 아이디와 비밀번호를 확인해주세요.',
                    icon: 'error',
                    confirmButtonText: '확인',
                });
            }
        });
    })
});