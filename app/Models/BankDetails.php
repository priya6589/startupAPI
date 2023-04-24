<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    use HasFactory;
    
    protected $table = 'bank_details';
    protected $fillable = [
     'user_id',
     'bank_name',
     'account_holder',
     'account_no',
     'ifsc_code'];
}
