<?php

namespace App\Http\Controllers;

use App\Models\User;

class BuyerController extends Controller
{

    public function getAllAgents()
{
    $agents = User::with('profile') // نحمل علاقة البروفايل
        ->whereHas('role', function ($query) {
            $query->where('type', 'agent');
        })
        ->get();

    return response()->json($agents);
}


    public function searchAgents($keyword)
    {
        $agents = User::whereHas('role', function ($query) {
            $query->where('type', 'agent');
        })->where(function ($q) use ($keyword) {
            $q->where('first_name', 'like', "%$keyword%")
                ->orWhere('last_name', 'like', "%$keyword%");
        })->get();

        return response()->json($agents);
    }

public function getAgentById($id)
{
    $agent = User::with([
        'profile',
        'reviews.user',
        'property.images',        // ✅ صور العقار
        'property.amenities',     // ✅ المرافق
        'property.property_type', // ✅ نوع العقار
        'property.listing_type',  // ✅ نوع الإعلان
    ])
    ->whereHas('role', function ($query) {
        $query->where('type', 'agent');
    })
    ->where('id', $id)
    ->first();

    if (!$agent) {
        return response()->json(['message' => 'Agent not found'], 404);
    }

    return response()->json([
        'status' => 200,
        'agent' => $agent
    ]);
}





}
