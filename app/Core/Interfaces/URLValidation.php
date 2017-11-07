<?php

namespace App\Core\Interfaces;

interface URLValidation
{
    const URL_REGEX = '/^((?:https?:\/\/)?[^.\/]+(?:\.[^.\/]+)+(?:\/.*)?)$/';
}
