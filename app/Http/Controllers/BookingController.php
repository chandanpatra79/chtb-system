<?php

namespace App\Http\Controllers;

use App\Seat;
use Validator;
use Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        //
        $seatNames = Seat::SEATNAMES;
        $seats = Seat::get();
        return view('booking', compact('seats','seatNames') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();

        // VALIDATION
        $rules = [
            'chosenSeat' => 'required|regex:/^[A-Z][1-9][0]?$/',
            'reqTicket'  => 'required|numeric|max:5|min:1',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails())
        {
            // return Redirect::back()->withInput($request->all());
            return back()->withErrors( $validator->errors()->toArray() )
                         ->withInput($request->all());
        }

        // CALCULATION
        $allseats = SEAT::get()->toArray();
        $seatNames = Seat::SEATNAMES;
        // P15
        $col = substr($data['chosenSeat'], 0, 1); // P
        $row = intval( substr($data['chosenSeat'], 1) ); // 15
        $origRow = $row-1;  // As array starts from ZERO, not ONE


        $startCol = $col;
        $ticketCanBeGiven = 0;

        for($i=1; $i <= $data['reqTicket']; $i ++)
        {
           $found = 0;
           $foundSeats = [];

           // echo "<br> ITERATION $i [$col]<br>";
           for($j=1; $j <= $data['reqTicket']; $j ++)
           {
              // echo " [CHECKING $startCol - $origRow], ";
              if( isset($allseats[$origRow][$startCol]) && $allseats[$origRow][$startCol] == 0 )
              {
                $foundSeats[] = "$startCol" . ( 1 + intval($origRow) );
                $found ++;
              }
              else
              {
                break;
              }

              $startCol = chr(ord($startCol)+1);

           }

           // All Ticket found Side by Side
           if( $found == $data['reqTicket'] )
           {
                $ticketCanBeGiven = 1;
                break;
           }

           $startCol = chr(ord($col) - $i);
        }

        if($ticketCanBeGiven)
        {
            // "Ticket Can be Given", Update Database
            foreach( $foundSeats as $seat )
            {
                $col = substr($seat,0,1);
                $id  = substr($seat,1);
                Seat::where("id","=",$id)->update([$col => 1]);
            }

            // Return with Success Message
            $str = implode(", ", $foundSeats) . " were Allotted with Thanks";
            return back()->with('success', $str);
        }
        else
        {
            // ERRORs
            return back()->withErrors( ["Tickets Can not be given"] )
                         ->withInput($request->all());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
