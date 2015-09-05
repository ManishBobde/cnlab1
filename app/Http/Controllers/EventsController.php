<?php

namespace App\Http\Controllers;

use App\CN\CNEvents\Events;
use App\CN\CNEvents\EventsRepository;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Validator;

class EventsController extends ApiController
{

    protected $events;

    /**
     * @param EventsRepository $events
     */
    public function __construct(EventsRepository $events ){

        //$this->auth = $auth;
        $this->events = $events ;
        $this->middleware('jwt.auth');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function retrieveEventItem($eventId)
    {
        return Events::findOrFail($eventId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function createEvent(Request $request)
    {
        return $this->events->createEvents();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteEvent($eventId)
    {
        Events::destroy($eventId);

        return "deleted event";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function editEvent($eventId)
    {
        $event = Events::find($eventId);
        if(is_null($event)){
            return "not found";
        }
        return $this->update($eventId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update($eventId)
    {
        $input = Input::all();
        $validation = Validator::make($input, Events::$rules);

        if($validation->passes()){
            $event = Events::find($eventId);
            $event->update($input);
            return "Updated Event";
        }
    }


    /**a
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function retrieveShortEventDesc(Request $request)
    {
        return $this->events->retrieveShortEventDesc();

    }

}
