<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $gategoryId = $request->input('category_id');
        $event=[];
        if ($gategoryId == 'all'){
            $events = Event::all();
        } else {
            $events = Event::where('event_category_id', $gategoryId)->get();
        }

        $events->load('eventCategory', 'vendor');
        return response()->json([
            'status' => 'success',
            'message' => 'Event tetched successfully',
            'data' => $events,
        ]);
    }

    public function categories()
    {
        $categories = EventCategory::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Event tetched successfully',
            'data' => $categories,
        ]);
    }

    public function detail(Request $request)
    {
        $event = Event::find($request->event_id);
        $event->load('eventCategory', 'vendor');
        $skus = $event->skus;
        $event['skus'] = $skus;
        return response()->json([
            'status' => 'success',
            'message' => 'Event tetched successfully',
            'data' => $event,
        ]);
    }
}
