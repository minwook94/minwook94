<?
$all_hours = array_fill(0,24,0);
$all_minutes = array_fill(0,60,0);
// echo '<pre>';
// print_r($items['place']);
// echo '</pre>';
// echo str_replace('-', '', $data['rs_data']->test_date);
$disable = null;
// echo (int)str_replace('-', '', $data['rs_data']->test_date);
// echo "<br>";
// echo date('Ymd');
if((int)str_replace('-', '', $data['rs_data']->test_date) <= date('Ymd')){
    $disable = 'disabled';
}
// echo "<br>";
// echo $disable;
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?=$head_html?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <script src="<?=$this->config->base_url()?>static/js/rs_detail.js?v=<?=date('YmdHis')?>"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
        
    </script>
    <style>
        body{
            background-color: #56baed;
        }
        #mainBox{
            box-shadow: 0 30px 60px 0 rgb(0 0 0 / 30%);
            background-color: #fff;
            border-radius: 10px;
        }
        .time-select{
            width:45%;
        }
        .move-badge{
            cursor: pointer;
        }
        .nav-link{
            cursor: pointer;
        }
        #confirmerTable{
            font-size:10pt;
        }
        #searchPlaceBox , #addPlaceBox , #placeBox{
            font-size:9pt;
            max-height:300px;
            overflow-y:auto;
        }
        #searchPlaceBox > div:HOVER{
            cursor: pointer;
            background-color: #f5f5f5;
        }
        .active-place:HOVER{
            cursor:pointer;
            border-color:red;
        }
        #placeBox input{
            text-align:center;
        }
        .card{
            font-size:10pt;
        }
        .card:HOVER{
            cursor:pointer;
            background-color:#0d6efd;
            color:#fff;
        }
        .fs-10{
            font-size:10pt !important;
        }
    </style>
    <script>
        const RS_ID = <?=$rs_id?>;
    </script>

