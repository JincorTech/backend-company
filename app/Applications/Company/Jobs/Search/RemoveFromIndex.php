<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 27/04/2017
 * Time: 02:06
 */

namespace App\Applications\Company\Jobs\Search;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class RemoveFromIndex implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    private $ids;

    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }



}