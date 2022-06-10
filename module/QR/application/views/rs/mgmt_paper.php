<?
// echo '<pre>';
// print_r($data);
// echo '</pre>';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?=$head_html?>
    <script src="<?=$this->config->base_url()?>static/js/rs_paper.js?v=<?=date('YmdHis')?>"></script>
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
        a.active{
            font-weight: bold;
            font-style: italic;
        }
        .alert:HOVER{
            cursor: pointer;
            font-weight: bold;
        }
        .paper-content{
            font-size:10pt;
        }
        .item-active{
            font-weight: bold;
            border-color:red;
        }
        .item-table{
            font-size :10pt;
        }
    </style>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>
    <div class="container mt-5" id="mainBox">
        <br>
        <div class="row">
            <div class="col">
                <h5 class="m-2 fw-bold">KIRBS 문진표 관리 시스템 / <small>문진표,동의서 관리</small></h5>
            </div>
            <div class="col text-end">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                </svg> -->
                <?=$this->session->userdata('admin_name')?>님으로 관리중..
            </div>
        </div>
        <hr>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-index="questions" aria-current="page" href="#">문진표 (<?=count($data['questions'])?>)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-index="agree" href="#">동의서 (<?=count($data['agree'])?>)</a>
            </li>
        </ul>
        
        <div>
            <div id="questionsBox">
                <div class="text-end">
                    <button type="button" class="btn btn-primary btn-sm m-2" data-item="q" onclick="openAddModal(this);">문진표를 추가하겠습니다.</button>
                </div>
                <div class="row">
                    <div class="col-sm-2 border-end">
                        <?foreach($data['questions'] as $qk => $qv){?>
                        <div class="alert alert-primary text-center paper-content" data-div="qr_question_items" data-index="<?=$qv->q_id?>" role="alert">
                            <?=$qv->q_name?>    
                        </div>
                        <?}?>
                    </div>
                    <div class="col" id="questionsItemBox"></div>
                </div>
            </div>
            <div class="d-none" id="agreeBox">
                <div class="text-end">
                    <button type="button" class="btn btn-info btn-sm m-2" data-item="a" onclick="openAddModal(this);">동의서를 추가하겠습니다.</button>
                </div>
                <div class="row">
                    <div class="col-sm-2 border-end">
                        <?foreach($data['agree'] as $ak => $av){?>
                        <div class="alert alert-info text-center paper-content" data-div="qr_agree_items" data-index="<?=$av->a_id?>" role="alert">
                            <?=$av->a_name?>    
                        </div>
                        <?}?>
                    </div>
                    <div class="col" id="agreeItemBox"></div>
                </div>
            </div>
        </div>
        <br>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title fw-bold"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label fw-bold">Title.</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm item-title" />
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="inputPassword" class="col-sm-2 col-form-label fw-bold">Desc.</label>
                        <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm item-desc" />
                        </div>
                    </div>
                    <hr>
                    <table class="table table-bordered item-table">
                        <thead></thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
                    <button type="button" class="btn btn-light btn-sm" onclick="addList(this);">항목추가</button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="addItem(this);">추가하기</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>