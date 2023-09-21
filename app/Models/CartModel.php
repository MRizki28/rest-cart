<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartModel extends Model
{
    use HasFactory;
    protected $table = 'tb_cart';
    protected $fillable = [
        'id', 'id_menu', 'quantity', 'created_at', 'updated_at'
    ];

    public function menu()
    {
        return $this->belongsTo(MenuModel::class , 'id_menu');
    }
    public function getMenu($id_menu)
    {
        $data = $this->join('tb_menu', 'tb_menu.id_menu', '=', 'tb_menu.id')
            ->select('tb_menu.id', 'tb_menu.nama_menu', 'tb_menu.gambar_menu', 'tb_menu.harga')
            ->where('tb_cart.id_menu', '=', $id_menu)
            ->first();
        return $data;
    }
}
