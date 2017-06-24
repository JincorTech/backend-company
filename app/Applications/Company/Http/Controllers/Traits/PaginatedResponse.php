<?php
/**
 * Created by PhpStorm.
 * User: artem
 * Date: 21.06.17
 * Time: 23:24
 */

namespace App\Applications\Company\Http\Controllers\Traits;
use Dingo\Api\Http\Response;
use Doctrine\Common\Collections\ArrayCollection;
use App\Applications\Company\Http\Requests\Traits\PaginatedRequest;
use App\Core\Pagination\Paginator;


trait PaginatedResponse
{
    /**
     * @param $request PaginatedRequest
     * @param $collection ArrayCollection
     * @param $transformer string
     * @return Response
     */
    protected function paginatedResponse($request, $collection, $transformer)
    {
        $perPage = $request->getPerPage();

        if ($perPage === 0) {
            $perPage = $collection->count();
        }

        $paginator = new Paginator($collection, count($collection), $perPage);
        return $this->response->collection(
                                    $paginator->getCollection()->forPage(
                                        $request->getPage(),
                                        $perPage
                                    ),
                                    $transformer
                                )
                                ->meta('pagination', $paginator->toArray());
    }
}
