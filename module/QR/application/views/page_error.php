<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>잘못된 URL</title>
</head>
<body>
    
</body>

<script>
    window.addEventListener('load', ()=>{
        swal({
            title: '잘못된 URL입니다.',
            text: '제공받은 URL이 아닙니다. 제대로 된 URL로 다시 접속해주세요.',
            icon: 'error',
            button: '확인',
        });
    });
</script>
</html>