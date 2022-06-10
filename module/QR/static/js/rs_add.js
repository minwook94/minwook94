let place_search_modal = null;
window.onload = function(){
    place_search_modal = new bootstrap.Modal(document.getElementById('placeSearchModal'));
    let move_badges = document.getElementsByClassName('move-badge');

    Array.prototype.forEach.call(move_badges , function(element){
        element.addEventListener('click', function(e){
            let btn = e.currentTarget;
            Swal.fire({
                icon:'warning',
                html:'문진표/동의서 관리 페이지로 이동합니다.<br>현재 페이지에서 작성했던 모든 내용은 사라집니다.',
                showCancelButton: true,
                confirmButtonText: '이동하기',
                cancelButtonText: '취소',
                confirmButtonClass: 'btn-primary',
            }).then((result) => {
                console.log(result.isConfirmed);
                if(result.isConfirmed){
                    location.href = BASE_URL+'rs/'+btn.dataset.uri;
                }else{
                    return false;
                }
            });
        });
    });

    document.getElementById('placeSearchInput').addEventListener('keyup', function(e){
        if(e.keyCode == 13){

            if(this.value.length >= 2){
                console.log(this.value);
                callApi('GET',null,BASE_URL+'rs/placeSearch/'+this.value,placeSearchSucc);
            }else{
                alert('2글자 이상 입력하세요.'); return false;
            }
        }
    });

    document.getElementById('activePlaceBtn').addEventListener('click', function(e){
        let add_place_box = document.getElementById('addPlaceBox');
        let active_places = add_place_box.querySelectorAll('div.alert');
        if(active_places.length <= 0){
            Swal.fire({
                'html': '선택된 장소가 없습니다.',
                'icon': 'warning',
                'button':'확인',
            });
        }else{
            let place_box = document.getElementById('placeBox');
            place_box.innerHTML = '';
            let place_table_str = "<table id='placeTable' class='table table-striped'><tbody>";

            active_places.forEach(function(elem){
                place_table_str += `<tr><td>`+elem.dataset.p_id+`</td><td>`+elem.innerText+`</td><td><input class="form-control form-control-sm" type="text" maxlength="6" placeholder="참여코드 6자리 입력(영문대/소 , 숫자)" /></td></tr>`;
            });
            place_table_str += "</tbody></table>";
            place_box.innerHTML = place_table_str;
            place_search_modal.hide();
        }
    });
}

const placeSearchSucc = function placeSearchSucc(res_data){
    let search_place_box = document.getElementById('searchPlaceBox');
    search_place_box.innerHTML = '';
    switch(res_data.code){ 
        case 601 :
            sessOutSwal(); return false;
        break;
        case 201 :
            search_place_box.innerHTML = '존재하지 않습니다.';
        break;
        case 200 :
            res_data.data.forEach(function(element){
                let each_place_div = document.createElement('div');
                each_place_div.className = 'p-2';
                each_place_div.innerHTML = '<b>'+element.p_name+'</b><br>'+element.addr;
                each_place_div.addEventListener('click', function(){
                    let add_place_box = document.getElementById('addPlaceBox');

                    let make_pass = true;
                    let active_places = add_place_box.querySelectorAll('div.alert');
                    if(active_places.length > 0){
                        active_places.forEach(function(elem){
                            if(elem.dataset.p_id == element.p_id){
                                Swal.fire({
                                    'html': '같은 장소가 이미 추가되어 있어요.',
                                    'icon': 'warning',
                                    'confirmButtonText': '확인',
                                });
                                make_pass = false;
                                return false;
                            }
                        });
                    }
                    if(make_pass){
                        let active_place = document.createElement('div');
                        active_place.className = 'alert alert-primary p-2 active-place';
                        active_place.dataset.p_id = element.p_id;
                        active_place.innerHTML = '<b>'+element.p_name+'</b>';
                        active_place.addEventListener('click',(active_place_event)=>{
                            active_place_event.currentTarget.remove();
                        });
                        add_place_box.append(active_place);
                    }
                    
                });
                search_place_box.append(each_place_div);

            });
        break;
    }
}

