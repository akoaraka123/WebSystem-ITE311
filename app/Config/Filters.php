<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,

        // ✅ Custom Filter for Role Authorization
        'roleauth'      => \App\Filters\RoleAuth::class,
    ];

    public array $required = [
        'before' => [
            // You can disable these if you’re not using HTTPS or caching yet
            // 'forcehttps',
            // 'pagecache',
        ],
        'after' => [
        'toolbar',     // Debug Toolbar
            // 'performance',
        ],
    ];


    public array $globals = [
        'before' => [
            // 'honeypot',
       
     // 'csrf',
        ],
        'after' => [
            // 'honeypot',
            // 'secureheaders',
        ],
    ];


    public array $methods = [];

    public array $filters = [
        // ✅ Apply RoleAuth filter before accessing specific areas
        'roleauth' => [
            'before' => [
                'admin/*',
                'teacher/*',
                'student/*',
            ],
        ],
    ];
}
