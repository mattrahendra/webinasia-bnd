<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Services\GoDaddyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DomainController extends Controller
{
    protected $goDaddyService;

    public function __construct(GoDaddyService $goDaddyService)
    {
        $this->goDaddyService = $goDaddyService;
        $this->middleware('role:user,admin')->only(['reserve']);
    }

    public function search(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|min:1|max:50',
            'extensions' => 'array',
            'extensions.*' => 'string|in:com,net,org,id,co.id,web.id'
        ]);

        $domain = strtolower(trim($request->domain));
        $extensions = $request->extensions ?? ['com', 'net', 'org', 'id'];
        $cacheKey = "domain_search_" . md5($domain . implode(',', $extensions));

        $results = Cache::remember($cacheKey, 300, function () use ($domain, $extensions) {
            return $this->goDaddyService->checkAvailability($domain, $extensions);
        });

        return view('domains.search', compact('results', 'domain', 'extensions'));
    }

    public function pricing()
    {
        $pricing = Cache::remember('domain_pricing', 3600, function () {
            return $this->goDaddyService->getPricing();
        });

        return view('domains.pricing', compact('pricing'));
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|string',
            'extension' => 'required|string'
        ]);

        $user = Auth::user();
        $fullDomain = $request->domain_name . '.' . $request->extension;

        $availability = $this->goDaddyService->checkAvailability($request->domain_name, [$request->extension]);

        if (!$availability[0]['available']) {
            return redirect()->back()->with('error', 'Domain is no longer available');
        }

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

        return redirect()->route('domains.search')->with('success', 'Domain reserved for 15 minutes');
    }
}