const addReservation = function addReservation(form){

    let pass = true;
    //시간 체크
    let start_time = form.s_time.value+form.s_minute.value;
    let end_time = form.e_time.value+form.e_minute.value;
    if(parseInt(start_time) >= parseInt(end_time)){
        Swal.fire({
            'html': '로그인 시작시간이 종료시간보다<br>크거나 같을 수 없습니다.',
            'icon': 'error',
            'confirmButtonText': '확인',
        });
        // return false;
        pass = false;
    }

    //place_group
    let place_group_data = new Array();
    let place_ids = new Array();
    let places = document.querySelectorAll('#placeBox tr');
    if(places.length <= 0){
        Swal.fire({
            'html': '장소를 추가하세요.',
            'icon': 'error',
            'confirmButtonText': '확인',
        });
        pass = false;
        return false;
    }else{
        places.forEach(function(elem){
            let place_code = elem.querySelector('input').value;
            if(place_code.length < 6){
                // console.log('코드가 6자리가 아닙니다.');
                // alert('코드가 6자리가 아닙니다.');
                Swal.fire({
                    'html': '장소 코드는 6자리 입니다.',
                    'icon': 'error',
                    'confirmButtonText': '확인',
                });
                pass = false;
                return false;
            }else{
                place_group_data.push({
                    'p_id':elem.querySelector('td:nth-child(1)').innerText,
                    'login_code':place_code
                });
                place_ids.push(elem.querySelector('td:nth-child(1)').innerText);
            }
        });
    }

    if(!pass){
        return false;
    }
    // console.log(place_ids);
    // return false;

    //응시자
    let candidate_data = new Array();
    let candidates = form.candidate.value.split('\n');
    let c_num_dup_chk_arr = new Array();
    // let c = 0;
    candidates.forEach(function(candidate){
        if(candidate != ''){
            let candidate_info = candidate.split('\t');
            let candidate_info_obj = {
                'c_name':candidate_info[0],
                'c_num': candidate_info[1],
                'c_class': candidate_info[3],
                'c_part': candidate_info[4],
            };
            // if(All_PLACE_NAMES.indexOf(candidate_info[2].trim()) > -1){
            // console.log(place_ids);
            // console.log(candidate_info[2].trim());
            if(place_ids.indexOf(candidate_info[2].trim()) > -1){
                // candidate_info_obj.p_id = All_PLACE_ID[All_PLACE_NAMES.indexOf(candidate_info[2].trim())];
                candidate_info_obj.p_id = candidate_info[2].trim();
                candidate_data.push(candidate_info_obj);
                c_num_dup_chk_arr.push(candidate_info_obj.c_num);
            }else{
                Swal.fire({
                    'html': '선택하지 않은 장소번호가 입력된 응시자가 있습니다.<br>장소번호 : '+candidate_info[2],
                    'icon': 'error',
                    'confirmButtonText':'확인',
                }); 
                pass = false;
                return false;
            }
        }
    });

    //수험번호 중복 체크
    let dup_set = new Set(c_num_dup_chk_arr);
    if(Object.keys(candidate_data).length > dup_set.size){
        Swal.fire({
            'html': '수험번호가 중복되는 응시자가 있습니다.',
            'icon': 'error',
            'confirmButtonText':'확인',
        }); 
        pass = false;
        return false;
    }

    //확인관
    let confirmer_data = new Array();
    let confirmers = form.confirmer.value.split('\n');
    let confirmer_dup_chk_arr = new Array();
    confirmers.forEach(function(confirmer){
        if(confirmer != ''){
            let confirmer_info = confirmer.split('\t');
            let confirmer_info_obj = {
                'cf_name':confirmer_info[0],
                'cf_pw': confirmer_info[1],
            };
            // if(All_PLACE_NAMES.indexOf(confirmer_info[2].trim()) > -1){
            if(place_ids.indexOf(confirmer_info[2].trim()) > -1){
                // console.log(All_PLACE_NAMES.indexOf(confirmer_info[2].trim()));
                // confirmer_info_obj.p_id = All_PLACE_ID[All_PLACE_NAMES.indexOf(confirmer_info[2].trim())];
                confirmer_info_obj.p_id = confirmer_info[2].trim();
                confirmer_data.push(confirmer_info_obj);
                if(confirmer_dup_chk_arr.indexOf(confirmer_info_obj.p_id+confirmer_info_obj.cf_name) > -1){
                    // confirmer_dup_chk_arr.push(confirmer_info_obj.p_id+confirmer_info_obj.cf_name);
                    Swal.fire({
                        'html': '한 장소에 이름이 중복된 확인관이 있습니다.<br>'+confirmer_info_obj.cf_name+'('+confirmer_info[2].trim()+')',
                        'icon': 'error',
                        'confirmButtonText':'확인',
                    }); 
                    // console.log(confirmer_dup_chk_arr);
                    pass = false;
                    return false;
                }else{
                    confirmer_dup_chk_arr.push(confirmer_info_obj.p_id+confirmer_info_obj.cf_name);
                }
                // console.log(confirmer_dup_chk_arr);
            }else{
                Swal.fire({
                    'html': '선택하지 않은 장소번호가 입력된 확인관 있습니다.<br>장소번호 : '+confirmer_info[2],
                    'icon': 'error',
                    'confirmButtonText':'확인',
                }); 
                pass = false;
                return false;
            }
        }
    });

    if(pass){
        // console.log(confirmer_dup_chk_arr);
        // alert('success');
        // return false;

        let formData = new FormData(form);
        formData.append('candidate', JSON.stringify(candidate_data));
        formData.append('confirmer', JSON.stringify(confirmer_data));
        formData.append('place_group', JSON.stringify(place_group_data));
        formData.append('login_s_time', start_time);
        formData.append('login_e_time', end_time);
        
        formData.delete('s_time');
        formData.delete('s_minute');
        formData.delete('e_time');
        formData.delete('e_minute');
        callApi('POST',formData,BASE_URL+'rs/addRs',addSucc);
    }
}

let test = null;

const addSucc = function addSucc(res_data){
    console.log(res_data);
    switch(res_data.code){
        case 601 :
            sessOutSwal(); return false;
        break;
        case 700 :
            Swal.fire({
                'html': '잘못된 요청입니다. ('+res_data.msg+')',
                'icon': 'error',
                'confirmButtonText':'확인',
            }); 
        break;
        case 500 :
            Swal.fire({
                'html': '예약에 실패했습니다. 다시 시도해주십시오.('+res_data.msg+')',
                'icon': 'error',
                'confirmButtonText':'확인',
            }); 
        break;
        case 200 :
            Swal.fire({
                'html': '예약되었습니다. 예약관리 페이지로 이동합니다',
                'backdrop':false,
                'icon': 'success',
                'confirmButtonText':'확인',
            }).then(function(){
                location.replace(BASE_URL+'rs/detail/'+res_data.rs_id);
            });
        break;
    }
}
