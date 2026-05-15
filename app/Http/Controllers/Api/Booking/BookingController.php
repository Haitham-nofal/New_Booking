<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{

    public function index()
    {
        $bookings = Auth::user()->bookings()->with('event')->get();

        return response()->json([
            'success' => true,
            'data' => BookingResource::collection($bookings)
        ]);
    }
    public function allWData()
    {
        $bookings = Booking::with('event.category', 'user')->get();

        return response()->json([
            'success' => true,
            'data' => BookingResource::collection($bookings)
        ]);
    }


    public function show(Booking $booking)
    {

        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $booking->load('event');

        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking)
        ]);
    }

    public function showWithData(Booking $booking)
    {
        $booking->load('event.category', 'user');

        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking)
        ]);
    }

    // حذف حجز
    public function destroy(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }


    public function create(BookingRequest $request, Event $event)
    {
        $user = Auth::user();

        return DB::transaction(function () use ($event, $user, $request) {

            $event = Event::where('id', $event->id)->lockForUpdate()->first();

            if ($event->available_seats <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No available seats'
                ], 400);
            }

            $bookingExists = Booking::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->exists();

            if ($bookingExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking already exists'
                ], 400);
            }

            $event->decrement('available_seats');

            $booking = Booking::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'status' => $request->status ?? 'pending',
            ]);

            $booking->load(['user', 'event.category']);

            return response()->json([
                'success' => true,
                'data' => new BookingResource($booking)
            ], 201);
        });
    }


    public function update(BookingRequest $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $booking->update([
            'status' => $request->status ?? $booking->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => new BookingResource($booking)
        ]);
    }
}
