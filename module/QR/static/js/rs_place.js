let now_page = null;
let add_modal = null;

window.addEventListener('hashchange',()=>{
    now_page = window.location.hash.split('#')[1];
    getPlaceList(now_page);
});

window.onload = function(){
    now_page = window.location.hash.split('#')[1];
    // alert(now_page);
    if(!now_page){
        now_page = 1;
    }
    getPlaceList(now_page);
    
    // document.getElementById('addPlaceModal').show();
    add_modal = new bootstrap.Modal(document.getElementById('addPlaceModal'));

    // document.querySelectorAll('#mainBtnBox button').forEach(function(btn){
    //     btn.addEventListener('click',function(e){
    //         // alert(e.target.dataset.uri);
    //         location.href = BASE_URL+'rs/'+e.target.dataset.uri;
    //     });
    // });

}

const getPlaceList = function getPlaceList(page){
    now_page = page;
    callApi('GET',null,BASE_URL+'rs/placeList/'+page,writePlaceList);
}

const writePlaceList = function writePlaceList(res_data){
    // console.log(res_data);
    switch(res_data.code){
        case 601 :
            sessOutSwal(); return false;
        break;
        case 600 :
            Swal.fire({
                'text': '데이터가 없습니다. URL을 임의로 변경하지 마십시오.',
                'icon': 'warning',
                'closeOnClickOutside': false,
                'closeOnEsc': false,
                'confirmButtonText': '확인',
            }). then(function(){
                window.history.back();
            });
        break;
        case 200 :
            let tbody = document.getElementById('rsListTbody');
            // let list_items = ['rs_id','company_name','test_date','login_s_time','login_e_time'];
            let list_items = ['p_id','p_name','addr'];
            tbody.innerHTML = '';
            res_data.place_list.forEach(function(place){
                let tr = document.createElement('tr');
                // tr.dataset.rs_id = rs.rs_id;
                tr.addEventListener('click',function(){
                    // location.href = BASE_URL+'rs/detail/'+rs.rs_id;
                });
                list_items.forEach(function(item){
                    let td = document.createElement('td');
                    td.innerHTML = place[item];
                    tr.appendChild(td);
                });
                tbody.appendChild(tr);
            });

            let page_link_ul = document.getElementById('pageLinkUl');
            page_link_ul.innerHTML = '';
            let link_cnt = Math.ceil(res_data.rs_all_cnt/res_data.per);
            for(let i=1; i<=link_cnt; i++){
                let li = document.createElement('li');
                li.classList.add('page-item');
                if(now_page == (i)){
                    li.classList.add('active');
                }
                let a = document.createElement('a');
                a.classList.add('page-link');
                a.innerHTML = i;
                a.href = '#'+i;
                a.onclick = function(){
                    getPlaceList(i);
                }
                li.appendChild(a);
                page_link_ul.appendChild(li);
            }

            let total_cnt_box = document.getElementById('totalCntBox');
            total_cnt_box.innerHTML = '<b>총 '+res_data.place_all_cnt+'건</b>';
        break;
    }
}

const addPlace = function addPlace(form){
    let formData = new FormData(form);
    callApi('POST',formData,BASE_URL+'rs/addPlace',placeAddSucc);
}

const placeAddSucc = function placeAddSucc(res_data){
    switch(res_data.code){
        case 601 :
            sessOutSwal(); return false;
        break;
        case 200 :
            add_modal.hide();
            Swal.fire({
                'html': '새로운 장소가 추가되었습니다.',
                'backdrop':false,
                'icon': 'success',
                'confirmButtonText':'확인',
            }).then(function(){
                location.reload();
            });
        break;
    }
}