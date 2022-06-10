<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body,html{
            padding:0px;
            margin:0px;
        }
        *{
            word-break:keep-all;
            box-sizing:border-box;
        }
        header{   
            background:#000080;
            color:#fff;
            font-size:.9rem;
            width:100%;
            height:40px;
            display:flex;
            padding-left:10px;
            padding-right:10px;
            align-items:center;
            justify-content:space-between;
            font-weight:bold;
        }
        header > span, header > label{
            display:block;
        }
        main{
            min-height:100vh;
            padding:5px;
            
            font-size:1rem;
        }
        h5.title{
            font-size:.7rem;
        }
        ul.list_box{
            border:#eee solid 1px;
            border-radius:4px;
            padding:5px;
            font-size:.8rem;
            list-style-type:none;
        }
        ul.list_box > li{
            padding-left:14px;
            position:relative;
            font-weight:bold;
        }
        ul.list_box > li > span{
            font-weight:normal;
            display:block;
            margin-top:5px;
        }
        ul.list_box > li:nth-child(n+2){
            margin-top:20px;
            padding-top:10px;
            border-top:#eee solid 1px;
        }
        ul.list_box > li:before{
            content:attr(data-toptext);
            position:absolute;
            left:0px;
            margin-left:0px;
            font-weight:bold;
            
        }
        ul.list_box > li > div{
            text-align:right;
        }
        ul.list_box > li > div > select{
            border:none;
            border-bottom:#eee solid 2px;
            padding:0px 10px;
            margin-top:20px;
        }
        ul.list_box > li > div > select > option:nth-child(2){
            color:blue;
            font-weight:bold;
        }
        ul.list_box > li > div > select > option:nth-child(3){
            color:red;
            font-weight:bold;
        }
        div.agree{
            font-size:.8rem;
            
        }
        div.agree > p{
            text-align:right;
        }
        .d-none{
            display:none;
        }
        .survay_box{
            display:none;
        }
        input[name='agree']:checked + div{
            display:block;
        }
    </style>
</head>
<body>
    <input type="radio" name="agree" id="agree1" class="d-none" checked>
    <div class="survay_box" id="survay1">
        <header>
        <span>코로나19 검사 전자 문진표 작성안내</span>
        <label for="agree2"> > </label>
        </header>
        <main>
            <h5 class="title">
                문진 내용에 따라 출입 통제될 수 있음을 양지하여 주시기 바랍니다. 또한, 문진 내용에 거짓으로 응답하여 발생하는 모든 문제는 작성자 본인에게 책임이 있으며 관계 법령에 따라 조치합니다.        
            </h5>
            <ul class="list_box">
                <li data-toptext="1.">최근 2주 이내, 코로나 바이러스 감염 확진자 또는 밀접접촉자를 만난 적이 있다.
                    <div>
                        <select name="" id="">
                            <option value="">선택</option>
                            <option value="">아니오</option>
                            <option value="">예</option>
                        </select>
                    </div>
                </li>
                <li data-toptext="2.">최근 2주 이내, 감염 확진자가 발생한 지역(국내 및 해외 포함) 및 기타 행사에 방문한 적이 있다.
                    <div>
                        <select name="" id="">
                            <option value="">선택</option>
                            <option value="">아니오</option>
                            <option value="">예</option>
                        </select>
                    </div>
                </li>
                <li data-toptext="3.">질병관리본부로부터 자가격리 대상자라는 통보받은 경험이 있다.
                    <div>
                        <select name="" id="">
                            <option value="">선택</option>
                            <option value="">아니오</option>
                            <option value="">예</option>
                        </select>
                    </div>
                </li>
                <li data-toptext="4.">질병관리본부로부터 능동감시 대상자로 통보받은 경험이 있다.
                    <div>
                        <select name="" id="">
                            <option value="">선택</option>
                            <option value="">아니오</option>
                            <option value="">예</option>
                        </select>
                    </div>
                </li>
                <li data-toptext="5.">최근 2주 이내, 발열(37.5℃ 이상) 또는 기침, 인후통, 근육통(몸살감기), 호흡관란과 같은 증상이 있었다.
                    <div>
                        <select name="" id="">
                            <option value="">선택</option>
                            <option value="">아니오</option>
                            <option value="">예</option>
                        </select>
                    </div>
                </li>
            </ul>
            </main>
        </div>
        <input type="radio" name="agree" id="agree2" class="d-none" checked>
        <div class="survay_box">
            <header>
            <span>개인정보 수집 · 이용 동의서</span>
            <label for="agree1"> < </label>
            </header>
            <main>
            <ul class="list_box">
                <li data-toptext="1.">수집·이용 목적<span>『코로나19』확산 방지</span></li>
                <li data-toptext="2.">수집·이용할 항목<span>민감정보(문진 내용의 건강 정보)</span></li>
                <li data-toptext="3.">보유·이용기간<span>위 개인정보는 동의일로부터 최대 30일간 보유 · 이용됩니다. (출입 통제 시 당일 파기, 그 외의 경우 30일간 보유 후 파기)</span></li>
                <li data-toptext="4.">동의를 거부할 권리 및 동의를 거부할 경우의 불이익<span>이용자는 언제든지 수집·이용에 대한 동의를 거부할 수 있으며, 이 경우 코로나19 확산 방지 서비스를 이용할 수 없습니다.</span></li>
            </ul>
            <div class="agree">
                <p>귀 기관이 위와 같이 본인의 개인정보를 수집 및 이용하는 것에 동의합니다.</p>
                <p>귀 기관이 위와 같이 민감정보를 수집 및 이용하는 것에 동의합니다.</p>
            </div>


            작성일 : 2022년 2월 9일

            이름 : 우민욱

            (서명)
            </main>
        </div>


</body>
</html>