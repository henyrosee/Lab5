<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class AuthMiddleware
{
    public function handle(Closure $next)
    {
        $session = lava_instance()->session;

        if (!$session->has_userdata('user_id')) {
            $session->set_flashdata('error', 'Please log in to manage products.');
            redirect('login');
        }

        return $next();
    }
}
