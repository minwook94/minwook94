const Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 2000,
    // timerProgressBar: true,

    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})
window.onload = function(){

    let login_btn = document.getElementById('loginBtn');
    
    login_btn.addEventListener('click', ()=>{
    
        let id = document.getElementById('totalUserId').value;
        let pw = document.getElementById('totalUserPw').value;
        if(id == '' || pw == ''){
            // Swal.fire({
            //     'text': '아이디와 비밀번호를 입력해주십시오.',
            //     'icon': 'warning',
            //     'backdrop':false,
            //     'confirmButtonText': '확인',
            // });
            Toast.fire({
                html: '<small >아이디와 비밀번호를 입력해주십시오.</small>',
                icon: 'warning',
            });
            
            return false;
        }else{
            let req_data = {
                'total_user_id': id,
                'total_user_pw': pw,
                'rs_id': RS_ID,
            };
            callApi('POST', JSON.stringify(req_data), BASE_URL+'total/loginChk', (res_data)=>{

                switch(res_data.code){
                    case 700 :
                        Toast.fire({
                            html: '<h5 class="text-success">잘못된 요청입니다.</h5>',
                            icon: 'error',
                        }); return false;
                    break;
                    case 600 :
                        Toast.fire({
                            html: '<h5 class="text-success">아이디와 비밀번호가<br>일치하지 않습니다.</h5>',
                            icon: 'error',
                        }); return false;
                    break;
                    case 200 :
                        location.href = BASE_URL+'total/main';
                    break;
                }
            });
        }
    
    
        // fetch('./login', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/json'
        //     },
        //     body: JSON.stringify(send_data)
        // }).then(res=>{
        //     return res.json();
        // }).then(res=>{
        //     if(res.result == 'success'){
        //         alert('로그인 성공');
        //         location.href = './';
        //     }else{
        //         alert('로그인 실패');
        //     }
        // })
    
    });
}