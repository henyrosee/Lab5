<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Products extends Controller
{
    public function before_action()
    {
        $this->ensure_products_table();
    }

    public function index()
    {
        $this->call->model('ProductModel');
        $this->call->view('products/index', [
            'products' => $this->ProductModel->all_products(),
            'message' => $this->session->flashdata('message'),
            'error' => $this->session->flashdata('error'),
        ]);
    }

    public function create()
    {
        $this->call->view('products/form', [
            'product' => null,
            'errors' => [],
            'form_action' => site_url('products/create'),
        ]);
    }

    public function store()
    {
        $data = $this->validated_product();
        if (isset($data['errors'])) {
            $this->call->view('products/form', [
                'product' => $data['product'],
                'errors' => $data['errors'],
                'form_action' => site_url('products/create'),
            ]);
            return;
        }

        $this->call->model('ProductModel');
        $this->ProductModel->create_product($data);
        $this->session->set_flashdata('message', 'Product added successfully.');
        redirect('products');
    }

    public function edit($id)
    {
        $this->call->model('ProductModel');
        $product = $this->ProductModel->find_product($id);
        if (!$product) {
            show_404();
        }

        $this->call->view('products/form', [
            'product' => $product,
            'errors' => [],
            'form_action' => site_url('products/edit/' . (int) $id),
        ]);
    }

    public function update($id)
    {
        $data = $this->validated_product();
        if (isset($data['errors'])) {
            $data['product']['id'] = (int) $id;
            $this->call->view('products/form', [
                'product' => $data['product'],
                'errors' => $data['errors'],
                'form_action' => site_url('products/edit/' . (int) $id),
            ]);
            return;
        }

        $this->call->model('ProductModel');
        $this->ProductModel->update_product($id, $data);
        $this->session->set_flashdata('message', 'Product updated successfully.');
        redirect('products');
    }

    public function delete($id)
    {
        $this->call->model('ProductModel');
        $this->ProductModel->delete_product($id);
        $this->session->set_flashdata('message', 'Product deleted successfully.');
        redirect('products');
    }

    private function validated_product()
    {
        $product = [
            'product_name' => trim((string) $this->request->post('product_name')),
            'description' => trim((string) $this->request->post('description')),
            'image_url' => trim((string) $this->request->post('image_url')),
            'price' => $this->request->post('price'),
            'quantity' => $this->request->post('quantity'),
        ];
        $errors = [];

        if ($product['product_name'] === '' || strlen($product['product_name']) > 100) {
            $errors[] = 'Product name is required and must be 100 characters or fewer.';
        }
        if (!is_numeric($product['price']) || (float) $product['price'] < 0) {
            $errors[] = 'Price must be a non-negative number.';
        }
        if (filter_var($product['quantity'], FILTER_VALIDATE_INT) === false || (int) $product['quantity'] < 0) {
            $errors[] = 'Quantity must be a non-negative whole number.';
        }
        $image_scheme = $product['image_url'] !== '' ? parse_url($product['image_url'], PHP_URL_SCHEME) : null;
        if ($product['image_url'] !== '' && (!filter_var($product['image_url'], FILTER_VALIDATE_URL) || !in_array(strtolower((string) $image_scheme), ['http', 'https'], true))) {
            $errors[] = 'Image URL must be a valid URL.';
        }

        return $errors ? ['errors' => $errors, 'product' => $product] : [
            'product_name' => $product['product_name'],
            'description' => $product['description'],
            'image_url' => $product['image_url'],
            'price' => number_format((float) $product['price'], 2, '.', ''),
            'quantity' => (int) $product['quantity'],
        ];
    }

    private function ensure_products_table()
    {
        $this->db->raw(
            'CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_name VARCHAR(100) NOT NULL,
                description TEXT NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                quantity INT NOT NULL,
                image_url VARCHAR(500) NULL,
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB'
        );
        $column = $this->db->raw(
            "SELECT COUNT(*) AS total FROM INFORMATION_SCHEMA.COLUMNS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'products' AND COLUMN_NAME = 'image_url'"
        )->fetch(PDO::FETCH_ASSOC);
        if ((int) $column['total'] === 0) {
            $this->db->raw('ALTER TABLE products ADD image_url VARCHAR(500) NULL AFTER quantity');
        }
    }
}
