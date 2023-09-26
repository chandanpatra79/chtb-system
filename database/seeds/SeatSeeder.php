<?php

use Illuminate\Database\Seeder;
use App\Seat;
class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		$arr =  [

                'A','B','C',
				'D','E','F',
				'G','H','I',
				'J','K','L',
				'M','N','O',
				'P','Q','R',
				'S','T'
        ];

		$res =[];
		foreach($arr as $val)
		{
			$res[$val] = 0;
		}

		for($i=1; $i<=10; $i++)
		{
		  Seat::create($res);
		}

    }
}
