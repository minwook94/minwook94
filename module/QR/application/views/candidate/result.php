<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <link href="<?=$this->config->config['base_url']?>static/css/result.css?v=<?=date('YmdHis')?>" rel="stylesheet" type="text/css">

    <title>문진표 결과 QR</title>

    <script>
        const BASE_URL = <?=$this->config->config['base_url']?>;
        <?
        $qr_text = json_encode(array(
            'answer_id' => $answer_id,
            'c_id' => $c_id,
            'rs_id' => $rs_id,
        ));
        ?>
    </script>

</head>
<body>

    <header>
        <span>QR 결과</span>
        <button id="logoutBtn">로그아웃</button>
    </header>
    <main>
        
        <h3>발급된 QR코드를 확인자에게 보여주세요.</h3>
        <br>

        <p><?=$c_name;?>님의 정보입니다.</p>

        <div id="qrDiv">
            <input type="hidden" id="qrlink" value=<?=$qr_text?>>
            <br>
            <canvas id="qrcode"></canvas>
        </div>

        <div class="container">
            <p>시험 장소 : <b><?=$place['p_name'];?></b></p>
            <p>주소 : <b><?=$place['addr'];?></b></p>
        </div>
    </main>

    <footer>
        <p><?=$company_name;?></p>
    </footer>
</body>

<script>
    window.addEventListener('load', function() {

        var qrcode = new QRious({
            element: document.getElementById('qrcode'),
            value: qrlink.value,
            size: 200,
            level: 'H',
            background: '#FFFFFF',
            backgroundAlpha: 0.5,
        });
    });

    document.getElementById('logoutBtn').addEventListener('click', function(){
        location.href = BASE_URL+'candidate/'+<?=$rs_id?>;
    });
</script>
</html>