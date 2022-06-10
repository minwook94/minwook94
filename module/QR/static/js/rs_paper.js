let add_modal = null;
window.onload = function(){
    add_modal = new bootstrap.Modal(document.getElementById('addModal'));
    let nav_link = document.getElementsByClassName('nav-link');

    for(let i=0; i<nav_link.length; i++){
        nav_link[i].addEventListener('click',function(e){
            let my = e.currentTarget;
            if(!my.classList.contains('active')){
                my.parentElement.parentElement.querySelector('a.active').classList.remove('active');
                my.classList.add('active');
                
                let active_box = document.getElementById(my.dataset.index+'Box');
                active_box.parentElement.childNodes.forEach(function(item){
                    if(item.nodeType == 1){
                        item.classList.add('d-none');
                    }
                });
                active_box.classList.remove('d-none');
                document.getElementById(my.dataset.index+'ItemBox').innerHTML = '';

                if(active_box.querySelector('.item-active')){
                    active_box.querySelector('.item-active').classList.remove('item-active');
                }
            }
        });
    }

    let paper_content = document.getElementsByClassName('paper-content');

    for(let i=0; i<paper_content.length; i++){
        paper_content[i].addEventListener('click',function(e){
            let my = e.currentTarget;

            callApi('GET',null,BASE_URL+'rs/getPaperItems/'+my.dataset.div+'/'+my.dataset.index,function(res_data){
                switch(res_data.code){ 
                    case 601 :
                        sessOutSwal(); return false;
                    break;
                    case 201 :
                        // search_place_box.innerHTML = '존재하지 않습니다.';
                    break;
                    case 200 :
                        switch(my.dataset.div){
                            case 'qr_question_items' :
                                drawQuestionItems(my , res_data.data);
                            break;
                            case 'qr_agree_items' :
                                drawAgreeItems(my , res_data.data);
                            break;
                        }
                        // res_data.data.forEach(function(item){
                        //     let item_div = document.createElement('div');
                        //     item_div.className = 'd-flex mb-1';
                        //     let keys = Object.keys(item);
                        //     let item_html = '<div>'+item[keys[1]]+'번 항목 :</div><div class="col"><input class="border-0 w-100" value="'+item[keys[2]]+'"></input></div>';
                        //     if(item[keys[3]]){
                        //         let chk = null;
                        //         console.log(item[keys[3]]);
                        //         item[keys[3]] == 'Y' ? chk = '동의체크' : chk = '-';
                        //         item_html += '<div>'+chk+'</div>';
                        //     }
                        //     item_div.innerHTML = item_html;
                        //     box.appendChild(item_div);
                        // });

                        // box.appendChild(document.createElement('hr'));
                        // let btn_box = document.createElement('div');
                        // btn_box.className = 'text-end';
                        // btn_box.innerHTML = `
                        //     <button class="btn btn-light btn-sm" onclick="savePaperItem('+my.dataset.div+','+my.dataset.index+')">항목추가</button>
                        //     <button class="btn btn-danger btn-sm" onclick="savePaperItem('+my.dataset.div+','+my.dataset.index+')">삭제</button>
                        //     <button class="btn btn-primary btn-sm" onclick="savePaperItem('+my.dataset.div+','+my.dataset.index+')">수정</button>
                        // `;
                        // box.appendChild(btn_box);

                        // if(my.parentElement.querySelector('.item-active')){
                        //     my.parentElement.querySelector('.item-active').classList.remove('item-active');
                        // }
                        // my.classList.add('item-active');

                    
                    break;
                }

            });
        });
    }
}

