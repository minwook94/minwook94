<?
$login_disabled = '';
$login_btn_elem = '<button id="loginBtn">로그인</button>';
$login_code_elem = '제공받은 6자리 코드를 입력해주세요.';

if(!$login_acc){
    $login_disabled = 'disabled';
    $login_btn_elem = '<div style="color:red; text-align:center;">로그인할 수 있는 시간이 아닙니다.<br>안내받으신 시간이 되면 새로고침(F5)하십시오.</div>';
    $login_code_elem = '코드 입력이 불가능합니다.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title><?=$management['company_name'];?> <?=$management['p_name'];?> 총감/고객사 및 담당자 확인 페이지</title>
    <link href="<?=$this->config->config['base_url']?>static/css/management_login.css?v=<?=date('YmdHis')?>" rel="stylesheet" type="text/css">
</head>

<script>
    const RS_ID = <?=$rs_id;?>;
    const P_ID = <?=$p_id;?>;
    const BASE_URL = '<?=$this->config->config['base_url']?>';
    const LOGIN_ACC = '<?=$login_acc?>';
</script>
<body> 

<div class="wrap">
        <div class="login">
            <h3><?=$management['company_name'];?></h3>
            <br>
            <h4><?=$management['p_name'];?></h4>
            <h6>총감/고객사 및 담당자 확인 페이지</h6>
            <div class="middle"></div>
            <p><?=$login_code_elem?></p>
            <div class="d-flex justify-content-center code">
                    <?for($i=0; $i<6; $i++){?>
                        <input class="code_input" type="text" maxlength="1" oninput="maxLengthCheck(this)" onkeyup="inputKeyEvent()" <?=$login_disabled?>>
                    <?}?>
                </div>
            <div class="middle"></div>
            <div class="submit">
                <?=$login_btn_elem?>
            </div>
        </div>
    </div>

</body>

<?if($login_acc != ''){?>
  
<script>

    let code_input = document.getElementsByClassName('code_input');

    function maxLengthCheck(object){
        if (object.value.length > object.maxLength){
            object.value = object.value.slice(0, object.maxLength);
        }

        if(object.value.length == object.maxLength){
            if(object.nextElementSibling){
                object.nextElementSibling.focus();
            }
        }
    }
    function inputKeyEvent(){
        if(event.keyCode == 8){
            if(event.target.value.length == 0){
                if(event.target.previousElementSibling){
                    event.target.previousElementSibling.focus();
                }
            }
        }
        if(event.keyCode == 13){
            loginBtn.click();
        }
    }

    document.getElementById('loginBtn').addEventListener('click', ()=>{

        if(LOGIN_ACC == ''){
            Swal.fire({
                title: '로그인 가능 시간이 아닙니다.',
                icon: 'error',
                confirmbuttonText: "확인",
            })

            return false;
        }

        let code = '';
        for(let i=0; i<code_input.length; i++){
            code += code_input[i].value;
        }

        let check_number = /^[A-Za-z0-9+]{6}$/;

        if(!check_number.test(code)){
            Swal.fire({
                title: '잘못된 코드입니다.',
                text: '제공받으신 숫자, 영문 대/소문자 6자리 코드를 입력해주세요.',
                icon: 'error',
                confirmButtonText: '확인'
            })
        }else{
            fetch(BASE_URL+'/management/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    rs_id: RS_ID,
                    p_id: P_ID,
                    code: code
                })
            }).then(function(response){
                response.json().then(function(data){
                    console.log(data.code);

                    if(data.code == 200){
                        Swal.fire({
                            title: '로그인 성공',
                            text: '로그인이 완료되었습니다.',
                            icon: 'success',
                            confirmButtonText: '확인',
                        }).then(function(){
                            location.href = BASE_URL+'management/main';
                        })
                    }else if(data.code == 201){
                        Swal.fire({
                            title: '잘못된 코드입니다.',
                            text: '제공받으신 숫자, 영문 대/소문자 6자리 코드를 입력해주세요.',
                            icon: 'error',
                            confirmButtonText: '확인',
                        })
                    }
                })
            })
                
        }
    });

</script>
  
<?}?>

</html>