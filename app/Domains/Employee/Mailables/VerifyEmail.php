<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 1/30/17
 * Time: 10:23 PM
 */

namespace App\Domains\Employee\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

class VerifyEmail extends Mailable
{
    use Queueable;


    /**
     * @var string
     */
    public $pin;

    /**
     * VerifyEmail constructor.
     * @param string $pin
     */
    public function __construct(string $pin)
    {
        $this->pin = $pin;
    }

    /**
     * @return string
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.registration.verify-email')
            ->with('pin', $this->pin);
    }

}
