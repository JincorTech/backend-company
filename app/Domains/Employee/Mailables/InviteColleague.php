<?php
/**
 * Copyright (c) 2017  Universal Business Network - All rights reserved
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 2/21/17
 * Time: 11:55 PM
 */

namespace App\Domains\Employee\Mailables;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;


class InviteColleague extends Mailable
{

    use Queueable;

    /**
     * @var string
     */
    private $employee;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $verificationId;

    /**
     * @var string
     */
    private $verificationCode;

    /**
     * InviteColleague constructor.
     * @param string $employee
     * @param string $email
     * @param string $verificationId
     * @param string $verificationCode
     */
    public function __construct(string $employee, string $email, string $verificationId, string $verificationCode)
    {
        $this->employee = $employee;
        $this->email = $email;
        $this->verificationId = $verificationId;
        $this->verificationCode = $verificationCode;
    }


    /**
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.invitations.invite', [
            'email' => $this->email,
            'employee' => $this->employee,
            'verificationId' => $this->verificationId,
            'code' => $this->verificationCode,
        ]);
    }


}