<?php

namespace App\Http\Controllers;

use App\Models\Route as BusRoute;
use App\Models\TransportAssignment;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class TransportationController extends Controller
{
    public function index()
    {
        $routes = BusRoute::all();
        $vehicles = Vehicle::all();

        return view('transport.index', compact('routes', 'vehicles'));
    }

    public function assignStudent(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        // Check capacity
        $currentAssignments = TransportAssignment::where('vehicle_id', $vehicle->id)->count();
        if ($currentAssignments >= $vehicle->capacity) {
            return back()->with('error', 'Vehicle is at maximum capacity.');
        }

        TransportAssignment::create($request->all());

        return back()->with('success', 'Student assigned to route successfully.');
    }
}
