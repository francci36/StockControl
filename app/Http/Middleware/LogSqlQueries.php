<?
// app/Http/Middleware/LogSqlQueries.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogSqlQueries
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        DB::listen(function ($query) {
            Log::info($query->sql, $query->bindings, $query->time);
        });

        return $next($request);
    }
}
