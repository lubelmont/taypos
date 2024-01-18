<?php

namespace App\Helpers;

class Crypto {
    private $key;

    public function __construct() {
        $this->key = env('APP_CRYPTO_KEY');
    }

    public function encrypt($message) {
        return openssl_encrypt($message, 'AES-256-CBC', $this->key, 0, substr($this->key, 0, 16));
    }

    public function decrypt($encryptedMessage) {
        return openssl_decrypt($encryptedMessage, 'AES-256-CBC', $this->key, 0, substr($this->key, 0, 16));
    }
}