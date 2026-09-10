<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth extends Controller
{
    public function login()
    {
        if ($this->session->has_userdata('user_id')) {
            redirect('products');
        }

        $this->ensure_admin_account();
        $error = $this->session->flashdata('error');

        if ($this->request->is_post()) {
            $email = trim((string) $this->request->post('email'));
            $password = (string) $this->request->post('password');
            $user = $this->db->table('users')->where('email', $email)->get();

            if ($user && password_verify($password, $user['password']) && (int) $user['is_active'] === 1) {
                $this->session->sess_regenerate();
                $this->session->set_userdata([
                    'user_id' => (int) $user['id'],
                    'user_email' => $user['email'],
                ]);
                redirect('products');
            }

            $error = 'Invalid email or password.';
        }

        $this->call->view('auth/login', ['error' => $error]);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }

    private function ensure_admin_account()
    {
        $this->db->raw(
            'CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                is_active TINYINT(1) NOT NULL DEFAULT 1,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB'
        );
        $columns = [
            'email' => 'VARCHAR(255) NULL',
            'password' => 'VARCHAR(255) NULL',
            'is_active' => 'TINYINT(1) NOT NULL DEFAULT 1',
            'created_at' => 'TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP',
        ];
        foreach ($columns as $name => $definition) {
            $column = $this->db->raw(
                "SELECT COUNT(*) AS total FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users' AND COLUMN_NAME = ?",
                [$name]
            )->fetch(PDO::FETCH_ASSOC);
            if ((int) $column['total'] === 0) {
                $this->db->raw("ALTER TABLE users ADD {$name} {$definition}");
            }
        }

        $email = trim((string) getenv('ADMIN_EMAIL'));
        $password = (string) getenv('ADMIN_PASSWORD');
        if ($email === '' || $password === '') {
            return;
        }

        $existing = $this->db->table('users')->where('email', $email)->get();
        if (!$existing) {
            $available = $this->db->raw(
                "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'users'"
            )->fetchAll(PDO::FETCH_COLUMN);
            $account = [
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'is_active' => 1,
                'username' => $email,
                'firstname' => 'Admin',
                'lastname' => 'User',
                'role' => 'admin',
            ];
            $account = array_intersect_key($account, array_flip($available));
            $this->db->table('users')->insert($account);
        }
    }
}
