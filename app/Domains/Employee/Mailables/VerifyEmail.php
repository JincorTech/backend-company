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
     * @var string
     */
    public $jwt;

    /**
     * VerifyEmail constructor.
     * @param string $pin
     * @param string $jwt
     */
    public function __construct(string $pin, string $jwt)
    {
        $this->pin = $pin;
        $this->jwt = $jwt;
    }

    /**
     * @return string
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->view('emails.registration.verify-email')
            ->with('pin', $this->pin)
            ->with('jwt', $this->jwt);
    }

}
