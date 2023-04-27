<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorTerms extends Model
{
    use HasFactory;
    protected $table = "investor_terms";
    protected $fillable = [
        'user_id',
        'principal_residence',
        'cofounder',
        'prev_investment_exp',
        'experience',
        'net_worth',
        'no_requirements',
        'annual_income',
        'accredited_net_worth',
        'final_annual_networth',
        'foriegn_annual_income',
        'foriegn_net worth',
        'body_corporates'
    ];

}
