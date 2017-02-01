<?php
/**
 * Copyright (c) 2016  Universal Business Network - All rights reserved.
 *
 * Created by hlogeon <email: hlogeon1@gmail.com>
 * Date: 11/21/16
 * Time: 8:47 PM
 */

namespace App\Core\Http\Middleware;

use Closure;
use App;
use Dingo\Api\Auth\Provider\JWT;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($request->has('locale') || $request->hasHeader('locale')) {
            $locale = $request->get('locale') ?: $request->header('locale');
            App::setLocale($locale);
        }

        return $next($request);
    }
}
