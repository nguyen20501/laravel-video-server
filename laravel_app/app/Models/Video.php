<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    // Các cột được phép điền dữ liệu (Mass Assignment)
    protected $fillable = [
        'title', // Tiêu đề video
        'path',  // Đường dẫn lưu video
    ];

    /**
     * Tùy chỉnh nếu cần các accessor hoặc các phương thức liên quan đến video
     */
    public function getVideoUrlAttribute()
    {
        // Trả về URL đầy đủ của video
        return asset('storage/' . $this->path);
    }
}

