<?php
// app/Validators/EmailValidator.php

namespace App\Validators;

class EmailValidator
{
    public static function validate(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}