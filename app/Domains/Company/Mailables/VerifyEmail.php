<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 10:23 PM
 */

namespace App\Domains\Company\Mailables;

namespace App\Domains\Company\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class VerifyEmail extends Mailable
{
    use Queueable;

    /**
     * @var string
     */
    public $pin;

    public function __construct(string $pin)
    {
        $this->pin = $pin;
    }

    public function build()
    {
        return $this->view('emails.registration.verify-email');
    }
}
