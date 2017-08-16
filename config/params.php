<?php

return [
    'adminEmail' => 'daxionginfo@gmail.com',
    'timeout' => @date('dHi') + 10,
    'api_url' => 'http://damoney.co.nf/api.php',
    'refer_id' => substr(md5(mt_rand()), 0, 7) . @date('Ymdhis'),
];
