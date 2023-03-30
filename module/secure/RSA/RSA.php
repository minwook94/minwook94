<?php
// 비대칭 알고리즘(단방향)
require_once './Rsa_lib.php';

$rea = new Rsa_lib();

// 개인키, 공개키 생성
$key = $rea->rsa_generate_keys('test');
// 암호화
$encrypted = $rea->rsa_encrypt('Hello, World!', $key['public_key']);
// 복호화
$decrypted = $rea->rsa_decrypt($encrypted, $key['private_key'], 'test');

echo $decrypted;