<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Events;
use Calendar;
use DB;

class EventsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    
            $events = DB::table('events')->get();
            $event_list = [];
            
            foreach($events as $key =>$event){
                $event_list[] = Calendar::event(
                    $event->event_title,
                    true,
                    new \DateTime($event->start_date),
                    new \DateTime($event->end_date.'+1 day'),                   
                    );
            }
            $calendar_details =  Calendar::addEvents($event_list);
            return $event_list;
    
        
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
        $user = auth('api')-> user();
        $userlogged =  $user->id;
    
        return Events::create([
            'user_id'=> $userlogged,
            'event_title' => $request['event_title'],
            'event_description' => $request['event_description'],
            'start_date' => $request['start_date'],
            'end_date' => $request['end_date'],
            
        ]);
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
