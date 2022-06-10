let main_card = document.querySelectorAll('.main_card');
let count_text = document.querySelectorAll('.count_text');
const modal = document.querySelector('.modal');
const modal_close = document.querySelector('.modal_close');
let loading = document.getElementById('loading');
let first_isolation = document.querySelector('#first_isolation');
let second_isolation = document.querySelector('#second_isolation');

const body = document.querySelector('body');

modal_close.addEventListener('click', function () {
    body.style.overflow = 'auto';
    modal.classList.remove('show');
});

document.getElementById('logout').addEventListener('click', function () {
    Swal.fire({
        title: '로그아웃 하시겠습니까?',
        text: '로그아웃 하시면 코드 입력 페이지로 이동합니다.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: '로그아웃',
        cancelButtonText: '취소'
    }).then((result) => {
        if (result.value) {
            Swal.fire({
                title: '로그아웃 중입니다.',
                text: '잠시 후 코드 입력 페이지로 이동합니다.',
                icon: 'success',
                showConfirmButton: false
            })
            setTimeout(function () {
                location.href = BASE_URL + 'management/' + RS_ID + '/' + P_ID;
            }, 1000);
        }
    });
});

document.getElementById('refreshBtn').addEventListener('click', function () {
    loading.style.display = 'block';
    refresh(getCount);
});

var getCount = function getCount() {
    fetch(BASE_URL + 'management/getCandidateCount', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'rs_id': RS_ID,
            'p_id': P_ID
        })

    }).then(function (response) {
        response.json().then(function (data) {

            if (data.code == 601) {
                Swal.fire({
                    title: '접속 시간이 만료되었습니다.',
                    text: '코드 입력 페이지로 이동합니다.',
                    icon: 'error',
                    confirmButtonText: '확인'
                }).then(function () {
                    location.href = BASE_URL + 'management/' + RS_ID + '/' + P_ID;
                });
            }

            for (let i = 0; i < count_text.length; i++) {

                count_text[i].innerHTML = '<p>' + data.c_confirm[count_text[i].dataset.num] + ' / ' + data.c_class[count_text[i].dataset.num] + '명</p>' + '<p>제외 : ' + data.isolation[count_text[i].dataset.num] + '</p>';

                let total = data.c_confirm[count_text[i].dataset.num] + data.isolation[count_text[i].dataset.num];
                if (total == data.c_class[count_text[i].dataset.num]) {
                    main_card[i].style.backgroundColor = '#DCE2F0';
                }
            }

            document.querySelector('#total').innerHTML = data.confirm_total + ' / ' + data.total + ' (입실율 : ' + data.percentage + '%)';

            let first_isolation_text = document.getElementById('first_isolation_text');
            let second_isolation_text = document.getElementById('second_isolation_text');

            first_isolation_text.innerText = data.first_isolation + '명';
            second_isolation_text.innerText = data.second_isolation + '명';

            first_isolation.append(first_isolation_text);
            second_isolation.append(second_isolation_text);

            if (loading.style.display == 'block') {
                loading.style.display = 'none';

                Toast.fire({
                    html: '<h5 class="text-success text-center mt-2">새로고침이 완료되었습니다.</h5>',
                    icon: 'success',
                });
            }
        });
    });
}

