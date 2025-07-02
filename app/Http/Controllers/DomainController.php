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
    }

    public function index()
    {
        $featuredDomains = Cache::remember('featured_domains', 3600, function () {
            return $this->goDaddyService->getFeaturedDomains();
        });

        return view('domains.index', compact('featuredDomains'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|min:1|max:50',
            'extensions' => 'array',
            'extensions.*' => 'string'
        ]);

        $domain = strtolower(trim($request->domain));
        $extensions = $request->extensions ?? $this->goDaddyService->getAvailableExtensions();

        $cacheKey = "domain_search_" . md5($domain . implode(',', $extensions));

        $results = Cache::remember($cacheKey, 300, function () use ($domain, $extensions) {
            return $this->goDaddyService->checkAvailability($domain, $extensions);
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'results' => $results
            ]);
        }

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
        if (!Auth::check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan login untuk memesan domain',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Silakan login untuk memesan domain');
        }

        $request->validate([
            'domain_name' => 'required|string',
            'extension' => 'required|string'
        ]);

        $user = Auth::user();
        $fullDomain = $request->domain_name . '.' . $request->extension;

        try {
            $availability = $this->goDaddyService->checkAvailability($request->domain_name, [$request->extension]);

            if (!$availability[0]['available']) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Domain tidak tersedia'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Domain tidak tersedia');
            }

            $domain = Domain::updateOrCreate(
                ['name' => $fullDomain],
                [
                    'extension' => $request->extension,
                    'price' => $availability[0]['price'],
                    'status' => 'reserved',
                    'user_id' => $user->id,
                    'reserved_until' => now()->addMinutes(15),
                ]
            );

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Domain dipesan untuk 15 menit',
                    'redirect' => route('orders.create', [
                        'domain_name' => $request->domain_name,
                        'domain_extension' => $request->extension
                    ])
                ]);
            }

            return redirect()->route('orders.create', [
                'domain_name' => $request->domain_name,
                'domain_extension' => $request->extension
            ])->with('success', 'Domain dipesan untuk 15 menit. Selesaikan pesanan Anda sekarang!');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memesan domain'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memesan domain');
        }
    }

    public function checkAvailability(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|min:1|max:50',
            'extension' => 'required|string'
        ]);

        $domain = strtolower(trim($request->domain));
        $extension = $request->extension;

        try {
            $cacheKey = "domain_check_" . md5($domain . $extension);

            $result = Cache::remember($cacheKey, 300, function () use ($domain, $extension) {
                return $this->goDaddyService->checkAvailability($domain, [$extension]);
            });

            return response()->json([
                'success' => true,
                'available' => $result[0]['available'] ?? false,
                'price' => $result[0]['price'] ?? null,
                'domain' => $domain . '.' . $extension
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat memeriksa ketersediaan domain'
            ], 500);
        }
    }

    public function getReservedDomains()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $user = Auth::user();
        $reservedDomains = Domain::where('user_id', $user->id)
            ->where('status', 'reserved')
            ->where('reserved_until', '>', now())
            ->get();

        return response()->json([
            'success' => true,
            'domains' => $reservedDomains
        ]);
    }

    public function cleanupExpiredReservations()
    {
        $expiredCount = Domain::where('status', 'reserved')
            ->where('reserved_until', '<', now())
            ->delete();

        return response()->json([
            'success' => true,
            'cleaned' => $expiredCount
        ]);
    }
}
