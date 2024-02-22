<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Upazila extends Model
{
    use HasFactory;

    protected $table = 'upazilas';
    protected $fillable = [];

    protected static function newFactory()
    {
        return \Modules\Order\Database\factories\UpazilaFactory::new();
    }
}
