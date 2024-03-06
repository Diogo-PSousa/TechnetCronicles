<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnblockAppeal extends Model
{
    use HasFactory;
    protected $primaryKey = 'appeal_id';
    protected $table = 'unblockappeal';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'appeal_text',
        'appeal_date',
        'is_resolved',
    ];

}