const Toast = Swal.mixin({
    toast: true,
    position: 'top',
    showConfirmButton: false,
    timer: 1500,
    timerProgressBar: true,

    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

refresh(getCount);

function refresh(callback) {
    callback();
}

main_card.forEach(element => {
    element.addEventListener('click', (e) => {

        loading.style.display = 'block';

        let isolation_bottom = document.getElementsByClassName('isolation_bottom');

        for (let i = 0; i < isolation_bottom.length; i++) {
            isolation_bottom[i].style.display = 'none';
        }

        fetch(BASE_URL + 'management/detail', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                'rs_id': RS_ID,
                'p_id': P_ID,
                'c_class': e.currentTarget.dataset.num
            })

        }).then(function (response) {
            response.json().then(function (data) {

                if (data.code == 601) {
                    Swal.fire({
                        title: '세션이 만료되었습니다.',
                        text: '로그인 페이지로 이동합니다.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    }).then(function () {
                        location.href = BASE_URL + 'management/' + RS_ID + '/' + P_ID;
                    });
                } else if (data.code == 200) {

                    let modal_table = document.getElementById('modalTable');

                    modal_table.innerHTML = '';

                    let thead = document.createElement('thead');
                    let tr_thead = document.createElement('tr');
                    let th_thead1 = document.createElement('th');
                    let th_thead2 = document.createElement('th');
                    let th_thead3 = document.createElement('th');
                    let th_thead4 = document.createElement('th');

                    th_thead1.innerHTML = '수험번호';
                    th_thead2.innerHTML = '이름';
                    th_thead3.innerHTML = '지원분야';
                    th_thead4.innerHTML = '제출여부';

                    tr_thead.append(th_thead1, th_thead2, th_thead3, th_thead4);

                    thead.append(tr_thead);

                    let tbody = document.createElement('tbody');
                    let count = 0;
                    let confirm = 0;

                    for (let i = 0; i < data.data.length; i++) {
                        let tr = document.createElement('tr');
                        let td1 = document.createElement('td');
                        let td2 = document.createElement('td');
                        let td3 = document.createElement('td');
                        let td4 = document.createElement('td');

                        td1.innerHTML = data.data[i].c_num;
                        td2.innerHTML = data.data[i].c_name;
                        td3.innerHTML = data.data[i].c_part;

                        switch (data.data[i].c_confirm) {
                            case '0':
                                td4.innerHTML = '<span style="color:green">정상제출</span>';
                                confirm++;
                                break;
                            case '1':
                                td4.innerHTML = '<span style="color:red">1격리실</span>';
                                confirm++;
                                break;
                            case '2':
                                td4.innerHTML = '<span style="color:red">2격리실</span>';
                                confirm++;
                                break;
                            default:
                                td4.innerHTML = '<span style="color:blue">미제출</span>';
                                break;
                        }
                        tr.append(td1, td2, td3, td4);

                        listClick(tr);

                        tr.dataset.confirm = data.data[i].c_confirm;
                        tbody.appendChild(tr);
                        modal_table.append(thead, tbody);

                        count++;
                    }

                    document.getElementById('modalTitle').innerHTML = '<h3><b>' + data.c_class + '고사실</b></h3>';
                    document.getElementById('modalSubTitle').innerHTML = ' <h6>제출 : ' + confirm + ' / ' + count + '</h6> <h6>출석율 : ' + (confirm / count * 100).toFixed(1) + '%</h6>';

                    loading.style.display = 'none';
                    body.style.overflow = 'hidden';
                    modal.classList.add('show');

                } else if (data.code == 201) {
                    loading.style.display = 'none';
                    Swal.fire({
                        title: '입실 내역이 없습니다.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    });
                } else if (data.code == 502) {
                    loading.style.display = 'none';
                    Swal.fire({
                        title: '비정상적인 접근입니다.',
                        text: '관리자에게 문의해주세요.',
                        icon: 'error',
                        confirmButtonText: '확인'
                    })
                }
            });
        })
    });
});

first_isolation.addEventListener('click', () => {
    loading.style.display = 'block';

    fetch(BASE_URL + 'management/isolationDetail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'rs_id': RS_ID,
            'p_id': P_ID,
            'c_confirm': '1'
        })
    }).then((response) => {
        response.json().then((data) => {

            loading.style.display = 'none';

            if (data.code == 200) {

                document.getElementById('modalTitle').innerHTML = '<h3><b>1 격리실</b></h3>';

                document.getElementById('modalSubTitle').innerHTML = null;

                let modal_table = document.getElementById('modalTable');

                let modal_footer = document.getElementById('modalFooter');

                modal_table.innerHTML = '';

                let thead = document.createElement('thead');
                let tr_thead = document.createElement('tr');
                let th_thead1 = document.createElement('th');
                let th_thead2 = document.createElement('th');
                let th_thead3 = document.createElement('th');
                let th_thead4 = document.createElement('th');

                th_thead1.innerHTML = '응시번호';
                th_thead2.innerHTML = '이름';
                th_thead3.innerHTML = '지원분야';
                th_thead4.innerHTML = '고사실';

                tr_thead.append(th_thead1, th_thead2, th_thead3, th_thead4);

                thead.append(tr_thead);

                let tbody = document.createElement('tbody');

                for (let i = 0; i < data.data.length; i++) {

                    let tr = document.createElement('tr');
                    let td1 = document.createElement('td');
                    let td2 = document.createElement('td');
                    let td3 = document.createElement('td');
                    let td4 = document.createElement('td');

                    td1.innerHTML = data.data[i].c_num;
                    td2.innerHTML = data.data[i].c_name;
                    td3.innerHTML = data.data[i].c_part;
                    td4.innerHTML = data.data[i].c_class + '고사실';

                    tr.append(td1, td2, td3, td4);

                    tr.dataset.num = data.data[i].c_confirm;

                    tbody.appendChild(tr);
                }

                modal_table.append(thead, tbody);

                modal_footer.innerHTML = `<p class="isolation_bottom">격리실의 응시자를 수정하려면 해당 고사실의 목록을 클릭하여 수정할 수 있습니다.</p>`;

                loading.style.display = 'none';
                body.style.overflow = 'hidden';
                modal.classList.add('show');
            }

            if (data.code == 201) {
                Swal.fire({
                    html: '<h3>입실 내역이 없습니다.</h3>',
                    icon: 'info',
                    confirmButtonText: '확인'
                })
            }
        })
    });
});

