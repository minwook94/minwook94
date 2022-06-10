<html>
    <head>
        <meta charset="utf-8">
        <title><?=$company_name;?> QR 체크 페이지</title>
        <script src="<?=$this->config->config['base_url']?>static/js/jsQR.js"></script>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="HandheldFriendly" content="true" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrious/4.0.2/qrious.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link href="<?=$this->config->config['base_url']?>static/css/check.css?v=<?=date('YmdHis')?>" rel="stylesheet" type="text/css">
        <script>
            const RS_ID = '<?=$rs_id?>';
            const BASE_URL = '<?=$this->config->config['base_url']?>';
        </script>

        </head>
    <body>

        <header>
            <span><?=$company_name;?> 문진표 확인</span>

        </header>
        <main>
            <div>
                <div id="test">
                    <div id="output">
                        <div id="outputMessage">
                            응시자의 문진표를 확인하기 위해 응시자가 제시한 QR코드를 카메라에 노출시켜 주세요.
                        </div>
                        <div id="outputLayer" hidden>
                            <span id="outputData"></span>
                        </div>
                    </div>
                </div>

                <div>
                    <div id="frame">
                        <div id="loadingMessage">
                            🎥 로딩 중... 계속 진행되지 않는다면 카메라나 웹캠이 설치되어있는지 확인해주세요.
                        </div>
                        <div id="confirmText" class="alert alert-primary">문진표 검사가 완료되었습니다.</div>
                        <canvas id="canvas"></canvas>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <div id="logout">
                로그아웃
            </div>
        </footer>

        <div id="qrDiv">
            <input type="hidden" id="qrlink">
            <br>
            <br>
            <canvas id="qrcode"></canvas>
        </div>
        <br>
        <audio id="confirmSound" src="<?=$this->config->config['base_url']?>static/sound/confirm.wav"></audio>
        <audio id="errorSound" src="<?=$this->config->config['base_url']?>static/sound/error.wav"></audio>
    </body>

    <script src="<?=$this->config->base_url()?>static/js/check.js?v=<?=date('YmdHis')?>"></script>

</html>
