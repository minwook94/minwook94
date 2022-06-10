<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>메인 화면으로</title>
    <style>
        body{
            background-color: #f5f5f5;
        }
    </style>
</head>
<body>

</body>

<script>
    window.addEventListener('load', ()=>{
        swal({
            title: "세션이 만료되었습니다.",
            text: "로그인 페이지로 이동합니다.",
            icon: "warning",
        })
        .then((willDelete) => {
            history.go(-1);
        });
    });
</script>
</html>