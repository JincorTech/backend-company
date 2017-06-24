<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 21.06.17
 * Time: 23:26
 */

namespace App\Applications\Company\Http\Requests\Traits;


trait PaginatedRequest
{
    public function getPerPage()
    {
        return $this->get('perPage', null) !== null ? (int) $this->get('perPage') : config('view.perPage');
    }

    public function getPage()
    {
        return $this->get('page', 1);
    }
}
