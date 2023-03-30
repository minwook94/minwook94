<?
    class Aes_256_lib {

        public $system_key = 'test';

        public function aes_encrypt($key, $value) {
            $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, false, str_repeat(chr(0), 16));
            return $encrypted_data;
        }

        public function aes_decrypt($key, $value) {
            $decrypted_data =  openssl_decrypt($value, 'aes-256-cbc', $key, false, str_repeat(chr(0), 16));
            return $decrypted_data;
        }
    }
?>