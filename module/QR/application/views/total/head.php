<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?
$html_title = '총괄관리 시스템';
if(!empty($company_name)){
    $html_title = $company_name.' 총괄관리 시스템';
}
?>
<title><?=$html_title?></title>
<script src="https://kit.fontawesome.com/51db22a717.js" crossorigin="anonymous"></script>
<link href="https://fonts.googleapis.com/css2?family=Alata&display=swap" rel="stylesheet">
<link href="<?=$this->config->config['base_url']?>static/css/total_login.css?v=<?=date('YmdHis')?>" rel="stylesheet" type="text/css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    const RS_ID = <?=$rs_id?>;
    const BASE_URL = '<?=$this->config->config['base_url']?>';
</script>
<script src="<?=$this->config->config['base_url']?>static/js/public.js"></script>
<style>
    #loadingBox{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #fff;
        z-index: 9999;
        display: flex;
        justify-content: center;
        align-items: center;
        opacity:0.7;
    }
</style>
<div id="loadingBox" class="d-none">
    <div class="spinner-border text-primary" role="status">
    <span class="visually-hidden">Loading...</span>
    </div>
    <div>&nbsp;&nbsp;데이터를 불러오는 중입니다..</div>
</div>