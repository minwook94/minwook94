<?
$login_disabled = '';
$login_script = '<script src="'.$this->config->config['base_url'].'/static/js/candidate_login.js"></script>';
$login_btn_elem = '<button id="loginBtn">문진표 작성</button>';
if(!$login_acc){
    $login_disabled = 'disabled';
    $login_script = '';
    $login_btn_elem = '<div style="color:red; text-align:center;" >로그인할 수 있는 시간이 아닙니다.<br>안내받으신 시간이 되면 새로고침(F5)하십시오.</div>';
}
?>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>전자 문진표 로그인</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/53a8c415f1.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="<?=$this->config->config['base_url']?>static/css/login.css?v=<?=date('YmdHis')?>" rel="stylesheet" type="text/css">
    <script>
        const RS_ID = '<?=$rs_id?>';
        const LOGIN_ACC = '<?=$login_acc?>';
        const BASE_URL = '<?=$this->config->config['base_url']?>';
    </script>

</head>
<body>
    <div class="wrap">
        <div class="login">
            <h2><?=$company_name?> </h2>
            <h2>전자 문진표</h2>
            <div class="login_id">
                <h4>이름</h4>
                <input type="text" name="" id="name" placeholder="이름을 입력해주세요." <?=$login_disabled?>>
            </div>
            <div class="login_pw">
                <h4>수험번호</h4>
                <input type="text" name="" id="c_num" placeholder="제공받으신 수험번호를 입력해주세요." <?=$login_disabled?>>
            </div>
            <div class="login_btn">
                <!-- <button id="loginBtn">문진표 작성</button> -->
                <?=$login_btn_elem?>
            </div>
        </div>
    </div>
</body>

<!-- <script src="<?=$this->config->config['base_url']?>static/js/candidate_login.js?v=<?=date('YmdHis')?>"></script> -->
<?=$login_script?>
</html>