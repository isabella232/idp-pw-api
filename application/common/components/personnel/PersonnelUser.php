<?php
namespace common\components\personnel;

/**
 * Class PersonnelUser
 * @package common\components\personnel
 */
class PersonnelUser
{
    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $employeeId;

    /**
     * @var string
     */
    public $username;

    /**
     * @var null|string
     */
    public $supervisorEmail;

    /**
     * @var null|string
     */
    public $spouseEmail;

    /**
     * @var int
     */
    public $doNotDisclose;
}
