<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public const REQUEST_STATUS_PENDING = 1;
    public const REQUEST_STATUS_APPROVED = 2;
    public const REQUEST_STATUS_REJECT = 3;

    use HasFactory;
    protected $table = 'company';
    protected $fillable = [
        'company_name', 'company_type', 'domain_name', 'email'

    ];

    public static function getCompStatus(){
        return [
            ['id'=> static::REQUEST_STATUS_PENDING, 'name'=>'Pending'],
            ['id'=> static::REQUEST_STATUS_APPROVED, 'name'=>'Approved'],
            ['id'=> static::REQUEST_STATUS_REJECT, 'name'=>'Rejected']
        ];
    }
}
