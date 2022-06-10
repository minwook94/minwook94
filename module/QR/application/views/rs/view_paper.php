<?
$q_title = $question_title->q_name;
$a_title = $agree_title->a_name;
// echo '<pre>';
// print_r($question_itmes);
// print_r($agree_itmes);
// print_r($answer_data);

// echo $q_title;
// echo "<br>"; 
// echo $a_title;
// echo '</pre>';
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<style>
        .answer-box{
            width:990px;
            height:1440px;
            margin: auto;
            padding:15px;
        }
        /* .answer-box{
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.5cm 1.5cm 2cm 1.5cm;
        } */
        .answer-box .title {
            font-size:22pt;
        }
        /* .text-center{
            text-align:center;
        } */
        .agree-sub-desc b{
            font-size:17px;
        }
        .test-date-p{
            font-size:20px;
        }
        .candidate-info-table{
            font-size:18px;
        }
</style>
<body>
    <div id="mainDiv">
        <?
        $q_answer_pool = array('아니오','예');
        foreach($answer_data as $k => $v){?>
            <div class="answer-box">
                <div class="text-center m-3">
                    <b class="title"><?=$q_title?></b>
                </div>
                <div>
                    <p>&nbsp;&nbsp;&nbsp;최근 <b>코로나19</b> 확산에 따라 <b>(사)<?=$v['company_name']?></b>에서는 아래와 같이 방문하는 모든 수검자를 대상으로 문진을 시행하며 문진 내용에 따라 출입 통제될 수 있을을 양지하여 주시기 바랍니다. 또한, <u>문진 내용에 거짓으로 응답하여 발생하는 모든 문제는 작성자 본인에게 책임이 있으며 관계 법령에 따라 조치</u>합니다.</p>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">항목</th>
                            <th class="text-center" width="15%">응답</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?
                        $candidate_answer = $v['answer']->question;
                        foreach($question_itmes as $q_item_k => $q_item_v){?>
                            <tr>
                                <td class="p-3"><?=$q_item_v->q_content?></td>
                                <?
                                $candidate_answer[($q_item_v->q_order-1)] ? $q_td_class = 'text-danger' : $q_td_class = 'text-primary';
                                ?>
                                <td class="text-center align-middle fw-bold <?=$q_td_class?>"><?=$q_answer_pool[$candidate_answer[($q_item_v->q_order-1)]]?></td>
                            </tr>
                        <?}?>
                    </tbody>
                </table>

                <hr class="m-5">

                <div class="text-center m-3">
                    <b class="title"><?=$a_title?></b>
                </div>
                <table class="table table-bordered">
                    <tbody>
                        <?
                        foreach($agree_itmes as $a_item_k => $a_item_v){?>
                            <tr>
                                <?
                                $user_agree_elem = "";
                                $td_border = "";
                                if($a_item_v->a_agree_type == 'Y'){
                                    $user_agree_elem = "<br><div class='text-end text-primary fw-bold'>동의함</div>";
                                    $td_border = "border border-dark";
                                }
                                ?>
                                <td class="p-3 <?=$td_border?>"><?=$a_item_v->a_title?></td>
                                <?
                                ?>
                                <td class="<?=$td_border?>">
                                    <?=$a_item_v->a_content?>
                                    <?=$user_agree_elem?>
                                </td>
                            </tr>
                        <?}?>
                    </tbody>
                </table>
                <div>
                    <p class="agree-sub-desc"><small>* <b>민감정보</b>는 개인정보보호법 제 23조에 규정된 <b>건강정보</b>를 의미합니다</small></p>
                </div>
                <div class="text-center m-5">
                    <?
                    $test_date = explode('-',$v['test_date']);
                    ?>
                    <p class="test-date-p"><?=$test_date[0].'년 '.$test_date[1].'월 '.$test_date[2].'일'?></p>
                </div>
                <!-- <div class="row"> -->
                <div class="d-flex justify-content-between align-items-end">
                    <!-- <div class="col"></div> -->
                    <!-- <div class="col"> -->
                        <div>
                            <h5 class="fw-bold mt-1"><?=$v['company_name']?> 사장 <small>귀하</small></h5>
                        </div>
                        <table class="fw-bold candidate-info-table">
                            <tbody>
                                <tr><td>지원회사 : </td><td colspan="2"><?=$v['company_name']?></td></tr>
                                <tr><td>수험번호 : </td><td colspan="2"><?=$v['c_num']?></td></tr>
                                <tr><td>성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명 : </td><td colspan="2"><?=$v['c_name']?></td>
                                        <!-- <td class="fw-normal">
                                        </td> -->
                                </tr>
                                <tr>
                                    <td class="fw-normal text-end" colspan="3">
                                        <div class="position-relative">
                                            (서명)
                                            <img class="position-absolute" src="<?=$v['sign_img']?>" style="top:-15px; right:-30px; width:150px;">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <!-- </div> -->
                </div>

            </div>
        <?}?>
    </div>
