<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class ProductModel extends Model
{
    protected $table = 'products';

    public function all_products()
    {
        return $this->db->table($this->table)
            ->order_by('created_at', 'DESC')
            ->get_all();
    }

    public function find_product($id)
    {
        return $this->db->table($this->table)
            ->where('id', (int) $id)
            ->get();
    }

    public function create_product(array $data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function update_product($id, array $data)
    {
        return $this->db->table($this->table)
            ->where('id', (int) $id)
            ->update($data);
    }

    public function delete_product($id)
    {
        return $this->db->table($this->table)
            ->where('id', (int) $id)
            ->delete();
    }
}
