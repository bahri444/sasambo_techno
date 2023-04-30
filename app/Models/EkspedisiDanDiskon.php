<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EkspedisiDanDiskon extends Model
{
    use HasFactory;
    // protected $primaryKey = 'pesanan_id';
    protected $table = 'ekspedisi_dan_diskon';
    protected $fillable = [
        'pesanan_id',
        'berat_paket',
        'satuan_berat',
        'tarif',
        'total_ekspedisi',
        'persentase_diskon',
        'perolehan_diskon',
        'total_diskon',
        'total_semua_pesanan',
        'created_at',
        'updated_at',

    ];
    public $timestamps = false;
    // public function GetEkspedisiDanDiskonInPesanan()
    // {
    //     return $this->hasOne(Pesanan::class, 'pesanan_id', 'pesanan_id');
    // }
}
