<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanCuti extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','tanggal_cuti','alasan_cuti','status_permohonan','tanggal_cuti_selesai','created_at','updated_at'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
