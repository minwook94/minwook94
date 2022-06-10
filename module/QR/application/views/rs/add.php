<?
$all_hours = array_fill(0,24,0);
$all_minutes = array_fill(0,60,0);
// echo '<pre>';
// print_r($items);
// echo '</pre>';
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <?=$head_html?>
    <script src="<?=$this->config->base_url()?>static/js/rs_add.js?v=<?=date('YmdHis')?>"></script>
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
    </style>
</head>
<body>
    <!-- <details>
        <summary>이미지 추가 정보</summary>
        내용
    </details>

    <menu>
    <command type="command" label="Save" icon="save.png" onclick="save()"></command>Save
    </menu>

    <form>
    <input list="browsers" type="text">
    <datalist id="browsers">
        <option value="Internet Explorer">인터넷 익스플로러</option>
        <option value="Firefox">파이어폭스</option>
        <option value="Chrome">크롬</option>
        <option value="Opera">오페라</option>
        <option value="Safari">사파리</option>
    </datalist>
    </form>

    <figure>
    <img src="http://kangyoo80.tistory.com/admin/entry/post/logo.png" alt="biew">
    <figcaption>biew logo</figcaption>
    </figure> -->

    <div class="container mt-5" id="mainBox">
        <form onsubmit="addReservation(this); return false;">
        <br>
        <div class="row">
            <div class="col">
                <h5 class="m-2 fw-bold">KIRBS 문진표 관리 시스템 / <small>신규예약</small></h5>
            </div>
            <div class="col text-end">
                <?=$this->session->userdata('admin_name')?>님으로 관리중..
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col border-end">
                <div class="mb-2 d-flex justify-content-between">
                    <div class="w-25">
                        <label class="form-label fw-bold necessary">회사명</label>
                    </div>
                    <div class="w-75">
                        <input class="form-control form-control-sm" name="company_name" placeholder="ex) 한국행동과학연구소" required>
                    </div>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <div class="w-25">
                        <label class="form-label fw-bold necessary">날짜선택</label>
                    </div>
                    <div class="w-75">
                        <input type="date" class="form-control form-control-sm" name="test_date" required>
                    </div>
                </div>
                <!-- <div class="mb-2">
                    <label class="form-label fw-bold necessary">날짜선택</label>
                    <input type="date" class="form-control form-control-sm" name="test_date" required>
                </div> -->
                <div class="mb-2">
                    <label class="form-label fw-bold necessary">로그인 시작시간 설정</label>
                    <div class="d-flex justify-content-between">
                        <select name="s_time" class="form-select form-select-sm time-select" required>
                            <?foreach($all_hours as $k => $v){?>
                                <option value="<?=sprintf('%02d',$k)?>"><?=sprintf('%02d',$k)?></option>
                            <?}?>
                        </select>
                        :
                        <select name="s_minute" class="form-select form-select-sm time-select" required>
                            <?foreach($all_minutes as $k => $v){?>
                                <option value="<?=sprintf('%02d',$k)?>"><?=sprintf('%02d',$k)?></option>
                            <?}?>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label fw-bold necessary">로그인 종료시간 설정</label>
                    <div class="d-flex justify-content-between">
                        <select name="e_time" class="form-select form-select-sm time-select" required>
                            <?foreach($all_hours as $k => $v){?>
                                <option value="<?=sprintf('%02d',$k)?>"><?=sprintf('%02d',$k)?></option>
                            <?}?>
                        </select>
                        :
                        <select name="e_minute" class="form-select form-select-sm time-select" required>
                            <?foreach($all_minutes as $k => $v){?>
                                <option value="<?=sprintf('%02d',$k)?>"><?=sprintf('%02d',$k)?></option>
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
                                <span class="badge bg-primary move-badge" data-uri="mgmt/paper">원하는 문진표가 없으십니까?</span>
                            </div>
                        </div>
                        <select name="q_id" class="form-select form-select-sm" required>
                            <?foreach($items['questions'] as $k => $v){?>
                                <option value="<?=$v->q_id?>"><?=$v->q_name?></option>
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
                                <span class="badge bg-primary move-badge" data-uri="mgmt/paper">원하는 동의서가 없으십니까?</span>
                            </div>
                        </div>
                        <select name="a_id" class="form-select form-select-sm" required>
                            <?foreach($items['agree'] as $k => $v){?>
                                <option value="<?=$v->a_id?>"><?=$v->a_name?></option>
                            <?}?>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <button type="button" class="btn btn-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#placeSearchModal">장소선택</button>
                </div>
                <div class="mb-2" id="placeBox"></div>
                <!-- <div class="mb-2">
                </div> -->
            </div>
            <div class="col">
                <div class="mb-2">
                    <label class="form-label fw-bold necessary">응시자 입력</label>
                    <textarea name="candidate" class="form-control form-control-sm" rows="7" placeholder="이름    수험번호   장소번호   고사실   지원분야" required></textarea>
                </div>
                <span class="badge bg-danger">수험번호는 중복될 수 없습니다.</span>
                <span class="badge bg-danger">선택한 장소만 입력이 가능합니다. 장소번호를 정확하게 입력해주십시오.</span>
                <br><br>
                <div class="mb-2">
                    <label class="form-label fw-bold necessary">확인관 입력</label>
                    <textarea name="confirmer" class="form-control form-control-sm" rows="7" placeholder="이름    비밀번호    장소번호" required></textarea>
                </div>
                <span class="badge bg-danger">한 장소에 같은 이름을 가진 확인관은 추가할 수 없습니다.</span>
                <span class="badge bg-danger">선택한 장소만 입력이 가능합니다. 장소번호를 정확하게 입력해주십시오.</span>
            </div>
        </div>
        <br>
        <br>
        <div>
            <button type="submit" class="btn btn-primary w-100">예약하겠습니다</button>
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
                        <button type="button" class="btn btn-primary btn-sm" id="activePlaceBtn">장소선택완료</button>
                    </div>
                </div>
                <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button> -->
            </div>
            </div>
        </div>
    </div>
</body>
</html>