const drawQuestionItems = function drawQuestionItems(my , data){
    let box = my.parentElement.nextElementSibling;
    box.innerHTML = '';
    let table = document.createElement('table');
    table.className = 'table table-bordered item-table';
    let thead = document.createElement('thead');
    let tr = document.createElement('tr');
    tr.innerHTML = `<th data-col="q_content">항목명</th><th width="5%" data-col="q_room">격리실</th><th class="text-center" width="1%">Del</th>`;
    thead.appendChild(tr);
    table.appendChild(thead);
    let tbody = document.createElement('tbody');
    data.forEach(function(item){
        let item_div = document.createElement('tr');
        item_div.innerHTML = `
            <td width="30%"><input class="border-0 w-100" value="${item.q_content}" /></td>
            <td><input class="border-0 w-100" value="${item.q_room}" /></td>
            <td class="text-center">
                <svg onclick="itemRemove(this);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-x" viewBox="0 0 16 16">
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </td>
        `;
        tbody.appendChild(item_div);
    });
    table.appendChild(tbody);
    box.appendChild(table);
    
    box.appendChild(document.createElement('br'));

    let btn_box = document.createElement('div');
    btn_box.className = 'text-end';
    btn_box.innerHTML = `
        <button class="btn btn-light btn-sm" onclick="addList(this)">항목추가</button>
        <button class="btn btn-danger btn-sm" data-div="question" data-index="${data[0].q_id}" data-action="delete" onclick="savePaperItem(this)">삭제</button>
        <button class="btn btn-primary btn-sm" data-div="question" data-index="${data[0].q_id}" data-action="update" onclick="savePaperItem(this)">수정</button>
    `;
    box.appendChild(btn_box);

    if(my.parentElement.querySelector('.item-active')){
        my.parentElement.querySelector('.item-active').classList.remove('item-active');
    }
    my.classList.add('item-active');
}


const drawAgreeItems = function drawQuestionItems(my , data){
    let box = my.parentElement.nextElementSibling;
    box.innerHTML = '';
    let table = document.createElement('table');
    table.className = 'table table-bordered item-table';
    let thead = document.createElement('thead');
    let tr = document.createElement('tr');
    tr.innerHTML = `
        <th data-col="a_title">항목명</th>
        <th data-col="a_content">설명</th>
        <th data-col="a_agree_type" class="text-center" width="3%">Chk</th>
        <th class="text-center" width="1%">Del</th>`;
    thead.appendChild(tr);
    table.appendChild(thead);
    let tbody = document.createElement('tbody');
    data.forEach(function(item){
        let item_div = document.createElement('tr');
        
        let chked = null;
        item.a_agree_type === 'Y' ? chked = 'checked' : chked = '';
        
        // item_div.innerHTML = `
        //     <td data-col="a_title" width="30%"><input class="border-0 w-100" value="${item.a_title}" /></td>
        //     <td data-col="a_content"><input class="border-0 w-100" value="${item.a_content}" /></div>
        //     <td data-col="a_agree_type" class="text-center"><input type="checkbox" ${chked} /></div>
        //     <td class="text-center">
        //         <svg onclick="itemRemove(this);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-x" viewBox="0 0 16 16">
        //         <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
        //         </svg>
        //     </td>
        // `;
        item_div.innerHTML = `
            <td width="30%"><input class="border-0 w-100" value="${item.a_title}" /></td>
            <td><input class="border-0 w-100" value="${item.a_content}" /></div>
            <td class="text-center"><input type="checkbox" ${chked} /></div>
            <td class="text-center">
                <svg onclick="itemRemove(this);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-x" viewBox="0 0 16 16">
                <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                </svg>
            </td>
        `;
        tbody.appendChild(item_div);
    });
    table.appendChild(tbody);
    box.appendChild(table);
    
    box.appendChild(document.createElement('br'));

    let btn_box = document.createElement('div');
    btn_box.className = 'text-end';
    btn_box.innerHTML = `
        <button class="btn btn-light btn-sm" onclick="addList(this)">항목추가</button>
        <button class="btn btn-danger btn-sm" data-div="agree" data-index="${data[0].a_id}" data-action="delete" onclick="savePaperItem(this)">삭제</button>
        <button class="btn btn-primary btn-sm" data-div="agree" data-index="${data[0].a_id}" data-action="update" onclick="savePaperItem(this)">수정</button>
    `;
    box.appendChild(btn_box);

    if(my.parentElement.querySelector('.item-active')){
        my.parentElement.querySelector('.item-active').classList.remove('item-active');
    }
    my.classList.add('item-active');
}