second_isolation.addEventListener('click', () => {
    loading.style.display = 'block';

    fetch(BASE_URL + 'management/isolationDetail', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            'rs_id': RS_ID,
            'p_id': P_ID,
            'c_confirm': '2'
        })
    }).then((response) => {
        response.json().then((data) => {
            loading.style.display = 'none';

            if (data.code == 200) {

                document.getElementById('modalTitle').innerHTML = '<h3><b>2 격리실</b></h3>';

                document.getElementById('modalSubTitle').innerHTML = null;
                
                let modal_table = document.getElementById('modalTable');

                let modal_footer = document.getElementById('modalFooter');
                modal_table.innerHTML = '';

                let thead = document.createElement('thead');
                let tr_thead = document.createElement('tr');
                let th_thead1 = document.createElement('th');
                let th_thead2 = document.createElement('th');
                let th_thead3 = document.createElement('th');

                th_thead1.innerHTML = '응시번호';
                th_thead2.innerHTML = '이름';
                th_thead3.innerHTML = '고사실';

                tr_thead.append(th_thead1, th_thead2, th_thead3);

                thead.append(tr_thead);

                let tbody = document.createElement('tbody');

                tbody.innerHTML = '';

                for (let i = 0; i < data.data.length; i++) {

                    let tr = document.createElement('tr');
                    let td1 = document.createElement('td');
                    let td2 = document.createElement('td');
                    let td3 = document.createElement('td');

                    td1.innerHTML = data.data[i].c_num;
                    td2.innerHTML = data.data[i].c_name;
                    td3.innerHTML = data.data[i].c_class + '고사실';

                    tr.append(td1, td2, td3);

                    tbody.appendChild(tr);
                }

                modal_table.append(thead, tbody);

                modal_footer.innerHTML = `<p class="isolation_bottom">격리실의 응시자를 수정하려면 해당 고사실의 목록을 클릭하여 수정할 수 있습니다.</p>`;
                
                loading.style.display = 'none';
                body.style.overflow = 'hidden';
                modal.classList.add('show');

            }
            if (data.code == 201) {
                Swal.fire({
                    html: '<h3>입실 내역이 없습니다.</h3>',
                    icon: 'info',
                    confirmButtonText: '확인'
                })
            }
        })
    });
});

function listClick(tr) {
    tr.addEventListener('click', (e) => {

        loading.style.display = 'block';

        let c_num = e.currentTarget.children[0].innerHTML;

        let confirm_arr = [];
        let c_confirm = e.currentTarget.dataset.confirm;

        switch (c_confirm) {
            case '1':
                confirm_arr = ['2', '0'];
                confirm_text = ['2격리실', '정상제출'];
                break;
            case '2':
                confirm_arr = ['1', '0'];
                confirm_text = ['1격리실', '정상제출'];
                break;
            case '0':
                confirm_arr = ['1', '2'];
                confirm_text = ['1격리실', '2격리실'];
                break;
            default:
                confirm_arr = [];
                break;
        }

        if(confirm_arr.length == 2){

            Swal.fire({
                html: `
                <h5 class="fw-bold">수험번호 : ${e.currentTarget.children[0].innerHTML}</h5>
                <h5 class="fw-bold">이름 : ${e.currentTarget.children[1].innerHTML}</h5>
                <h5>변경하실 장소를 선택해주세요.</h5>
                <br>
                <div class="form-group">
                    <div>
                        <div class="toggle">
                            <input type="radio" name="confirm" id="confirm1" value="${confirm_arr[0]}" checked>
                            <label for="confirm1">${confirm_text[0]}</label>
                        </div>
                        <br>
                        <br>
                        <div class="toggle">
                            <input type="radio" name="confirm" id="confirm2" value="${confirm_arr[1]}">
                            <label for="confirm2">${confirm_text[1]}</label>
                        </div>
                    </div>
                </div>`,
                icon: 'question',
                confirmButtonText: '수정',
                showCancelButton: true,
                cancelButtonText: '취소'
            }).then((result) => {
                loading.style.display = 'none';

                if (result.isConfirmed) {

                    let c_confirm = document.querySelector('input[name="confirm"]:checked').value;

                    if(c_confirm == '0' || c_confirm == '1' || c_confirm == '2'){

                        fetch(BASE_URL + 'management/updateIsolation', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                'rs_id': RS_ID,
                                'p_id': P_ID,
                                'c_num': c_num,
                                'c_confirm' : c_confirm
                            })
                        }).then((response) => {
                            response.json().then((data) => {
                                if (data.code == 200) {
                                    Swal.fire({
                                        html: '<h3>수정이 완료되었습니다.</h3>',
                                        icon: 'success',
                                        confirmButtonText: '확인'
                                    }).then(() => {
                                        location.reload();
                                    });
                                }
                                if (data.code == 201) {
                                    Swal.fire({
                                        html: '<h3>수정을 하지 않았습니다. 다시 시도해주세요.</h3>',
                                        icon: 'error',
                                        confirmButtonText: '확인'
                                    });
                                }
                            })
                        })
                    }else{
                        Swal.fire({
                            html: '<h3>선택된 항목이 없습니다. 선택을 하신 후 수정을 해주세요.</h3>',
                            icon: 'error',
                            confirmButtonText: '확인'
                        });
                    }
                }
            })
        }else {
            Swal.fire({
                html: '<h3>문진표를 제출하지 않아 수정할 수 없습니다.</h3>',
                icon: 'info',
                confirmButtonText: '확인'
            }).then(() => {
                loading.style.display = 'none';
                return false;
            });
        }
    });
}