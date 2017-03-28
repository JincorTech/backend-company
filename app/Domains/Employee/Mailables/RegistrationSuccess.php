<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 9:23 PM
 */

namespace App\Domains\Employee\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class RegistrationSuccess extends Mailable
{
    use Queueable;

    public $companyName;

    public $employeeName;

    public $employeePosition;

    public function __construct(string $companyName, string $name, string $position)
    {
        $this->companyName = $companyName;
        $this->employeeName = $name;
        $this->employeePosition = $position;
    }

    public function build()
    {
        return $this->view('emails.registration.complete');
    }
}
