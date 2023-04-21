<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;
    protected $table = 'proof_documents';
    protected $fillable = [
        'user_id',
        'pan_number',
        'uid',
        'dob',
        'proof_img'
    ];
}
