<?php

namespace UserManager\Application\Service\User\Add;

final class AddUserRequest
{
    /** @var string */
    private $name;

    /** @var string */
    private $surname;

    /** @var string */
    private $email;

    /** @var string */
    private $username;

    /** @var array */
    private $skills;

    public function __construct($a_raw_name, $a_raw_surname, $a_raw_email, $a_raw_username, $a_skills = [])
    {
        $this->name = $a_raw_name;
        $this->surname = $a_raw_surname;
        $this->email = $a_raw_email;
        $this->username = $a_raw_username;
        $this->skills = $a_skills;
    }

    public function name()
    {
        return $this->name;
    }

    public function surname()
    {
        return $this->surname;
    }

    public function email()
    {
        return $this->email;
    }

    public function username()
    {
        return $this->username;
    }

    public function skills()
    {
        return $this->skills;
    }
}
