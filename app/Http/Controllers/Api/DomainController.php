<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Services\GoDaddyService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DomainController extends Controller
{
    protected $goDaddyService;

    public function __construct(GoDaddyService $goDaddyService)
    {
        $this->goDaddyService = $goDaddyService;
    }

    /**
     * Search domain availability
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'domain' => 'required|string|min:1|max:50',
            'extensions' => 'array',
            'extensions.*' => 'string|in:com,net,org,id,co.id,web.id'
        ]);

        $domain = strtolower(trim($request->domain));
        $extensions = $request->extensions ?? ['com', 'net', 'org', 'id'];

        // Cache key for this search
        $cacheKey = "domain_search_" . md5($domain . implode(',', $extensions));

        $results = Cache::remember($cacheKey, 300, function () use ($domain, $extensions) { // Cache 5 minutes
            return $this->goDaddyService->checkAvailability($domain, $extensions);
        });

        return response()->json([
            'success' => true,
            'data' => $results
        ]);
    }

    /**
     * Get domain pricing
     */
    public function pricing(): JsonResponse
    {
        $pricing = Cache::remember('domain_pricing', 3600, function () { // Cache 1 hour
            return $this->goDaddyService->getPricing();
        });

        return response()->json([
            'success' => true,
            'data' => $pricing
        ]);
    }

    /**
     * Reserve domain temporarily (requires auth)
     */
    public function reserve(Request $request): JsonResponse
    {
        $request->validate([
            'domain_name' => 'required|string',
            'extension' => 'required|string'
        ]);

        $user = Auth::user();
        $fullDomain = $request->domain_name . '.' . $request->extension;

        // Check if domain is still available
        $availability = $this->goDaddyService->checkAvailability($request->domain_name, [$request->extension]);

        if (!$availability[0]['available']) {
            return response()->json([
                'success' => false,
                'message' => 'Domain is no longer available'
            ], 400);
        }

        // Reserve domain for 15 minutes
        $domain = Domain::updateOrCreate(
            ['name' => $fullDomain],
            [
                'extension' => $request->extension,
                'price' => $availability[0]['price'],
                'renewal_price' => $availability[0]['renewal_price'],
                'status' => 'reserved',
                'user_id' => $user->id,
                'reserved_until' => now()->addMinutes(15),
                'godaddy_data' => $availability[0]
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $domain,
            'message' => 'Domain reserved for 15 minutes'
        ]);
    }
}
