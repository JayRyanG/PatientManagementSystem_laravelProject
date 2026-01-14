<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use Hasfactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'date_of_birth',
        'email',
        'phone_number',
        'address',
        'doctor_id',
        'photo',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
