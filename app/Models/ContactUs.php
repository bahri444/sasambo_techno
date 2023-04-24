<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;
    protected $primaryKey = 'testimoni_id';
    protected $fillable = ['nama_lengkap',    'email',    'telepon',    'saran', 'created_at', 'updated_at'];
    public $timestamps = false;
}
