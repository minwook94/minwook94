<?
//print "<pre>";
//print_r($place_data);
//print_r($class_data);
//print "</pre>";
//exit;
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>QR 현황 확인</title>
	<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+KR:wght@400;500;700&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/53a8c415f1.js" crossorigin="anonymous"></script>
	<!-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script src="<?=$this->config->base_url()?>static/js/public.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
	<script>
		window.addEventListener("load",function(){
			var time_max = 5;
			var max = time_max;
			var box = document.querySelector("#rs_info");
			box.dataset.toptext = max+"초";
			var time = setInterval(()=>{
				if(max==time_max){				
				var form = new FormData();
				form.append("rs_id",<?=$rs_data['rs_id']?>);
				fetch('/QRtest/info/call_num',{
					method:"POST",
					body:form
				}).then((resp)=>{
					if(!resp.ok){
						throw new Error("네트워크 오류");
					}
					return resp.json();
					}).then((res)=>{
					
						Object.keys(res.class_num).forEach((key)=>{
							var class_box = document.querySelector("#class_box"+key+" > div p > span");
							var con_box = document.querySelector("#class_box"+key+" > div");
							class_box.innerText = res.class_num[key];
							if(class_box.dataset.total==res.class_num[key]){
								if(!con_box.classList.contains("full-box")){
								con_box.classList.add("full-box");
								}
							}
						});
					}).catch((err)=>{
						alert(err);	
					});
				}
				max--;
				if(max==0){
					max=time_max;
				}
				box.dataset.toptext = max+"초";
			},1000);

		});
	</script>
	<style>
	[data-toptext],[data-toptextleft]{
		position:relative;
	}
	[data-toptext]:after{
		content:attr(data-toptext);
		position:absolute;
		top:-20px;
		font-weight:bold;
		right:0px;
	}
	[data-toptextleft]:before{
		content:attr(data-toptextleft);
		position:absolute;
		top:-20px;
		font-weight:bold;
		left:0px;
	}
	.class_box{
		border:#eee solid 1px;
		border-radius:8px;
		transition: box-shadow .3s;
	}
	.class_box > p{
		text-align:center;
	}
	.class_box.full-box{
		border:transparent solid 1px;
		box-shadow:10px 10px 10px rgba(0, 128, 192,.5);
	}
	</style>
</head>
<body>
	<div class="container mt-5">
		<h3><?=$rs_data['company_name']?></h3>
		<div id="rs_info" data-toptext="">
		<?
		foreach($place_data as $k=>$v){
		?>
		<div class="row my-5" data-toptextleft="<?=$v['p_name']?>">
		<?
		foreach($class_data[$v['p_id']] as $key=>$val){
			$num = 0;
			if(!empty($result_class[$val['c_class']])){
			$num = $result_class[$val['c_class']];
			}
		?>
			<div class="col-md  p-2" id="class_box<?=$val['place_code']?>">
				<div class="class_box p-2">
				<h4><?=$val['c_class']?><small>고시실</small></h4>
				<p><span data-total="<?=$val['num']?>"><?=$num?></span>/<?=$val['num']?></p>
				</div>
			</div>

		<?}?>
		</div>
		<?}?>
		</div>

	</div>
</body>
</html>
