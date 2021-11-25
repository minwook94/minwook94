<?php

session_start();

require "Authenticator.php";

$ga = new Authenticator();

if(!isset($_SESSION['auth_secret'])) {
    $secret = $ga->generateRandomSecret();
    $_SESSION['auth_secret'] = $secret;
}

$QR = $ga->getQR('테스트', $_SESSION['auth_secret']);
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Time-based Authenticator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body class="bg">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">시간 기반 구글 인증</h3>
                    </div>
                    <div class="card-body text-center">
                            <div class="form-group">
                                <?echo "<img src=".$QR.">";?>
                            </div>
                            <input type="text" class="form-control" name="code" placeholder="******" style="font-size: xx-large; width: 200px; border-radius: 0px; text-align:center; display: inline; color: #0275d8;">
                            <br><br>
                            <button id="checkBtn" type="button" class="btn btn-md btn-primary" style="wdith:200px; border-radius: 0px;">확인</button>
                    </div>
                </div>
                <input type="text" class="form-control" id="secret" name="secret" value="<?php echo $_SESSION['auth_secret']; ?>" readonly>
            </div>
        </div>

    </div>
    
</body>

<script>
    document.getElementById('checkBtn').addEventListener("click", ()=>{
        let code = document.getElementsByName('code')[0].value;

        let httpRequest = new XMLHttpRequest();

        httpRequest.onreadystatechange = function() {
            if(httpRequest.readyState === XMLHttpRequest.DONE) {
                if(httpRequest.status === 200) {

                    if(httpRequest.responseText === '200') {
                        alert('인증코드가 일치합니다.');
                    }else{
                        alert('인증코드가 일치하지 않습니다.');
                    }
                }else {
                    alert("실패");
                }
            }
        }

        httpRequest.open("POST", "./check.php", true);
        httpRequest.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        httpRequest.send("code="+code);

    });
</script>
</html>
