<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script type = "text/javascript" src = "https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
    <script type = "text/javascript" src = "https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        #Div{
            width: 21cm;
            min-height: 29.7cm;
            padding: 1.5cm 1.5cm 2cm 1.5cm;
            word-break:break-all;
        }
    </style>
</head>
<script>
    
    function pdfPrint(){
	//pdf_wrap을 canvas객체로 변환
            // html2canvas(document.body).then(function(canvas) {
            html2canvas(document.getElementById('Div') , {
                scale: 3
            }).then(function(canvas) {
                        // 캔버스를 이미지로 변환
                        var imgData = canvas.toDataURL('image/png');

                        var imgWidth = 210; // 이미지 가로 길이(mm) A4 기준
                        var pageHeight = imgWidth * 1.414;  // 출력 페이지 세로 길이 계산 A4 기준
                        var imgHeight = canvas.height * imgWidth / canvas.width;
                        var heightLeft = imgHeight;

                        var doc = new jsPDF('p', 'mm');
                        var position = 0;

                        // 첫 페이지 출력
                        doc.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
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

    }

    window.onload = function(){
        pdfPrint();
    }

</script>
<body>
    <div id="Div">
        <!-- <p>testtesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttesttest</p>     -->
        <div style="color:#fff; background-color:red;">결과표</div>
    </div>
</body>
<!-- <button type="button" onclick="pdfPrint();">create!</button> -->
</html>