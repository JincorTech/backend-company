<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/22/17
 * Time: 1:20 PM
 */

namespace App\Domains\Company\Mailables;

namespace App\Domains\Employee\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;


class RestorePassword extends Mailable
{

    use Queueable;

    private $email;

    private $verificationId;

    private $verificationCode;

    public function __construct(string $email, string $verificationId, string $verificationCode)
    {
        $this->email = $email;
        $this->verificationId = $verificationId;
        $this->verificationCode = $verificationCode;
    }

    public function build()
    {
        return $this->view('emails.restore-password.verify-email', [
            'email' => $this->email,
            'verificationId' => $this->verificationId,
            'code' => $this->verificationCode,
        ]);
    }

}