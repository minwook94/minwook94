<?
$input_dis = '';
if(empty($total_user_id)){
    $input_dis = 'disabled';
}
?>
<html lang="ko">
<head>
    <?=$head_html?>
    <script src="<?=$this->config->config['base_url']?>static/js/total_login.js?v=<?=date('YmdHis')?>"></script>
</head>

<body>
    <div class="page-container">
        <div class="login-form-container shadow">
            <div class="login-form-right-side text-center">
                <div class="top-logo-wrap">
                </div>
                <h4><?=$company_name?><br><small>총관리자 시스템</small></h4>
                <p>모든 검사장소의 통계를 볼 수 있습니다.</p>
            </div>
            <div class="login-form-left-side">
                <div class="login-input-container">
                    <div class="login-input-wrap input-id">
                        <i class="far fa-id-badge"></i>
                        <input id="totalUserId" placeholder="ID" type="text" class="input_data" <?=$input_dis?>>
                    </div>
                    <div class="login-input-wrap input-password">
                        <i class="fas fa-key"></i>
                        <input id="totalUserPw" placeholder="Password" type="password" class="input_data" data-name="password" onkeyup="if(event.keyCode==13){ document.getElementById('loginBtn').click(); }" <?=$input_dis?>>
                    </div>
                </div>
                <div class="login-btn-wrap">
                    <?if(empty($total_user_id)){?>
                        <p id="inputMsg">총 관리자의 계정이 설정되지 않았습니다.<br>관리자에게 문의하십시오.</p>
                    <?}else{?>
                        <button id="loginBtn" class="login-btn">로그인</button>
                    <?}?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>