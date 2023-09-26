<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    //
    public const SEATNAMES = [
        'A','B','C',
        'D','E','F',
        'G','H','I',
        'J','K','L',
        'M','N','O',
        'P','Q','R',
        'S','T','U',
        'V','W','X',
        'Y','Z'
    ];

	protected $fillable = [
        'id',
        ...self::SEATNAMES
	];

	public $timestamps = false;
}
