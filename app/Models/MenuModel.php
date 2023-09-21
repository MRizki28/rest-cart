<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuModel extends Model
{
    use HasFactory;
    protected $table = 'tb_menu';
    protected $fillable = [
        'id' , 'nama_menu' , 'gambar_menu' , 'harga', 'created_at' ,'updated_at'
    ];
}
