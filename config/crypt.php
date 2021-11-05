<?php

return [
    'encode_ext' => env('ENCODE_EXT', 'pcit'),
    'encrypt_key' => env('ENCODE_ENC_KEY'),
    'decrypt_key' => env('ENCODE_DEC_KEY'),
    'passphrase' => env('ENCODE_PASSPHRASE'),
];