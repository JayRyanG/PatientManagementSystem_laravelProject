<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use Hasfactory;

    protected $fillable = [
        'name',
        'date_of_birth',
        'email',
        'phone_number',
        'address',
        'doctor_id',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
