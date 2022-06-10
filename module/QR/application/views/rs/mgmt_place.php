<!DOCTYPE html>
<html lang="ko">
<head>
    <?=$head_html?>
    <script src="<?=$this->config->base_url()?>static/js/rs_place.js?v=<?=date('YmdHis')?>"></script>
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
                <h5 class="m-2 fw-bold">KIRBS 문진표 관리 시스템 / <small>장소관리</small></h5>
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
            <button type="button" data-uri="add" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addPlaceModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                </svg>    
                장소추가
            </button>
            <div class="modal fade" id="addPlaceModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title fw-bold" id="exampleModalLabel">장소추가</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form onsubmit="addPlace(this); return false;">
                    <div class="modal-body">
                            <div class="mb-3 text-start">
                                <label class="form-label fw-bold necessary">장소명</label>
                                <input class="form-control form-control-sm" name="p_name" placeholder="ex) 서울고등학교" required>
                            </div>
                            <div class="mb-3 text-start">
                                <label class="form-label fw-bold necessary">주소</label>
                                <input class="form-control form-control-sm" name="addr" placeholder="ex) 서울시 강남구" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                            <button type="submit" class="btn btn-primary btn-sm">추가</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <table class="table table-striped table-hover text-center">
            <thead>
                <tr>
                    <th width="10">no.</th>
                    <th>장소이름</th>
                    <th>주소</th>
                    <!-- <th>로그인 종료시간</th> -->
                </tr>
            </thead>
            <tbody id="rsListTbody"></tbody>
        </table>
        <div class="row">
            <div class="col" id="totalCntBox"></div>
            <div class="col text-end">
            <nav aria-label="..." style="">
                <ul id="pageLinkUl" class="pagination pagination-sm justify-content-end"></ul>
            </nav>
            </div>
        </div>
        <br>
    </div>
</body>
</html>