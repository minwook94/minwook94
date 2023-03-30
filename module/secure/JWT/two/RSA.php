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

    $private_key = '-----BEGIN ENCRYPTED PRIVATE KEY-----
MIIFHDBOBgkqhkiG9w0BBQ0wQTApBgkqhkiG9w0BBQwwHAQIcGm24Xh+N3ECAggA
MAwGCCqGSIb3DQIJBQAwFAYIKoZIhvcNAwcECGELFWbRNOwTBIIEyItzshkCgmOt
dWAiU0coQArAVH0yqWdc3UNH+U10qxifIJAQ9ipxgtx7corzoSIM2zHGO5jwA/M6
zTtTZSbBVXa54VOi1l6tCRJpBrZ9fOkmYWVEZY9P4Ki3pWe02r55G3PRiEr+CmCP
cxYFVzyynhDsQ/Dz4b5aG7tf3y3qWSLVjIrMVdcw1KjJTesPrD9Bey/9e83XLQnr
rC9LwICrg+lQRW5qk4rxjfiM9DVIp2gSVyfGEpnryTKKGtaD2EXT1QvUkwsJCGIo
6K0tTobM4Eno8KlOsIH06EwBhmbzO+auo6zPdAbOu+Ywu8EgtDl2t2B01JvxPirY
xG07JLsA+hFuBgbC1q37pphuBlEIoLQk/AhJCFOY3/yL6An7GUTgkq0NZpIqJU46
3Qc6uzMyEIvgbUg0d5TvZSJDlroHmRFhNOBAbdo9jsPt+v0JloBsz2lGEofnbnTU
voLZQa5oPqCnuzp99W6qtgBWflHabU+iF5ZNTCLUlylILGv9q7eaVA5KabOjL7t8
lI19o5TQMiYoqf1eHeHaeheqSZ4UjFZEN30VAOxtFnAa9+i1Uj9bvrurhRCfwEvs
XaiswQ8avFZXgrTdLX5PWZ5fQQw6zaS/ZDSmoPxPI7qTHW3fLzMTKQ7qMja1tKUT
bUxnXUsc1PaCmob9me7pY6pgrTtaVw70mwxycws92taHXNJfQIMruktFWRI54OYV
U01tMRXvNMaLIE4LemzoCdGU5kdwg3hIh6HnGq+5Zrr9ROQjLTfeHy2444H9SZa+
jMrB563+osg1dTa4CrrcneHzcT9HcEDrQcaZDq5d7hO1BevfMcdfXJ97wNJX7JJ5
jaW6qABitPbIaL1kItFWWP6cjJqfJPQI/ejKZVFXRMWZZ3eMOI7VcqJ8TwP7Jj4c
ce2qEC+GyFE9ZS8PS1fDzsn9wMBsSKeosBPiOeLa0j+TVXYpt6ouPqQ7/ApUwRfa
/5AejrsOA9yKiwlDejA3M23WpeWTNFrZuCmsuWna992j7kOBdU6ul1PAfE9cx+dh
xuw882GRe5EbsLzoC157cMZpfFVR3HFA5t/jaRiKfxJMZijURmuOHz2yFPcEafKJ
O4td0yp3fTzL1PLlW42fEYd75hHKXGvlPNM8R4cwoxUV610defpSkKVDPFxYiCBR
VCEIf3ZrihktcG/r1M5Ov5djE7Pv1vCFvUfCkegBDdfEShlQ42wFg94bsMsT2ykv
F7rRRAM6MASt91xpHzsr448DFXT+44ri3lsUfk7/JZEQnbaPXciVUj8DYYsJkiby
1ya3EBW4zdM2ty+KkyevsGR1InBXc2+oCfTzX5/BHZYShXWrQs0I2sApstBjWUb9
HEaGegD+cCfCKJHMCXLPcHP6UdN0sf0i7HuwNPTvwoF+id8jhtIDukeDfESgAXby
xdotopxXWJ+C5Zxxcu8vpSLp9pTFKVNm7SXvKxn8EAOsnqYUMZR0ngFhjJghi35l
z1vNygiyHw1rFK0XgHAdXiZHIq5ZDfOh9hiZAY6s9JOAQPjLjNAggZjVOtq+vUfs
txPHAtPbkLrGBK6kdWwzO0337hz4ReOb9GtFCYgqCFjEPXUVDiUumS0F+7od+BNZ
1fA3Cvuti7/jflkqhaYvOw==
-----END ENCRYPTED PRIVATE KEY-----';

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