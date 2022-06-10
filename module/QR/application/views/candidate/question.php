
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="HandheldFriendly" content="true" />
    <link rel="shortcut icon" href="#">
    <title>QR 및 서명 테스트</title>
</head>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- <link href="<?=$this->config->config['base_url']?>static/css/main.css" rel="stylesheet" type="text/css"> -->
<link href="<?=$this->config->config['base_url']?>static/css/question.css?v=<?=date('YmdHis')?>" rel="stylesheet" type="text/css">

<script>
    const BASE_URL = <?=$this->config->config['base_url']?>;
    const RS_ID = <?=$rs_id?>;
</script>
<body>

    <div style="margin: 0 auto;">

        <div class="modal fade" id="signModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="signModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="signModalLabel">서명</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="canvasDiv">
                        <div class="wrapper">
                            <canvas id="signature-pad" class="signature-pad" width="250" height="100"></canvas>
                        </div>
                        <div id="sign"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button id="save" class="btn signBtn">서명</button>
                    <button id="clear" class="btn signBtn">지우기</button>
                </div>
                </div>
            </div>
        </div>

        <input type="radio" name="agree" id="agree2" class="d-none" checked>
        <div class="survay_box" id="survay2">
            <header>
                <span>개인정보 수집 · 이용 동의서</span>
                <button class="logoutBtn">로그아웃</button>
            </header>
            <main>
            <ul class="list_box">
                <?$j=1;
                foreach($agrees as $k=>$v){?>

                    <?if($v->a_agree_type == 'N'){?>
                    <li data-toptext="<?=$j?>."><?=$v->a_title?> <span> <?=$v->a_content?></span></li>
                    <?}else{?>
                        <li class="answer">
                            <p><?=$v->a_title?></p>
                            <label for="<?=$j?>"><p><?=$v->a_content?> <input type="checkbox" id="<?=$j?>" class="agree"></p></label>
                        </li>
                    <?}?>
                <?$j++;}?>
            </ul>
            <h5 class="text-right">작성일 : <span id="today"></span></h5>
            <div id="infoDiv" class="d-flex justify-content-end">
                <span>이름 : <?=$this->session->userdata['name'];?> </span>
                <div class="subpic">
                    <img id="signImg">
                    <span class="imtext" type="button" data-bs-toggle="modal" data-bs-target="#signModal">(서명)</span>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <button id="beforeBtn"><label for="agree1">이전</label></button>
                <button id="addBtn" class="btn">작성</button>
            </div>
            </main>
        </div>

    <!-- 앞 페이지 -->
    <input type="radio" name="agree" id="agree1" class="d-none" checked>

    <div class="survay_box" id="survay1">
        <header>
            <span>코로나19 검사 전자 문진표 작성안내</span>
            <button class="logoutBtn">로그아웃</button>
        </header>
        <main>
            <h5 class="title">
                문진 내용에 따라 출입 통제될 수 있음을 양지하여 주시기 바랍니다. 또한, 문진 내용에 거짓으로 응답하여 발생하는 모든 문제는 작성자 본인에게 책임이 있으며 관계 법령에 따라 조치합니다.        
            </h5>
            <ul class="list_box">
                <?$i=1;
                foreach($questions as $k=>$v){?>
                    <li data-toptext="<?=$i?>."><?=$v->q_content?>
                    <div>
                        <select name="q<?=$i?>" class="answer input_data">
                            <option value="">선택</option>
                            <option value="0">아니오</option>
                            <option value="1">예</option>
                        </select>
                    </div>
                </li>
                <?$i++;}?>
            </ul>

            <label for="agree2" style="width:100%;">
                <div id="nextBtn">다음</div>
            </label>

            </main>
        </div>

<script src="<?=$this->config->config['base_url']?>static/js/candidate_main.js"></script>
</html>