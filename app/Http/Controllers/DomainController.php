<?php

namespace App\Http\Controllers;

use App\Models\Domain;
use App\Services\GoDaddyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

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

        // Selalu limit ke 5 ekstensi saja untuk kecepatan
        if (!empty($request->extensions)) {
            $extensions = array_slice($request->extensions, 0, 5);
        } else {
            // Default ke 5 ekstensi paling populer
            $extensions = $this->goDaddyService->getDefaultSearchExtensions();
        }

        $cacheKey = "domain_search_" . md5($domain . implode(',', $extensions));

        $results = Cache::remember($cacheKey, 300, function () use ($domain, $extensions) {
            return $this->goDaddyService->checkAvailability($domain, $extensions, 5);
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'results' => $results,
                'total_checked' => count($results)
            ]);
        }

        return view('domains.search', compact('results', 'domain', 'extensions'));
    }

    /**
     * Quick domain search endpoint - limited to 5 extensions
     */
    public function quickSearch(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|min:1|max:50'
        ]);

        $domain = strtolower(trim($request->domain));

        $cacheKey = "domain_quick_" . md5($domain);

        try {
            $results = Cache::remember($cacheKey, 300, function () use ($domain) {
                return $this->goDaddyService->quickCheckAvailability($domain);
            });

            return response()->json([
                'success' => true,
                'results' => $results,
                'domain' => $domain,
                'total_checked' => count($results)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat melakukan pencarian domain',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check specific extensions (max 5)
     */
    public function checkMultipleExtensions(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|min:1|max:50',
            'extensions' => 'required|array|max:5',
            'extensions.*' => 'string'
        ]);

        $domain = strtolower(trim($request->domain));
        $extensions = array_slice($request->extensions, 0, 5); // Hard limit ke 5

        $cacheKey = "domain_multi_" . md5($domain . implode(',', $extensions));

        try {
            $results = Cache::remember($cacheKey, 300, function () use ($domain, $extensions) {
                return $this->goDaddyService->checkAvailability($domain, $extensions, 5);
            });

            return response()->json([
                'success' => true,
                'results' => $results,
                'domain' => $domain,
                'total_checked' => count($results)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat melakukan pencarian domain',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function pricing()
    {
        $pricing = Cache::remember('domain_pricing', 3600, function () {
            return $this->goDaddyService->getPricing();
        });

        return view('domains.pricing', compact('pricing'));
    }

    public function selectDomain(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|string',
            'extension' => 'required|string'
        ]);

        $domainName = $request->domain_name;
        $extension = $request->extension;
        $fullDomain = $domainName . '.' . $extension;

        try {
            // Check availability with single extension for speed
            $availability = $this->goDaddyService->checkAvailability($domainName, [$extension]);

            if (empty($availability) || !$availability[0]['available']) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Domain tidak tersedia'
                    ], 400);
                }
                return redirect()->back()->with('error', 'Domain tidak tersedia');
            }

            // Store domain data in session for order process
            Session::put('selected_domain', [
                'domain_name' => $domainName,
                'extension' => $extension,
                'full_domain' => $fullDomain,
                'price' => $availability[0]['price'],
                'selected_at' => now()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Domain dipilih, mengarahkan ke halaman order...',
                    'redirect' => route('orders.create', ['step' => 2])
                ]);
            }

            return redirect()->route('orders.create', ['step' => 2])
                ->with('success', 'Domain ' . $fullDomain . ' telah dipilih. Silakan pilih template untuk melanjutkan.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memilih domain'
                ], 500);
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memilih domain');
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

    /**
     * Get limited popular extensions for frontend (hanya 10)
     */
    public function getPopularExtensions()
    {
        $extensions = array_slice($this->goDaddyService->getPopularExtensions(), 0, 10);
        return response()->json([
            'success' => true,
            'extensions' => $extensions
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
