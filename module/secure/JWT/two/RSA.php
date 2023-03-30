<?
    $header = json_encode([
        'alg' => 'RS256',
        'typ' => 'JWT',
    ]);

    $payload = json_encode([
        'name' => 'John Doe',
        'email' => '',
    ]);

    $public_key = '';

    $signature = hash_hmac('sha256', $header . '.' . $payload, $public_key);

    $jwt = base64_encode($header . '.' . $payload . '.' . $signature);

    $jwt_exploded = explode('.', base64_decode($jwt));

    $jwt_header = $jwt_exploded[0];
    $jwt_payload = $jwt_exploded[1];
    $jwt_signature = $jwt_exploded[2];

    // 2.2. Decode a header
    $jwt_header_decoded = $jwt_header;

    // 2.3. Decode a payload
    $jwt_payload_decoded = $jwt_payload;

    // 2.4. Decode a signature
    $jwt_signature_decoded = $jwt_signature;



    // 2.5. Verify a signature
    $signature_verified = hash_hmac('sha256', $jwt_header . '.' . $jwt_payload, $private_key) === $jwt_signature_decoded;

    print_r($signature_verified);

    exit;
    
    // 2.6. Decode a payload
    $jwt_payload_decoded = json_decode($jwt_payload_decoded, true);

    print_r($jwt_payload_decoded);

    // 2.7. Get a payload

?>