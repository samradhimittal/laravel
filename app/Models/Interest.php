<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interest extends Model
{

	use HasFactory;

    protected $table = 'interest';

    protected $fillable = [
        'name'
    ];

    public function users()
	{
		return $this->hasMany(UserInterest::class, 'interest_id', 'id');
	}

}
