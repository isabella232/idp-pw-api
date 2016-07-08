<?php
use common\helpers\Utils;
return [
    'user1' => [
        'id' => 1,
        'uid' => '11111111111111111111111111111111',
        'employee_id' => '111111',
        'first_name' => 'User',
        'last_name' => 'One',
        'idp_username' => 'first_last',
        'email' => 'first_last@organization.org',
        'created' => '2016-02-29 11:58:00',
        'last_login' => null,
        'pw_last_changed' => null,
        'pw_expires' => null,
        'access_token' => Utils::getAccessTokenHash('user1'),
        'access_token_expiration' => Utils::getDatetime(time() + 1800),
    ],
    'user2' => [
        'id' => 2,
        'uid' => '22222222222222222222222222222222',
        'employee_id' => '222222',
        'first_name' => 'User',
        'last_name' => 'Two',
        'idp_username' => 'user_two',
        'email' => 'user_two@organization.org',
        'created' => '2016-02-29 11:58:00',
        'last_login' => null,
        'pw_last_changed' => null,
        'pw_expires' => null,
        'access_token' => Utils::getAccessTokenHash('user2'),
        'access_token_expiration' => Utils::getDatetime(time() + 1800),
    ],
    'user3' => [
        'id' => 3,
        'uid' => '33333333333333333333333333333333',
        'employee_id' => '333333',
        'first_name' => 'User',
        'last_name' => 'Three',
        'idp_username' => 'user_three',
        'email' => 'user_three@organization.org',
        'created' => '2016-02-29 11:58:00',
        'last_login' => null,
        'pw_last_changed' => null,
        'pw_expires' => null,
        'access_token' => Utils::getAccessTokenHash('user3'),
        'access_token_expiration' => Utils::getDatetime(time() + 1800),
    ],
    'user4' => [
        'id' => 4,
        'uid' => '33333333333333333333333333333334',
        'employee_id' => '444444',
        'first_name' => 'User',
        'last_name' => 'Four',
        'idp_username' => 'user_four',
        'email' => 'user_four@organization.org',
        'created' => '2016-02-29 11:58:00',
        'last_login' => null,
        'pw_last_changed' => null,
        'pw_expires' => null,
    ],
];