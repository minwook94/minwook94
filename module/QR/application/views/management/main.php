<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="<?=$this->config->config['base_url']?>static/css/management_main.css?v=<?=date('YmdHis')?>" rel="stylesheet" type="text/css">
    <title>총감/고객사 및 담당자 확인 페이지</title>

    <script>
        const RS_ID = <?=$rs_id;?>;
        const P_ID = <?=$p_id;?>;
        const BASE_URL = "<?=$this->config->config['base_url']?>";
    </script>
</head>
<body>
    <header>
        <div class="header d-flex justify-content-between">
            <h2><?=$p_name?></h2>
            <svg id="logout" xmlns="http://www.w3.org/2000/svg" fill="white" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
                <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
            </svg>
        </div>
    </header>

    <main>
        <div class="main_div">
            <h5 style="text-align:center;">전체 입실 현황</h5>
            <h6 id="total"></h6>
        </div>
        <button id="refreshBtn" class="btn btn-success">새로고침</button>

        <div id="cardDiv" class="container">
            <?for($i=0;$i<count($c_class); $i++){?>
                
                <?if($i%2 == 0){?>
                    <div class="d-flex justify-content-center card_div">
                <?}?>
                    <div class="card main_card" data-num="<?=$c_class[$i];?>">
                        <p><?=$c_class[$i]?>고사실</p>
                        <p class="count_text" data-num="<?=$c_class[$i];?>"></p>
                    </div>
                <?if($i%2 == 1){?>
                    </div>
                <?}?>
            <?}?>
        </div>
    </main>
    <footer>
        <div class="d-flex justify-content-center">
            <div id="first_isolation" class="card isolation_card">
                <p>1 격리실</p>
                <p id="first_isolation_text"></p>
            </div>
            <div id="second_isolation" class="card isolation_card">
                <p>2 격리실</p>
                <p id="second_isolation_text"></p>
            </div>
        </div>
        </div>
    </footer>

    <div class="modal">
        <div class="modal_body">
            <div class="d-flex justify-content-between">
                <div id="modalTitle"></div>
                <button class="modal_close">X</button>
            </div>
            <div id="modalSubTitle"></div>
            <br>
            <table id="modalTable"></table>

            <div id="modalFooter"></div>
        </div>
    </div>

    <div id="loading">
        <img src="<?=$this->config->config['base_url']?>static/img/loading.gif" class="loading-image" alt="">
        <p style="margin: 0 30px;">정보를 불러오고 있습니다. 잠시만 기다려주세요.</p>
    </div>
    </main>
</body>

<script src="<?=$this->config->config['base_url']?>static/js/manage_main.js?v=<?=date('YmdHis')?>"></script>
</html>