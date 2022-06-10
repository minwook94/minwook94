const rsLogin = function rsLogin(form){
    // LOADING_ELEM.style.display = 'block';
    const form_data = new FormData(form); 
    callApi('POST',form_data , BASE_URL+'rs/adminLogin',loginSucc);
}

const loginSucc = function loginSucc(res_data){
    // LOADING_ELEM.style.display = 'none';
    // console.log(res_data);
    switch(res_data.code){
        case 200:

            // return false;
            location.href = BASE_URL+'rs/main#1';
        break;
        case 601:
            // Swal.fire({
            //     'text': '이메일 또는 비밀번호가 일치하지 않습니다.',
            //     'icon': 'error',
            //     'confirmButtonText': '확인',
            // })
            Swal.fire(
                '',
                '이메일 또는 비밀번호가 일치하지 않습니다.',
                'error'
            );
            return false;
        break;
    }
}