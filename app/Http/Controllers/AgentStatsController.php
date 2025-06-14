<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class AgentStatsController extends Controller
{

    public function getPropertyStats(Request $request): JsonResponse
    {
        $userId = Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }


        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        if ($request->route('id') && $user->id != $request->route('id')) {
            return response()->json(['error' => 'Unauthorized access.'], 403);
        }


        $total = DB::table('properties')->where('user_id', $user->id)->count();
        $sold = DB::table('properties')
            ->where('user_id', $user->id)

            ->count();
        $available = DB::table('properties')
            ->where('user_id', $user->id)
            ->where('available', 1)
            ->count();
        $rented = $total - $available  ;

        return response()->json([
            'agentName' => "{$user->first_name} {$user->last_name}",
            'soldCount' => (int)$sold,
            'rentedCount' => (int)$rented,
            'totalCount' => (int)$total,
            'availableCount' => (int)$available,
        ]);
    }
}