const itemRemove = function itemRemove(my){
    my.parentElement.parentElement.remove();
}

const addList = function addList(my){
    let table = my.parentElement.parentElement.querySelector('table');
    let th_count = table.querySelector('thead').querySelector('tr').querySelectorAll('th').length;

    let tr = document.createElement('tr');
    for(let i=0; i<th_count; i++){
        let td = document.createElement('td');
        let input_type = 'text';
        if(i==2){
            input_type = 'checkbox';
        }
        let td_html = `<input type="${input_type}" class="border-0 w-100" value="" />`;
        let td_class = '';
        if(i==(th_count-1)){ 
            td_html = `<svg onclick="itemRemove(this);" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="red" class="bi bi-x" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
            </svg>`;
            td_class = 'text-center';
        }
        td.innerHTML = td_html;
        td.className = td_class;
        tr.appendChild(td);
    }
    table.querySelector('tbody').appendChild(tr);
}


const savePaperItem = function savePaperItem(my){

    let data = new Object();

    data.action_prop = {div: my.dataset.div, index: my.dataset.index, action: my.dataset.action};

    Swal.fire({
        icon: 'warning',
        html: my.innerText+'하시겠습니까?',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        },
        showCancelButton: true,
        confirmButtonText: '예',
        cancelButtonText: '아니오',
    }).then((result)=>{
        if(result.isConfirmed){

            if(my.dataset.action === 'update'){ 
                let items = new Array();
                let content_table_thead_ths = my.parentElement.parentElement.querySelectorAll('table thead tr th');
                let content_table_tbody_trs = my.parentElement.parentElement.querySelectorAll('table tbody tr');
                // console.log(content_table_tbody_trs);
                
                let order = 1;
                let order_key_beans = {'question':'q','agree':'a'};
                content_table_tbody_trs.forEach(function(item){
                    let each_item = new Object();
                    each_item[order_key_beans[my.dataset.div]+'_order'] = order;
                    each_item[order_key_beans[my.dataset.div]+'_id'] = my.dataset.index;
                    let tds = item.querySelectorAll('td');
                    let td_order = 0;
                    tds.forEach(function(td){
                        if(td.querySelector('input')){
                            
                            switch(td.querySelector('input').type){
                                case 'text':
                                    // each_item[td.dataset.col] = td.querySelector('input').value;
                                    each_item[content_table_thead_ths[td_order].dataset.col] = td.querySelector('input').value;
                                break;
                                case 'checkbox':
                                    td.querySelector('input').checked ? each_item[content_table_thead_ths[td_order].dataset.col] = 'Y' : each_item[content_table_thead_ths[td_order].dataset.col] = 'N';
                                break;
                            }
    
                            if(each_item[content_table_thead_ths[td_order].dataset.col] == ''){  
                                Swal.fire({
                                    icon: 'error',
                                    html: '항목을 입력해주세요.',
                                    showClass: {
                                        popup: 'animate__animated animate__fadeInDown'
                                    },
                                    hideClass: {
                                        popup: 'animate__animated animate__fadeOutUp'
                                    }
                                });
                                return false;
                            }
                        }
                        td_order++;
                    });
                    items.push(each_item);
                    order++;
                });
                data.items = items;
            }

            // console.log(data);
            // return false;
            callApi('POST',JSON.stringify(data),BASE_URL+'rs/paperAction',paperActionSucc , 'json');
        }
    });
}

const paperActionSucc = function paperActionSucc(res_data){
    switch(res_data.code){
        case 601:
            sessOutSwal(); return false;
        break;
        case 501:
            sessOutSwal(); return false;
        break;
        case 200:
            Swal.fire({
                icon: 'success',
                html: '적용되었습니다.',
                // backdrop: false,
                confirmButtonText: '확인',
            })
            .then((result)=>{
                location.reload();
            });
        break;
    }
}


