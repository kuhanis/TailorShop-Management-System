<?php

namespace App\Models;

use App\Models\Designation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'user_id',
        'address',
        'phone',
        'salary',
        'name',
        'username',
        'email'
    ];
    

    public function user(){
        return $this->belongsTo(User::class);
    }
}
