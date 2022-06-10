const callApi = function(method , req_data , url , succ_func, content_type=null){
    var xhr = new XMLHttpRequest();
    const data = req_data;
    xhr.onload = function() {
    if (xhr.status === 200 || xhr.status === 201) {
        const res_data = JSON.parse(xhr.responseText);
        succ_func(res_data);
    } else {
        alert('서버와 연결에 실패했습니다. 다시 시도해주십시오.')
    }
    };
    xhr.open(method, url);
    if(content_type=='json'){
        xhr.setRequestHeader('Content-Type', 'application/json'); 
    }
    xhr.setRequestHeader('test', 'application/json'); 
    xhr.send(data);
}

const sessOutSwal = function sessOutSwal(){
    Swal.fire({
        'text': '세션이 만료되었습니다. 다시 로그인해주십시오.',
        'icon': 'warning',
        'backdrop':false,
        'confirmButtonText': '확인',
    }). then(function(){
        location.replace(BASE_URL+'rs');
    });
}