</head>
<body>
    <div class="container mt-5" id="mainBox">

        <div>
        
        <br>
        <div class="row">
            <div class="col">
                <h5 class="m-2 fw-bold">KIRBS 문진표 관리 시스템 / <small>예약관리</small></h5>
            </div>
            <div class="col text-end">
                <?=$this->session->userdata('admin_name')?>님으로 관리중..
            </div>
        </div>
        <hr>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" data-index="qr" aria-current="page">QR 확인</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-index="info">예약정보관리</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-index="tester">장소별 응시자 관리</a>
            </li>
        </ul>
        <div class="text-center detail-frame" data-index="qr">
            <br>
            <div class="d-flex justify-content-between">
                <div class="text-start">
                    <p>
                        총관리자 URL : <a href="https://<?=$this->input->server('HTTP_HOST').$this->config->base_url().'total/'.$rs_id?>">https://<?=$this->input->server('HTTP_HOST').$this->config->base_url().'total/'.$rs_id?></a>
                        <br>
                        확인관 URL : <a href="https://<?=$this->input->server('HTTP_HOST').$this->config->base_url().'checker/'.$rs_id?>">https://<?=$this->input->server('HTTP_HOST').$this->config->base_url().'checker/'.$rs_id?></a>
                        <br>
                        응시자 문진표 작성 URL : <a id="qrURL" href="https://<?=$this->input->server('HTTP_HOST').$this->config->base_url().'candidate/'.$rs_id?>">https://<?=$this->input->server('HTTP_HOST').$this->config->base_url().'candidate/'.$rs_id?></a>
                    </p>
                </div>
                <div>
                    <?
                    $total_user_mgmt_btn_sub_text = '<small>(계정정보를 설정하지 않으면 총관리 시스템을 사용할 수 없습니다)</small>';
                    $total_user_id = '';
                    $pw_pass = false;
                    $btn_class = 'danger';
                    if(!empty($data['rs_data']->total_user_id)){
                        $total_user_mgmt_btn_sub_text = '';
                        $total_user_id = $data['rs_data']->total_user_id;
                        $pw_pass = true;
                        $btn_class = 'primary';
                    }
                    ?>
                    <button type="button" class="btn btn-<?=$btn_class?> btn-sm" data-bs-toggle="modal" data-bs-target="#totalUserModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wrench" viewBox="0 0 16 16">
                            <path d="M.102 2.223A3.004 3.004 0 0 0 3.78 5.897l6.341 6.252A3.003 3.003 0 0 0 13 16a3 3 0 1 0-.851-5.878L5.897 3.781A3.004 3.004 0 0 0 2.223.1l2.141 2.142L4 4l-1.757.364L.102 2.223zm13.37 9.019.528.026.287.445.445.287.026.529L15 13l-.242.471-.026.529-.445.287-.287.445-.529.026L13 15l-.471-.242-.529-.026-.287-.445-.445-.287-.026-.529L11 13l.242-.471.026-.529.445-.287.287-.445.529-.026L13 11l.471.242z"/>
                            </svg>
                            총관리자 계정관리 <?=$total_user_mgmt_btn_sub_text?>
                    </button>

                    <!-- total user info management Modal -->
                    <div class="modal fade" id="totalUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title fw-bold" id="exampleModalLabel">총관리자 계정관리</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-1 text-start">
                                    <small for="exampleFormControlInput1" class="form-label">ID 입력</small>
                                    <input type="text" id="totalUserId" class="form-control form-control-sm" placeholder="총관리자의 ID를 입력하세요." value="<?=$total_user_id?>">
                                </div>
                                <div class="text-start">
                                    <small for="exampleFormControlInput1" class="form-label">PW 입력</small>
                                    <input type="password" id="totalUserPw" class="form-control form-control-sm"  placeholder="총관리자의 비밀번호를 입력하세요.">
                                </div>
                                <?if($pw_pass){?>
                                    <hr>
                                    <div class="text-start">
                                        <small for="exampleFormControlInput1" class="form-label">기존 PW</small>
                                        <input type="password" id="totalUserOldPw" class="form-control form-control-sm"  placeholder="총관리자의 비밀번호를 입력하세요.">
                                    </div>
                                <?}?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary btn-sm" data-old_pw_pass="<?=$pw_pass?>" onclick="totalUserSetting(this);">설정하기</button>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <canvas id="qr"></canvas>
            <br>
            <div class="alert alert-light fw-bold" role="alert">
                QR코드 이미지 파일을 다운받아 각 검사장소에 비치하세요.
                <br>
                응시자가 QR코드를 스캔하여 전자문진표를 작성할 수 있도록 도움을 주시기 바랍니다.
            </div>
            <div class="d-flex justify-content-between">
                <div>
                    <button type="button" onclick="location.href = '<?=$this->config->base_url().'rs/main#1'?>';" class="btn btn-light btn-sm" data-img_name='<?=$data['rs_data']->company_name?> ( <?=$data['rs_data']->test_date?> ) QR.png'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list-ol" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z"/>
                        <path d="M1.713 11.865v-.474H2c.217 0 .363-.137.363-.317 0-.185-.158-.31-.361-.31-.223 0-.367.152-.373.31h-.59c.016-.467.373-.787.986-.787.588-.002.954.291.957.703a.595.595 0 0 1-.492.594v.033a.615.615 0 0 1 .569.631c.003.533-.502.8-1.051.8-.656 0-1-.37-1.008-.794h.582c.008.178.186.306.422.309.254 0 .424-.145.422-.35-.002-.195-.155-.348-.414-.348h-.3zm-.004-4.699h-.604v-.035c0-.408.295-.844.958-.844.583 0 .96.326.96.756 0 .389-.257.617-.476.848l-.537.572v.03h1.054V9H1.143v-.395l.957-.99c.138-.142.293-.304.293-.508 0-.18-.147-.32-.342-.32a.33.33 0 0 0-.342.338v.041zM2.564 5h-.635V2.924h-.031l-.598.42v-.567l.629-.443h.635V5z"/>
                        </svg>
                        목록으로 가기
                    </button>
                </div>
                    <button id="qrDownBtn" type="button" class="btn btn-primary btn-sm" data-img_name='<?=$data['rs_data']->company_name?> ( <?=$data['rs_data']->test_date?> ) QR.png'>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                        </svg>
                        &nbsp;<?=$data['rs_data']->company_name?> ( <?=$data['rs_data']->test_date?> ) QR.png Download
                    </button>
                </div>
            </div>
            <!-- <div class="text-end mt-1">
            </div> -->
        </div>
        <div class="d-none detail-frame" data-index="info">
        <form onsubmit="updateReservation(this); return false;">
            <div class="row mt-2">
                <div class="col border-end">
                    <div class="mb-2 d-flex justify-content-between">
                        <div class="w-25">
                            <label class="form-label fw-bold necessary">회사명</label>
                        </div>
                        <div class="w-75">
                            <input class="form-control form-control-sm" name="company_name" value="<?=$data['rs_data']->company_name?>" placeholder="ex) 한국행동과학연구소" required <?=$disable?>>
                        </div>
                    </div>
                    <!-- <div class="mb-3">
                        <label class="form-label fw-bold necessary">회사명</label>
                        <input class="form-control form-control-sm" name="company_name" value="<?=$data['rs_data']->company_name?>" placeholder="ex) 한국행동과학연구소" required <?=$disable?>>
                    </div> -->
                    <div class="mb-2 d-flex justify-content-between">
                        <div class="w-25">
                            <label class="form-label fw-bold necessary">날짜선택</label>
                        </div>
                        <div class="w-75">
                            <input type="date" class="form-control form-control-sm" name="test_date" value="<?=$data['rs_data']->test_date?>" required <?=$disable?>>
                        </div>
                    </div>
                    <!-- <div class="mb-3">
                        <label class="form-label fw-bold necessary">날짜선택</label>
                        <input type="date" class="form-control form-control-sm" name="test_date" value="<?=$data['rs_data']->test_date?>" required <?=$disable?>>
                    </div> -->
                    <div class="mb-2">
                        <label class="form-label fw-bold necessary">로그인 시작시간 설정</label>
                        <div class="d-flex justify-content-between">
                            <select name="s_time" class="form-select form-select-sm time-select" required <?=$disable?>>
                                <?foreach($all_hours as $k => $v){
                                    $value = sprintf('%02d',$k);
                                    $selected = '';
                                    if($value == substr($data['rs_data']->login_s_time,0,2)){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?=$value?>" <?=$selected?>><?=$value?></option>
                                <?}?>
                            </select>
                            :
                            <select name="s_minute" class="form-select form-select-sm time-select" required <?=$disable?>>
                                <?foreach($all_minutes as $k => $v){
                                    $value = sprintf('%02d',$k);
                                    $selected = '';
                                    if($value == substr($data['rs_data']->login_s_time,2,2)){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?=$value?>" <?=$selected?>><?=$value?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold necessary">로그인 종료시간 설정</label>
                        <div class="d-flex justify-content-between">
                            <select name="e_time" class="form-select form-select-sm time-select" required <?=$disable?>>
                                <?foreach($all_hours as $k => $v){
                                    $value = sprintf('%02d',$k);
                                    $selected = '';
                                    if($value == substr($data['rs_data']->login_e_time,0,2)){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?=$value?>" <?=$selected?>><?=$value?></option>
                                <?}?>
                            </select>
                            :
                            <select name="e_minute" class="form-select form-select-sm time-select" required <?=$disable?>>
                                <?foreach($all_minutes as $k => $v){
                                    $value = sprintf('%02d',$k);
                                    $selected = '';
                                    if($value == substr($data['rs_data']->login_e_time,2,2)){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?=$value?>" <?=$selected?>><?=$value?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-2 d-flex justify-content-between">
                        <div class="w-100">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <label class="form-label fw-bold necessary">문진표 선택</label>
                                </div>
                                <div>
                                    <?if(!$disable){?>
                                    <span class="badge bg-primary move-badge" data-uri="mgmt/paper">원하는 문진표가 없으십니까?</span>
                                    <?}?>
                                </div>
                            </div>
                            <select name="q_id" class="form-select form-select-sm" required <?=$disable?>>
                                <?foreach($items['questions'] as $k => $v){
                                    $selected = '';
                                    if($v->q_id == $data['rs_data']->q_id){
                                        $selected = 'selected';
                                    }?>
                                    <option value="<?=$v->q_id?>" <?=$selected?>><?=$v->q_name?></option>
                                <?}?>
                            </select>
                        </div>
                        &nbsp;
                        <div class="w-100">
                            <div class="d-flex  justify-content-between">
                                <div>
                                    <label class="form-label fw-bold necessary">동의서 선택</label>
                                </div>
                                <div>
                                    <?if(!$disable){?>
                                    <span class="badge bg-primary move-badge" data-uri="mgmt/paper">원하는 동의서가 없으십니까?</span>
                                    <?}?>
                                </div>
                            </div>
                            <select name="a_id" class="form-select form-select-sm" required <?=$disable?>>
                                <?foreach($items['agree'] as $k => $v){
                                    $selected = '';
                                    if($v->a_id == $data['rs_data']->a_id){
                                        $selected = 'selected';
                                    }?>
                                    <option value="<?=$v->a_id?>" <?=$selected?>><?=$v->a_name?></option>
                                <?}?>
                            </select>
                        </div>
                    </div>
                    <?if(!$disable){?>
                    <div class="mb-2">
                        <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#placeSearchModal">장소추가</button>
                    </div>
                    <?}?>
                    <div class="mb-2" id="placeBox">
                        <table id="placeTable" class="table table-striped fs-10">
                            <thead>
                                <tr>
                                    <th>장소번호</th>
                                    <th>장소명</th>
                                    <th>참여코드</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                foreach($data['place_group'] as $k => $v){?>
                                    <tr>
                                        <td><?=$v->p_id?></td>
                                        <td><?=$v->p_name?></td>
                                        <td><?=$this->seed_for_lib->kirbs_decrypt($v->login_code_seed)?></td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <label class="form-label fw-bold necessary">응시자 수정</label>
                            <small class="form-label fw-bold">총 <?=count($data['candidate'])?>명</small>
                        </div>
                        <?
                        $candidate_place_group = array();
                        $candidate = array();
                        foreach($data['candidate'] as $k => $v){
                            // $candidate_place_group[$v->p_id]['all'] = 0;
                            // $candidate_place_group[$v->p_id]['c'] = 0;
                            // if(!isset($candidate_place_group[$v->p_id]['all'])){ $candidate_place_group[$v->p_id]['all'] = 0; echo "test"; }
                            // if(!isset($candidate_place_group[$v->p_id]['c'])){ $candidate_place_group[$v->p_id]['c'] = 0; }
                            // $candidate[] = $v->c_name.'&#9;'.$v->c_num.'&#9;'.$v->p_name.'&#9;'.$v->c_class;
                            $candidate[] = $v->c_name.'&#9;'.$v->c_num.'&#9;'.$v->p_id.'&#9;'.$v->c_class.'&#9;'.$v->c_part;

                            @$candidate_place_group[$v->p_id]['all']++;
                            // echo $v->c_confirm;
                            if(isset($v->c_confirm)){
                                @$candidate_place_group[$v->p_id]['c']++;
                            }
                            @$candidate_place_group[$v->p_id]['class'][$v->c_class][] = $v;
                        }
                        ?>
                        <textarea name="candidate" class="form-control form-control-sm" rows="7" placeholder="이름    수험번호   장소" required <?=$disable?>><?=join('&#10;',$candidate)?></textarea>
                    </div>
                    <span class="badge bg-danger">수험번호는 중복될 수 없습니다.</span>
                    <span class="badge bg-danger">시스템에 등록된 장소로만 입력이 가능합니다. 장소를 정확하게 입력해주십시오.</span>
                    <br><br>
                    <div class="mb-3">
                        <!-- <label class="form-label fw-bold necessary">확인관</label> -->
                        <div class="d-flex justify-content-between">
                            <label class="form-label fw-bold">확인관</label>
                            <small class="form-label fw-bold">총 <?=count($data['confirmer'])?>명</small>
                        </div>
                        <?
                        // $confirmer = array();
                        // foreach($data['confirmer'] as $k => $v){
                        //     $confirmer[] = $v->cf_name.'&#9;'.$v->p_name;
                        // }
                        ?>
                        <!-- <textarea name="confirmer" class="form-control form-control-sm" rows="7" <?=$disable?>><?=join('&#10;',$confirmer)?></textarea> -->
                        <table id="confirmerTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>이름</th>
                                    <th>장소번호</th>
                                    <th>PW</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?
                                // echo '<pre>';
                                // print_r($data['confirmer']);
                                // echo '</pre>';
                                foreach($data['confirmer'] as $k => $v){?>
                                    <tr>
                                        <td><?=$v->cf_name?></td>
                                        <td><?=$v->p_id?></td>
                                        <td><?=$this->seed_for_lib->kirbs_decrypt($v->cf_pw_seed)?></td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                        
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <span class="badge bg-danger">기존 확인관의 정보를 수정하려면 전산센터에 문의하십시오.</span>
                        </div>
                        <div>
                            <!-- <span class="badge bg-danger">시스템에 등록된 장소로만 입력이 가능합니다. 장소를 정확하게 입력해주십시오.</span> -->
                            <?if(!$disable){?>
                            <!-- <span class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#addConfirmerModal" style="cursor:pointer;">확인관 추가</span> -->
                            <?}?>
                        </div>
                    </div>
                        <div class="modal fade" id="addConfirmerModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title fw-bold" id="exampleModalLabel" >확인관 추가</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <textarea id="confirmer" class="form-control form-control-sm" rows="7" placeholder="이름    비밀번호   장소"></textarea>
                                </div>
                                <span class="badge bg-danger m-2">한 장소에 같은 이름을 가진 확인관은 추가할 수 없습니다.</span>
                                <!-- <div class="modal-footer">
                                    <button type="button" class="btn btn-primary btn-sm">확인관을 추가하겠습니다.</button>
                                </div> -->
                                </div>
                            </div>
                        </div>
                    <!-- </div> -->
                </div>
            </div>
            <br>
            <br>
            <?if(!$disable){?>
                <div>
                    <button type="submit" class="btn btn-primary w-100">수정하겠습니다</button>
                </div>
            <?}else{?>
                <div class="text-center">
                    <i>검사 당일부터는 수정이 불가합니다.</i>
                </div>    
            <?}?>
                
        </div>
        <div class="d-none detail-frame p-1" data-index="tester">
            <div class="row mt-2">
                <?
                // $candidate_place_group = array();
                // foreach($data['candidate'] as $k => $v){
                //     @$candidate_place_group[$v->p_id]['all']++;
                //     echo $v->c_confirm;
                //     if(isset($v->c_confirm)){
                    //         @$candidate_place_group[$v->p_id]['c']++;
                    //     }
                    // }
                // echo '<pre>';
                // print_r($candidate_place_group);
                // echo '</pre>';
                $p_names = array();
                foreach($data['place_group'] as $k => $v){?>
                    <div class="col-lg-3">
                        <div class="card mt-2" data-bs-toggle="offcanvas" href="#pgBox<?=$v->p_id?>">
                        <!-- <div class="card mt-2" data-bs-toggle="offcanvas" href="#offcanvasExample"> -->
                            <div class="card-body" data-p_id="<?=$v->p_id?>">
                            <?
                            $confirm_count = 0;
                            if(!empty($candidate_place_group[$v->p_id]['c'])){
                                $confirm_count = $candidate_place_group[$v->p_id]['c'];
                            }
                            ?>
                            <?=$v->p_name?> (<?=$confirm_count.'/'.$candidate_place_group[$v->p_id]['all']?>)
                            </div>
                        </div>
                    </div>
                <?
                $p_names[$v->p_id] = $v->p_name;
                }
                // echo '<pre>';
                // print_r($candidate_place_group);
                // echo '</pre>';
                ?>
                </div>

                <?foreach($candidate_place_group as $k => $v){?>
                <div class="offcanvas offcanvas-end w-50" tabindex="-1" id="pgBox<?=$k?>" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel"><?=$p_names[$k]?></h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="overflow-auto" style="height:80vh;">
                            <div class="accordion">
                                <?foreach($v['class'] as $class_k => $class_v){?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#<?='box_'.$k.'_'.$class_k?>" aria-expanded="true" aria-controls="collapseOne">
                                            <b><?=$class_k?></b> <small>고사실</small>
                                        </button>
                                        </h2>
                                        <div id="<?='box_'.$k.'_'.$class_k?>" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <table class="table table-bordered fs-10">
                                                <thead>
                                                    <tr>
                                                        <th>이름</th>
                                                        <th>수험번호</th>
                                                        <th class="text-center" width="10%">상태</th>
                                                        <th width="20%"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?
                                                    $confirm_cnt = 0; //입실자 count
                                                    $iso_cnt = 0; //격리자 count

                                                    foreach($class_v as $can_k => $can_v){?>
                                                        <tr>
                                                            <td><?=$can_v->c_name?></td>
                                                            <td><?=$can_v->c_num?></td>
                                                            <td class="text-center">
                                                            <?
                                                            $stat = '미응시';
                                                            $text_class = 'warning';
                                                            $view_btn = false;
                                                            if(isset($can_v->c_confirm)){ 
                                                                $view_btn = true;
                                                                $stat = '입실완료';
                                                                $confirm_cnt++;
                                                                $text_class = 'primary';
                                                                if($can_v->c_confirm > 0){
                                                                    $stat = '격리실('.$can_v->c_confirm.')' ;
                                                                    // $confirm_cnt++;
                                                                    $text_class = 'danger';
                                                                    
                                                                }
                                                                // switch($can_v->c_confirm){
                                                                //     case 1 :
                                                                //         $stat = '입실완료';
                                                                //         $confirm_cnt++;
                                                                //         $text_class = 'primary';
                                                                //     break;
                                                                //     case 0 :
                                                                //         $stat = '격리실';
                                                                //         $iso_cnt++;
                                                                //         $text_class = 'danger';
                                                                //     break;
                                                                // }
                                                            }
                                                            echo '<b class="text-'.$text_class.'">'.$stat.'</bc>';
                                                            ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?if($view_btn){?>
                                                                    <button type="button" class="btn btn-link btn-sm p-0 fs-10 paper-view-btn" data-c_id="<?=$can_v->c_id?>" data-rs_id="<?=$rs_id?>">문진표/동의서보기</button>
                                                                <?}?>
                                                            </td>
                                                        </tr>
                                                    <?}?>
                                                </tbody>
                                            </table>
                                            <div class="fs-10 text-end">
                                                전체 : <?=count($class_v).'명 / 입실완료 : '.$confirm_cnt.' / 격리실 : '.$iso_cnt.'명 / 미응시 : '.(count($class_v) - ($confirm_cnt+$iso_cnt)).'명'?>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-primary btn-sm" onclick="window.open(BASE_URL+'rs/viewPaper?rs_id=<?=$rs_id?>&p_id=<?=$k?>');">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>    
                                <?=$p_names[$k]?> 문진표/동의서 다운로드</button>
                        </div>
                    </div>
                </div>
                <?}?>

            <?
            // $place_group = array();
            // foreach($data['candidate'] as $k => $v){
            //     $place_group[$v->p_id][] = $v;
            // }
            // echo '<pre>';
            // print_r($data['place_group']);
            // print_r($data['candidate']);
            // echo '</pre>'; 
            ?>
        </div>
        <br>
        </form>
    </div>



    <!-- 장소선택 modal -->
    <div class="modal fade" id="placeSearchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold" id="exampleModalLabel">장소선택</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col border-end">
                        <input type="text" id="placeSearchInput" class="form-control form-control-sm" placeholder="장소 입력 후 Enter.">
                        <div id="searchPlaceBox" class="p-2"></div>
                    </div>
                    <div class="col">
                        <small class="fw-bold">- 내가 추가한 장소</small>
                        <div class="p-2" id="addPlaceBox"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="d-flex justify-content-between w-100">
                    <div>
                        <span class="badge bg-danger">장소를 추가한 후 참여코드를 꼭 입력해주세요. 고사본부에서 확인할 때 사용됩니다.</span>
                    </div>
                    <div>
                        <button type="button" class="btn btn-primary btn-sm" id="activePlaceBtn">장소추가완료</button>
                    </div>
                </div>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
            </div>
        </div>
    </div>
</body>
</html>