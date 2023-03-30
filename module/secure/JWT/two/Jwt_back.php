<?
include './Rsa_lib.php';
class Jwt_lib
{
    public $alg;
    public $private_key;
    public $public_key;

    function getKey()
    {

        $this->alg = 'RS256';

        $rsa = new Rsa_lib();

        $rsa = new Rsa_lib();
        $rsa_key = 'kirbs';

        $rsa_data = $rsa->rsa_generate_keys($rsa_key);

        $secret_key = $rsa_data['private_key'];
        $public_key = $rsa_data['public_key'];

        return $rsa_data;
    }

//    jwt 발급하기
    function hashing(array $data, $public)
    {
        $alg = 'RS256';
        $header = json_encode(array(
            'alg' => $alg,
            'typ' => 'JWT',
        ));

        $payload = json_encode($data);

        $base64UrlHeader = $this->base64UrlEncode($header);
        $base64UrlPayload = $this->base64UrlEncode($payload);

        $signature = $this->sign($base64UrlHeader . "." . $base64UrlPayload, $public);

        $base64UrlSignature = $this->base64UrlEncode($signature);

        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        $jwt_exploded = explode('.', $jwt);

        $jwt_header = $jwt_exploded[0];
        $jwt_payload = $jwt_exploded[1];
        $jwt_signature = $jwt_exploded[2];

        $jwt_header_decoded = base64_decode($jwt_header);
        $jwt_payload_decoded = base64_decode($jwt_payload);
        $jwt_signature_decoded = base64_decode($jwt_signature);

        print_r('헤더 : '.$jwt_header_decoded);
        echo "<br>";
        print_r('페이로드 : '.$jwt_payload_decoded);
        echo "<br>";
        print_r('서명 : '.$jwt_signature_decoded);



        // 페이로드 - 전달할 데이터
        // $payload = json_encode($data);


        // $jwt_data = base64_encode($header . '.' . $payload . '.' . $signature);

        // $rsa = new Rsa_lib();

        // $rsa_data = $rsa->rsa_encrypt('test');

        // $encrypted_signature = $rsa->rsa_encrypt('test', $rsa_data['public_key']);

        // return base64_encode($header . '.' . $payload . '.' . $signature);
    }

    function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    function sign($input, $public)
    {

        $private_key = openssl_pkey_get_private($public);

        openssl_sign($input, $signature, $private_key, OPENSSL_ALGO_SHA256);
        openssl_free_key($private_key);
        return $signature;
    }


//    jwt 해석하기
    function dehashing($token, $private_key)
    {
        $token = base64_decode($token);

        $token = explode('.', $token);

        $header = $token[0];
        $payload = $token[1];
        $signature = $token[2];

        print_r($signature);

        exit;

        $signature_check = hash_hmac('sha256', $header . '.' . $payload, $private_key, true);

        if ($signature_check == $signature) {
            echo '인증 성공';
        } else {
            echo '인증 실패';
        }

        return $payload;

    }
}
?>