</body>
<!-- <button id = "create_pdf">
  pdf 생성
</button> -->
<script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type = "text/javascript" src = "https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script type = "text/javascript" src = "https://code.jquery.com/jquery-latest.min.js"></script>


<script>
$('#create_pdf').click(function() {
	//pdf_wrap을 canvas객체로 변환
    html2canvas(document.body).then(function(canvas) {
                // 캔버스를 이미지로 변환
                var imgData = canvas.toDataURL('image/png');

                var imgWidth = 210; // 이미지 가로 길이(mm) A4 기준
                var pageHeight = imgWidth * 1.414;  // 출력 페이지 세로 길이 계산 A4 기준
                var imgHeight = canvas.height * imgWidth / canvas.width;
                var heightLeft = imgHeight;

                var doc = new jsPDF('p', 'mm');
                var position = 0;

                // 첫 페이지 출력
                doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;

                // 한 페이지 이상일 경우 루프 돌면서 출력
                while (heightLeft >= 20) {
                    position = heightLeft - imgHeight;
                    doc.addPage();
                    doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;
                }

                // 파일 저장
                doc.save('sample.pdf');
  });
});
// $('#create_pdf').click(function() {
// 	//pdf_wrap을 canvas객체로 변환
//     html2canvas(document.body.then(function(canvas) {
// 	// var doc = new jsPDF('p', 'mm', 'a4'); //jspdf객체 생성
// 	var doc = new jsPDF('p', 'mm'); //jspdf객체 생성
//     var position = 0;
//     var imgData = canvas.toDataURL('image/png'); //캔버스를 이미지로 변환


//     var imgWidth = 210; // 이미지 가로 길이(mm) A4 기준
//     var pageHeight = imgWidth * 1.414;  // 출력 페이지 세로 길이 계산 A4 기준
//     var imgHeight = canvas.height * imgWidth / canvas.width;
//     var heightLeft = imgHeight;

//     doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight); //이미지를 기반으로 pdf생성
//     doc.save('sample-file.pdf'); //pdf저장


//   });
// });

// function pdfPrint(){
// // alert('test');

// // 현재 document.body의 html을 A4 크기에 맞춰 PDF로 변환
// html2canvas(document.body, {
//     onrendered: function (canvas) {

//         // 캔버스를 이미지로 변환
//         var imgData = canvas.toDataURL('image/png');

//         var imgWidth = 210; // 이미지 가로 길이(mm) A4 기준
//         var pageHeight = imgWidth * 1.414;  // 출력 페이지 세로 길이 계산 A4 기준
//         var imgHeight = canvas.height * imgWidth / canvas.width;
//         var heightLeft = imgHeight;

//         var doc = new jsPDF('p', 'mm');
//         var position = 0;

//         // 첫 페이지 출력
//         doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
//         heightLeft -= pageHeight;

//         // 한 페이지 이상일 경우 루프 돌면서 출력
//         while (heightLeft >= 20) {
//             position = heightLeft - imgHeight;
//             doc.addPage();
//             doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
//             heightLeft -= pageHeight;
//         }

//         // 파일 저장
//         doc.save('sample.pdf');


//         //이미지로 표현
//         //document.write('<img src="'+imgData+'" />');
//     }
    
// });

// }
</script>