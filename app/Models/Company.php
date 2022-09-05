<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public const REQUEST_STATUS_PENDING = 0;
    public const REQUEST_STATUS_APPROVED = 1;
    public const REQUEST_STATUS_REJECT = 2;

    use HasFactory;
    protected $table ='company';
   protected $fillable = [
    'company_name','company_type','domain_name','email'

    ];

}
