<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface {

    public function before(RequestInterface $request, $arguments = null) {
        $uri = $request->getUri()->getPath();
        $path = trim($uri, '/');

        // Normalize "index.php" prefix so routes resolve consistently
        // whether the app is served from "/" or "/index.php/".
        if ($path === 'index.php') {
            $path = '';
        } elseif (str_starts_with($path, 'index.php/')) {
            $path = substr($path, strlen('index.php/'));
        }

        // Root URL = public landing page
        if ($path === '') {
            return;
        }

        // Allow public routes
        $publicRoutes = ['login', 'register', 'logout', 'about', 'contact'];
        // Allow language switch route (GET /language/{locale})
        if (str_starts_with($path, 'language/')) {
            return;
        }
        foreach ($publicRoutes as $route) {
            if ($path === $route) {
                return;
            }
        }

        // Allow public routes with dynamic segments (e.g. verify-email/{token})
        $publicPrefixes = ['verify-email', 'share'];
        foreach ($publicPrefixes as $prefix) {
            if ($path === $prefix || str_starts_with($path, $prefix . '/') || str_starts_with($path, 'index.php/' . $prefix . '/')) {
                return;
            }
        }

        // Allow debug toolbar and static assets
        if (str_contains($path, 'debugbar') || str_contains($path, 'uploads')) {
            return;
        }

        // Check if user is logged in
        if (!session()->get('user_id')) {
            return redirect()->to('/login')->with('error', 'Please login first.');
        }

        // Check admin routes
        if (str_starts_with($path, 'admin') && session()->get('role') !== 'admin') {
            return redirect()->to('/dashboard')->with('error', 'Access denied.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {
    }
}
