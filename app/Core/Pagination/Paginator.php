<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 25/05/2017
 * Time: 13:35
 */

namespace App\Core\Pagination;


use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends LengthAwarePaginator
{

    public function toArray()
    {
        return [
            'total' => $this->total(),
            'perPage' => $this->perPage(),
            'currentPage' => $this->currentPage(),
            'lastPage' => $this->lastPage(),
            'nextPageUrl' => $this->nextPageUrl(),
            'prevPageUrl' => $this->previousPageUrl(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
//            'data' => $this->items->toArray(),
        ];
    }

}