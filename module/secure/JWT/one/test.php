<?
require_once './Jwt_lib.php';
require_once './Rsa_lib.php';

$jwt = new Jwt_lib();
$rsa = new Rsa_lib();

$rsa_key = 'kirbs';
$rsa_data = $rsa->rsa_generate_keys($rsa_key);

$token = $jwt->hashing(array(
    'time' => time()+100,
    'public_key' => $rsa_data['public_key'],
));

print_r('공개 키 JWT : '. $token);
echo "<br><br>";
// 여기까지 상대방에서 요청이 진행되면 jwt에서 RS256 방식의 공개키를 넣어서 전송
// 비밀키는 DB 등을 통해서 보관
// ---------------------- jwt --------------------

$parted = explode('.', base64_decode($token));

print_r('행과연에서 전송받은 값 : ');
echo "<br>";
echo "<pre>";
print_r($parted);
echo "</pre>";

$payload = json_decode($parted[1], true);

$encrypt_data = $rsa->rsa_encrypt('Hello World!', $payload['public_key']);

$hashing_data = $jwt->hashing(array(
    'time' => $payload['time'],
    'data' => $encrypt_data,
));

print_r('오토에버에서 전송할 값 : '.$hashing_data);

// 여기까지 제공받은 공개키를 통해 조회할 데이터를 jwt로 묶어서 보내주는 작업 (현대오토에버 측 작업)
// -----------------------------------------------

$hashed_data = explode('.', base64_decode($hashing_data));

echo "<br><br>";
print_r('오토에버에서 전송받은 값 : ');
echo "<br>";
echo "<pre>";
print_r($hashed_data);
echo "</pre>";
echo "<br><br>";

$return_payload = json_decode($hashed_data[1], true);

$return_decrypted_data = $rsa->rsa_decrypt($return_payload['data'], $rsa_data['private_key'], $rsa_key);

if(empty($return_decrypted_data)){
    print_r('복호화가 정상적으로 이루어지지 않았습니다.');
}else{
    print_r($return_decrypted_data);
}
?>