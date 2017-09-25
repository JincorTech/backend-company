<?php
/**
 * Created by PhpStorm.
 * User: alekns
 * Date: 21.09.17
 * Time: 21:19
 */

namespace App\Core\Services\Mailing\Lists;

use Illuminate\Support\Manager;

class MailingListManager extends Manager
{
    /**
     * @param string $driver
     * @return MailchimpListService|MailgunListService
     */
    protected function createDriver($driver)
    {
        if ($driver === 'mailchimp') {
            return new MailchimpListService();
        } elseif ($driver === 'mailgun') {
            return new MailgunListService();
        } else {
            throw new \RuntimeException('Not implemented');
        }
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return config('mailinglist.driver');
    }
}
