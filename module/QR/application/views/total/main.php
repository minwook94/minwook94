<?
// echo '<pre>';
// print_r($this->session->userdata());
// echo '</pre>';
?>
<html lang="ko">
<head>
    <?=$head_html?>
    <!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="<?=$this->config->config['base_url']?>static/js/total_main.js?v=<?=date('YmdHis')?>"></script>
    <style>
        .main_container{
            background: #eff0f2;
        }
        .stat-title{
            font-size:12pt;
        }
    </style>
</head>

<body>
    <div class="container main-container mt-5">
        <div class="d-flex justify-content-between">
            <div>
                <h5 class="fw-bold"><?=$data->company_name?> 오프라인 검사 전체 통계</h5>
            </div>
            <div>
                    검사일 : <?=$data->test_date?>
                    &nbsp;&nbsp;
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="rgb(13, 110, 253)" class="bi bi-box-arrow-left" viewBox="0 0 16 16" style="cursor:pointer;" onclick="location.href=BASE_URL+'total/'+RS_ID;">
                        <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0v2z"/>
                        <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3z"/>
                    </svg>
            </div>
        </div>
        <hr>
        <div>
            <span class="badge rounded-pill bg-light text-primary border border-primary stat-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                </svg>
                전체 응시현황
            </span>
            <div class="row mt-3" id="allStatBox">
                <div class="col-3"></div>
                <div class="col-4"></div>
                <div class="col-5" id="allStatPieBox"></div>
            </div>
        </div>
        <div>
            <span class="badge rounded-pill bg-light text-primary border border-primary stat-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                </svg>
                분야별 응시현황
            </span>
            <div class="w-100 mt-3" id="partStatBarChartBox"></div>
        </div>
        <div>
            <span class="badge rounded-pill bg-light text-primary border border-primary stat-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                    <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
                </svg>
                장소별 응시현황
            </span>
            <div class="w-100 mt-3" id="placeStatBarChartBox"></div>
            <div class="w-100 mt-3 d-flex" id="placeClassChartBox"></div>
        </div>
    </div>
</body>
</html>