<?php

namespace App\Http\Controllers\Api\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCreateRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Http\Services\MediaService;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    protected $mediaService;

    public function __construct(MediaService $mediaService)
    {
        $this->mediaService=$mediaService;
    }
    public function index()
    {
        $events=Event::get();

        return response()->json(
            [
                "success"=>true,
                "events"=>EventResource::collection($events),
            ]
        ,200);
    }

    public function allwithCategory()
    {

        $events=Event::with("category")->get(); //Eager loading
           return response()->json(
            [
                "success"=>true,
                "events"=>EventResource::collection($events),
            ]
        ,200);
    }

    public function show(Event $event)
    {
        return response()->json([
          "success"=>true,
          "event"=> new EventResource($event)
        ],200);
    }
    public function showwithCategory(Event $event)
    {
        $event=Event::with("category")->first();

        return response()->json([
          "success"=>true,
          "event"=> new EventResource($event)
        ],200);
    }


    public function create(EventCreateRequest $request)
    {
        $request->validated();

        $event=Event::create([
             "title"=>$request->title,
         "description"=>$request->description,
         "location"=>$request->location,
         "date"=>$request->date,
         "available_seats"=>$request->available_seats,
         "category_id"=>$request->category_id,
        ]
        );

        foreach($request->file("images") as $image)
        {
            $this->mediaService->createMedia($event,$image,"main_image");
        }

        // $event->addMedia($request->file("image"))->toMediaCollection("main_image");
        // $this->mediaService->createMedia($event,$request->file("image"),"main_image");

           return response()->json([
          "success"=>true,
          "event"=> new EventResource($event)
        ],201);
    }

    public function update(UpdateEventRequest $request,Event $event)
    {
        $request->validated();
            // dd($request);
        $event->update([
        "title"=>$request->title,
        "description"=>$request->description,
        "location"=>$request->location,
        "date"=>$request->date,
        "available_seats"=>$request->available_seats,
        "category_id"=>$request->category_id,
        ]);

        if($request->hasFile("image")){
             foreach($request->file("images") as $image)
        {

            $this->mediaService->editMedia($event,$request->file("image"),"main_image");
        }
        }

              return response()->json([
          "success"=>true,
          "message"=>"event updated successfully",
          "event"=> new EventResource($event)
        ],200);
    }

    public function delete(Event $event)
    {
        $this->mediaService->deleteMedia($event,"main_image");

        $event->delete();

              return response()->json(
            [
                "success"=>true,
                 "message"=>"event deleted successfully",
            ]
        ,200);
    }
}
