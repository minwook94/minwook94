<?
    // 대칭 알고리즘(양방향)
    require_once './Aes_256_lib.php';

    $key = '1';

    $value = 'woominwook';

    $aes = new Aes_256_lib();

    // 암호화
    $encrypted = $aes->aes_encrypt($key, $value);

    // 복호화
    $decrypted = $aes->aes_decrypt('test', $encrypted);
    print_r($decrypted);
    
    // $reponse_arr = array('code'=>600, 'msg'=>'POST 파라미터 부족.');
    
    // if(!empty($_POST['aes_key'])){
    //     $reponse_arr['code'] = 700;
    //     $reponse_arr['msg'] = '시스템 유일키가 맞지 않습니다.';
    //     $sys_pk_keys = explode('_',$_POST['aes_key'])[0];
        
    //     $aes = new Aes_256_lib();

    //     if($sys_pk_keys === $aes->system_key){
    //         $key_date = strtotime(explode('_',$_POST['aes_key'])[1]);
            
    //         $now = strtotime(date('YmdHis'));

    //         if(((int)$now - (int)$key_date) <= 100 && ((int)$now > (int)$key_date)){
    //             $reponse_arr['code'] = 200;
    //             $reponse_arr['msg'] = 'API 인증완료.';
    //         }else{
    //             $reponse_arr['code'] = 800;
    //             $reponse_arr['msg'] = 'API timeout.';
    //         }
    //     }
    // }
    // echo json_encode($reponse_arr,JSON_UNESCAPED_UNICODE);
?>