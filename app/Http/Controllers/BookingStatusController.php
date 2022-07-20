<?php

namespace App\Http\Controllers;

use App\Models\BookingStatus;
use Illuminate\Http\Request;

class BookingStatusController extends Controller
{
    public function index()
    {
        return BookingStatus::all();
    }

    public function show($id)
    {
        return BookingStatus::findOrFail($id);
    }

    public function store(Request $request)
    {
        $status = [
            'name'=> $request->name
        ];
        
        $createdStatus = BookingStatus::create($status);
        
        return response()->json([
            'success'=> true,
            'message'=> 'status created successfully',
            'data'=> $createdStatus
        ]);
    }

    public function update(Request $request, $id)
    {
        $status = BookingStatus::findOrFail($id);

        $status->update($request->all());

        return response()->json([
            'success'=> true,
            'message'=> 'status updated successfully',
            'data'=> $status
        ]);
    }

    public function delete($id)
    {
        $status = BookingStatus::findOrFail($id);

        $status->destroy($id);

        return response()->json([
            'success'=> true,
            'message'=> 'status deleted successfully',
            'data'=> $status
        ]);
    }
}