const openAddModal = function openAddModal(my){
    let add_modal_elem = document.getElementById('addModal');
    const modal_conf = {
        q:{
            title:'문진표',
            th:`
                <th data-col="q_content">항목명</th>
                <th data-col="q_room" width="10%">격리실</th>
                <th width="5%">Del</th>
                `
        },
        a:{
            title:'동의서',
            th:`
                <th data-col="a_title" width="30%">항목명</th>
                <th data-col="a_content">설명</th>
                <th data-col="a_agree_type" width="5%">Chk</th>
                <th width="5%">Del</th>`
        }
    };
    add_modal_elem.querySelector('.modal-title').innerText = modal_conf[my.dataset.item]['title'] + ' 추가';

    add_modal_elem.querySelector('.item-title').value ='';
    add_modal_elem.querySelector('.item-desc').value ='';
    
    let thead = add_modal_elem.querySelector('.modal-body thead');
    thead.innerHTML = modal_conf[my.dataset.item].th;
    add_modal_elem.querySelector('.modal-body tbody').innerHTML = '';

    add_modal_elem.querySelector('.modal-footer .btn-primary').dataset.item = my.dataset.item;

    add_modal.show();
}

const addItem = function addItem(my){
    let data = new Object();

    let modal_content = my.parentElement.parentElement;

    let data_chk = true;

    let conf = {
        a:{group_table:'qr_agree' , item_table:'qr_agree_items' , id:'a_id'},
        q:{group_table:'qr_questions' , item_table:'qr_question_items' , id:'q_id'},
    };

    data.group_table = conf[my.dataset.item].group_table;
    data.item_table = conf[my.dataset.item].item_table;
    data.id = conf[my.dataset.item].id;

    data.group = new Object();

    data.group[my.dataset.item+'_name'] = modal_content.querySelector('.item-title').value;
    data.group[my.dataset.item+'_desc'] = modal_content.querySelector('.item-desc').value;

    if(data.title == '' || data.desc == ''){ data_chk = false; }

    let item_ths = modal_content.querySelectorAll('.item-table thead tr th');
    let item_trs = modal_content.querySelectorAll('.item-table tbody tr');
    // console.log(item_ths);
    // console.log(item_trs);

    data.items = new Array();

    tr_loop : for(let i=0; i<item_trs.length; i++){
        let tds = item_trs[i].querySelectorAll('td');
        let each_item = new Object();
        for(let j=0; j<tds.length; j++){
            if(tds[j].querySelector('input')){
                switch(tds[j].querySelector('input').type){
                    case 'text':
                        each_item[item_ths[j].dataset.col] = tds[j].querySelector('input').value;
                    break;
                    case 'checkbox':
                        tds[j].querySelector('input').checked ? each_item[item_ths[j].dataset.col] = 'Y' : each_item[item_ths[j].dataset.col] = 'N';
                    break;
                }
            }
            if(each_item[item_ths[j].dataset.col] == ''){ data_chk = false; break tr_loop; }
            each_item[my.dataset.item+'_order'] = i+1;
        }
        data.items.push(each_item); 
    }

    if(data_chk){
        callApi('POST',JSON.stringify(data),BASE_URL+'rs/paperAddItem', function(res_data){
            // console.log(res_data);
            switch(res_data.code){
                case 601:
                    sessOutSwal(); return false;
                break;
                case 200:
                    // sessOutSwal(); return false;
                    Swal.fire({
                        icon: 'success',
                        html: '추가되었습니다.',
                        confirmButtonText: '확인',
                        backdrop: false,
                    }).then((result)=>{
                        location.reload();
                    });
                break;
            }
        } , 'json');
    }else{
        Swal.fire({
            html:'항목을 입력해주세요.',
            icon: 'error',
        });
        return false;
    }

}