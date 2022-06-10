let now_page = null;

window.addEventListener('hashchange',()=>{
    now_page = window.location.hash.split('#')[1];
    getRsList(now_page);
});

window.onload = function(){
    now_page = window.location.hash.split('#')[1];
    // alert(now_page);
    if(!now_page){
        now_page = 1;
    }
    getRsList(now_page);
    
    document.querySelectorAll('#mainBtnBox button').forEach(function(btn){
        btn.addEventListener('click',function(e){
            // alert(e.target.dataset.uri);
            location.href = BASE_URL+'rs/'+e.target.dataset.uri;
        });
    });

}

const getRsList = function getRsList(page){
    now_page = page;
    callApi('GET',null,BASE_URL+'rs/rsList/'+page,writeRsList);

}

const writeRsList = function writeRsList(res_data){
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
            let list_items = ['rs_id','company_name','test_date','login_s_time','login_e_time'];
            tbody.innerHTML = '';
            res_data.rs_list.forEach(function(rs){
                let tr = document.createElement('tr');
                // tr.dataset.rs_id = rs.rs_id;
                tr.addEventListener('click',function(){
                    location.href = BASE_URL+'rs/detail/'+rs.rs_id;
                });
                list_items.forEach(function(item){
                    let td = document.createElement('td');
                    td.innerHTML = rs[item];
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
                    getRsList(i);
                }
                li.appendChild(a);
                page_link_ul.appendChild(li);
            }

            let total_cnt_box = document.getElementById('totalCntBox');
            total_cnt_box.innerHTML = '<b>총 '+res_data.rs_all_cnt+'건</b>';
        break;
    }

}