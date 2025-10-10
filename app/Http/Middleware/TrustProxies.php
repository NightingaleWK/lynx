<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrustProxies
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    // protected $proxies;
    // =================================================================
    // **修正: 信任所有代理**
    // 在 Docker 环境下，Nginx Proxy Manager 的 IP 地址可能会变化。
    // 设置为 '*' 是最简单的方式，让 Laravel 信任来自任何上游代理
    // 发送的 X-Forwarded-* 协议头，从而正确识别 HTTPS。
    // =================================================================
    protected $proxies = '*';


    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
    Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
