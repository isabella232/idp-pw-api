<?php

use common\helpers\MySqlDateTime;

return [
    'method2' => [
        'id' => 2,
        'uid' => '22222222222222222222222222222222',
        'user_id' => 1,
        'value' => 'email-1456769679@domain.org',
        'verified' => 1,
        'verification_code' => null,
        'verification_attempts' => null,
        'verification_expires' => null,
        'created' => '2016-02-29 13:15:00',
    ],
    'method3' => [
        'id' => 3,
        'uid' => '33333333333333333333333333333333',
        'user_id' => 1,
        'value' => 'email-1456769721@domain.org',
        'verified' => 0,
        'verification_code' => 123456,
        'verification_attempts' => null,
        'verification_expires' => '2000-01-01 00:00:00',
        'created' => '2000-01-01 00:00:00',
    ],
    'method4' => [
        'id' => 4,
        'uid' => '44444444444444444444444444444444',
        'user_id' => 1,
        'value' => 'email-1456769722@domain.org',
        'verified' => 0,
        'verification_code' => 444444,
        'verification_attempts' => null,
        'verification_expires' => MySqlDateTime::relativeTime('+50 hours'),
        'created' => '2016-02-29 13:14:00',
    ],
    'method5' => [
        'id' => 5,
        'uid' => '33333333333333333333333333333335',
        'user_id' => 1,
        'value' => 'email-145676972@domain.org',
        'verified' => 0,
        'verification_code' => 123456789,
        'verification_attempts' => null,
        'verification_expires' => MySqlDateTime::relativeTime('+50 hours'),
        'created' => '2000-01-01 00:00:00',
    ],
];
