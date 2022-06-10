<!DOCTYPE html>
<html lang="ko">
<head>
    <?=$head_html?>
    <script src="<?=$this->config->base_url()?>static/js/rs_main.js?v=<?=date('YmdHis')?>"></script>
    <style>
        body{
            background-color: #56baed;
        }
        #mainBox{
            box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
            background-color: #fff;
            border-radius: 10px;
        }
        #rsListTbody tr{
            cursor:pointer;
        }
    </style>

</head>
<body>
    <div class="container mt-5" id="mainBox">
        <br>
        <div class="row">
            <div class="col">
                <h5 class="m-2 fw-bold">KIRBS 문진표 관리 시스템</h5>
            </div>
            <div class="col text-end">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg> -->
                <?=$this->session->userdata('admin_name')?>님으로 관리중..
            </div>
        </div>
        <div id="mainBtnBox" class="text-end">
            <button type="button" data-uri="add" class="btn btn-danger btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                </svg>    
                신규예약
            </button>
            <button type="button" data-uri="mgmt/place" class="btn btn-info btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-building" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z"/>
                <path d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z"/>
                </svg>
                장소관리
            </button>
            <button type="button" data-uri="mgmt/paper" class="btn btn-info btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                <path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z"/>
                <path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z"/>
                </svg>
                문진표/동의서 관리
            </button>
        </div>
        <hr>
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th width="10">no.</th>
                    <th>회사명</th>
                    <th>검사일</th>
                    <th>로그인 시작시간</th>
                    <th>로그인 종료시간</th>
                </tr>
            </thead>
            <tbody id="rsListTbody"></tbody>
        </table>
        <div class="row">
            <div class="col" id="totalCntBox">

            </div>
            <div class="col text-end">
            <nav aria-label="..." style="">
                <ul id="pageLinkUl" class="pagination pagination-sm justify-content-end"></ul>
            </nav>
            </div>
        </div>
    </div>
</body>
</html>