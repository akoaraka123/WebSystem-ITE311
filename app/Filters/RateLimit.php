<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RateLimit implements FilterInterface
{
    /**
     * Rate limiting filter to prevent brute force attacks
     * Limits login attempts per IP address
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // Only apply to login POST requests
        if ($request->getMethod() === 'POST' && uri_string() === 'login') {
            $session = \Config\Services::session();
            $ipAddress = $request->getIPAddress();
            $cache = \Config\Services::cache();
            $lockoutKey = 'login_lockout_' . md5($ipAddress);
            
            // Check if currently locked out
            $lockoutUntil = $cache->get($lockoutKey);
            if ($lockoutUntil !== null && $lockoutUntil > time()) {
                $remaining = $lockoutUntil - time();
                $minutes = ceil($remaining / 60);
                $session->setFlashdata('error', "Too many login attempts. Please try again in {$minutes} minute(s).");
                return redirect()->to(base_url('login'))->withInput();
            }
            
            // If lockout expired but they try again, increment lockout count for progressive lockout
            // This handles the case where they wait out the first lockout and fail again
            if ($lockoutUntil !== null && $lockoutUntil <= time()) {
                // Lockout expired, but they're trying again - check if they should get longer lockout
                $lockoutCountKey = 'login_lockout_count_' . md5($ipAddress);
                $lockoutCount = $cache->get($lockoutCountKey) ?? 0;
                
                // If they've been locked out before and are trying again, apply progressive lockout
                if ($lockoutCount > 0) {
                    $lockoutCount++;
                    
                    // Progressive lockout: first time = 5 minutes, second time = 10 minutes
                    if ($lockoutCount == 1) {
                        $lockoutDuration = 300; // 5 minutes
                        $lockoutMinutes = 5;
                    } else {
                        $lockoutDuration = 600; // 10 minutes
                        $lockoutMinutes = 10;
                    }
                    
                    // Apply new lockout
                    $cache->save($lockoutKey, time() + $lockoutDuration, $lockoutDuration);
                    $cache->save($lockoutCountKey, $lockoutCount, 900);
                    
                    $session->setFlashdata('error', "Too many login attempts. Please try again in {$lockoutMinutes} minute(s).");
                    return redirect()->to(base_url('login'))->withInput();
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after request
    }
}
