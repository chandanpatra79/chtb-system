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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //
        $data = $request->all();

        // VALIDATION
        $rules = [
            'chosenSeat' => 'required|regex:/^[A-T][1-9][0]?$/',
            'reqTicket'  => 'required|numeric|max:5|min:1',
        ];

        $validator = Validator::make($data, $rules);
        if ($validator->fails())
        {
            // return Redirect::back()->withInput($request->all());
            return back()->withErrors( $validator->errors()->toArray() )
                         ->withInput($request->all());
        }

        $ret = $this->bookSeats($data['chosenSeat'], $data['reqTicket']);

        if( isset($ret['list']) && $ret['error'] == 0 )
        {
            $msg = implode(", ", $ret['list']) . " Were Allotted";
            return back()->with('success', $msg);
        }
        else
        {
            $msg = "These tickets can not be booked. I suggest these seats : " . implode(", " , $ret['suggestions']) ;
            return back()->withErrors( [ $msg ] )
                         ->withInput($request->all());
        }

    }

    /**
     * Checks if TICKETS available in any ROW
     *
     * @param string chosenSeat
     * @param int reqTicket
     * @return mixed Array
     */
    private function isSeatAvailable($row, $col, $reqTicket)
    {

        // CALCULATION
        $allseats = SEAT::get()->toArray();

        $origRow = $row-1;  // As array starts from ZERO, not ONE

        // Look Up
        $startCol = $col;
        $ticketCanBeGiven = 0;

        for($i=1; $i <= $reqTicket; $i ++)
        {
           $found = 0;
           $foundSeats = [];

           for($j=1; $j <= $reqTicket; $j ++)
           {
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
           if( $found == $reqTicket)
           {
                $ticketCanBeGiven = 1;
                break;
           }

           $startCol = chr(ord($col) - $i);
        }

        if($ticketCanBeGiven)
        {
            // Return Suggested List
            return $foundSeats;
        }
        else
        {
            return false;
        }

    }

    /**
     * PROVIDES Suggestions
     *
     * @param string chosenSeat
     * @param int reqTicket
     * @return mixed Array
     */
    private function getSuggestions($chosenSeat, $reqTicket)
    {
        // CALCULATION
        $allseats = SEAT::get()->toArray();
        $seatNames = Seat::SEATNAMES;

        // P15
        $col = substr($chosenSeat, 0, 1); // P
        $row = intval( substr($chosenSeat, 1) ); // 15
        $origRow = $row-1;  // As array starts from ZERO, not ONE

        $nextRowSearch = 0;
        $prevRowSearch = 0;

        $suggesttionArray = [];

        if( $row == 1 )
        {
            $suggesttionArray = $this->isSeatAvailable($row + 1, $col, $reqTicket);
        }
        else if( $row == 10 )
        {
            $suggesttionArray = $this->isSeatAvailable($row - 1, $col, $reqTicket);
        }
        else
        {
            $arr1 = $this->isSeatAvailable($row + 1, $col, $reqTicket);
            $arr2 = $this->isSeatAvailable($row - 1, $col, $reqTicket);
            $suggesttionArray = array_merge($arr1,$arr2);
        }

        return $suggesttionArray;
    }

    /**
     * Searches and Books Tickets, Updates DB
     *
     * @param string chosenSeat
     * @param int reqTicket
     * @return mixed Array
     */
    public function bookSeats($chosenSeat, $reqTicket)
    {
        // CALCULATION
        $allseats = SEAT::get()->toArray();

        // P15
        $col = substr($chosenSeat, 0, 1); // P
        $row = intval( substr($chosenSeat, 1) ); // 15
        $origRow = $row-1;  // As array starts from ZERO, not ONE

        // Look Up
        $startCol = $col;
        $ticketCanBeGiven = 0;

        for($i=1; $i <= $reqTicket; $i ++)
        {
           $found = 0;
           $foundSeats = [];

           for($j=1; $j <= $reqTicket; $j ++)
           {
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
           if( $found == $reqTicket)
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
            return ['error' => 0, 'list' => $foundSeats ];
        }
        else
        {

            return ['error' => 1, 'suggestions' => $this->getSuggestions($chosenSeat, $reqTicket) ];
        }
    }


}
