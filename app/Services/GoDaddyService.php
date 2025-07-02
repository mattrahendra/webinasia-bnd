<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoDaddyService
{
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;
    protected $isSandbox;

    // Kurs USD ke IDR (biasanya diupdate dari API atau database)
    protected $usdToIdr = 16000;

    public function __construct()
    {
        $this->apiKey = config('services.godaddy.api_key');
        $this->apiSecret = config('services.godaddy.api_secret');
        $this->isSandbox = config('services.godaddy.sandbox', true);
        $this->baseUrl = $this->isSandbox
            ? 'https://api.ote-godaddy.com'
            : 'https://api.godaddy.com';
    }

    /**
     * Get all available extensions
     */
    public function getAvailableExtensions(): array
    {
        return [
            'com',
            'net',
            'org',
            'info',
            'biz',
            'co',
            'me',
            'tv',
            'cc',
            'ws',
            'id',
            'co.id',
            'web.id',
            'my.id',
            'biz.id',
            'or.id',
            'ac.id',
            'sch.id',
            'asia',
            'mobi',
            'name',
            'pro',
            'travel',
            'jobs',
            'cat',
            'tel',
            'xxx',
            'ly',
            'am',
            'fm',
            'io',
            'ai',
            'co.uk',
            'org.uk',
            'me.uk',
            'de',
            'fr',
            'it',
            'es',
            'nl',
            'be',
            'ch',
            'at',
            'pl',
            'cz',
            'com.au',
            'net.au',
            'org.au',
            'jp',
            'cn',
            'in',
            'sg',
            'my'
        ];
    }

    /**
     * Convert USD to IDR
     */
    private function convertToIdr($usdPrice): int
    {
        return (int) round($usdPrice * $this->usdToIdr);
    }

    /**
     * Format price to IDR
     */
    private function formatPrice($usdPrice): string
    {
        $idrPrice = $this->convertToIdr($usdPrice);
        return 'Rp ' . number_format($idrPrice, 0, ',', '.');
    }

    /**
     * Get featured domains for homepage
     */
    public function getFeaturedDomains(): array
    {
        $featuredExtensions = ['com', 'net', 'org', 'id', 'co.id', 'web.id', 'info', 'biz'];
        $featured = [];

        $defaultPricing = [
            'com' => 12.99,
            'net' => 14.99,
            'org' => 13.99,
            'info' => 11.99,
            'biz' => 15.99,
            'id' => 25.00,
            'co.id' => 15.00,
            'web.id' => 18.00
        ];

        foreach ($featuredExtensions as $ext) {
            $usdPrice = $defaultPricing[$ext] ?? 15.99;
            $featured[] = [
                'extension' => $ext,
                'price_usd' => $usdPrice,
                'price_idr' => $this->convertToIdr($usdPrice),
                'price_formatted' => $this->formatPrice($usdPrice),
                'description' => $this->getExtensionDescription($ext),
                'popular' => in_array($ext, ['com', 'net', 'org'])
            ];
        }

        return $featured;
    }

    /**
     * Check domain availability
     */
    public function checkAvailability(string $domain, array $extensions): array
    {

        $results = [];

        foreach ($extensions as $extension) {
            $fullDomain = $domain . '.' . $extension;

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'sso-key ' . $this->apiKey . ':' . $this->apiSecret,
                    'Content-Type' => 'application/json'
                ])->get($this->baseUrl . '/v1/domains/available', [
                    'domain' => $fullDomain,
                    'checkType' => 'FAST'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $rawPrice = $data['price'] ?? 0;
                    $usd = $rawPrice / 1000000;
                    $idr = $this->convertToIdr($usd); // GoDaddy API returns price in cents

                    $results[] = [
                        'domain' => $fullDomain,
                        'extension' => $extension,
                        'available' => $data['available'],
                        'price_usd' => $usd,
                        'price_idr' => $idr,
                        'price_formatted' => $this->formatPrice($usd),
                        'price' => $idr
                    ];
                } else {
                    $results[] = [
                        'domain' => $fullDomain,
                        'extension' => $extension,
                        'available' => false,
                        'error' => 'API Error: ' . $response->status()
                    ];
                }
            } catch (\Exception $e) {
                Log::error('GoDaddy API Error: ' . $e->getMessage());
                $usdPrice = $this->getDefaultPrice($extension);

                $results[] = [
                    'domain' => $fullDomain,
                    'extension' => $extension,
                    'available' => true, // fallback to true for demo
                    'price_usd' => $usdPrice,
                    'price_idr' => $this->convertToIdr($usdPrice),
                    'price_formatted' => $this->formatPrice($usdPrice),
                    'price' => $this->convertToIdr($usdPrice),
                    'fallback' => true
                ];
            }
        }

        return $results;

    }

    /**
     * Get domain pricing
     */
    public function getPricing(): array
    {
        $extensions = $this->getAvailableExtensions();
        $pricing = [];

        $defaultPricing = [
            'com' => 12.99,
            'net' => 14.99,
            'org' => 13.99,
            'info' => 11.99,
            'biz' => 15.99,
            'co' => 16.99,
            'me' => 19.99,
            'tv' => 24.99,
            'cc' => 12.99,
            'ws' => 14.99,
            'id' => 25.00,
            'co.id' => 15.00,
            'web.id' => 18.00,
            'my.id' => 20.00,
            'biz.id' => 18.00,
            'or.id' => 15.00,
            'ac.id' => 20.00,
            'sch.id' => 20.00,
            'asia' => 16.99,
            'mobi' => 18.99,
            'name' => 12.99,
            'pro' => 19.99,
            'ly' => 75.00,
            'am' => 35.00,
            'fm' => 89.00,
            'io' => 59.00,
            'ai' => 99.00,
            'co.uk' => 8.99,
            'org.uk' => 8.99,
            'me.uk' => 8.99,
            'de' => 9.99,
            'fr' => 12.99,
            'it' => 15.99,
            'es' => 10.99,
            'nl' => 12.99
        ];

        foreach ($extensions as $ext) {
            $usdPrice = $defaultPricing[$ext] ?? 15.99;
            $pricing[] = [
                'extension' => $ext,
                'price_usd' => $usdPrice,
                'price_idr' => $this->convertToIdr($usdPrice),
                'price_formatted' => $this->formatPrice($usdPrice),
                'description' => $this->getExtensionDescription($ext)
            ];
        }

        // Sort by popularity and price
        usort($pricing, function ($a, $b) {
            $popular = ['com', 'net', 'org', 'id', 'co.id'];
            $aPopular = in_array($a['extension'], $popular);
            $bPopular = in_array($b['extension'], $popular);

            if ($aPopular && !$bPopular) return -1;
            if (!$aPopular && $bPopular) return 1;

            return $a['price_idr'] <=> $b['price_idr'];
        });

        return $pricing;
    }

    /**
     * Get default price for extension
     */
    private function getDefaultPrice(string $extension): float
    {
        $prices = [
            'com' => 12.99,
            'net' => 14.99,
            'org' => 13.99,
            'info' => 11.99,
            'id' => 25.00,
            'co.id' => 15.00,
            'web.id' => 18.00
        ];

        return $prices[$extension] ?? 15.99;
    }

    /**
     * Get extension description
     */
    private function getExtensionDescription(string $extension): string
    {
        $descriptions = [
            'com' => 'Domain paling populer di dunia',
            'net' => 'Cocok untuk jaringan dan teknologi',
            'org' => 'Ideal untuk organisasi',
            'info' => 'Untuk situs informasi',
            'biz' => 'Untuk bisnis dan perusahaan',
            'id' => 'Domain Indonesia',
            'co.id' => 'Domain komersial Indonesia',
            'web.id' => 'Domain web Indonesia',
            'my.id' => 'Domain personal Indonesia',
            'co' => 'Alternatif .com yang populer',
            'me' => 'Domain personal dan blog',
            'tv' => 'Untuk media dan video',
            'io' => 'Populer untuk startup tech',
            'ai' => 'Untuk artificial intelligence',
            'ly' => 'Domain Libya, populer untuk URL pendek',
            'co.uk' => 'Domain Inggris',
            'de' => 'Domain Jerman',
            'fr' => 'Domain Prancis',
            'asia' => 'Domain regional Asia'
        ];

        return $descriptions[$extension] ?? 'Ekstensi domain';
    }

    /**
     * Purchase domain (for order completion)
     */
    public function purchaseDomain(string $domain, array $customerData): array
    {
        // Implementasi pembelian domain melalui GoDaddy API
        // Untuk demo, return success
        return [
            'success' => true,
            'domain' => $domain,
            'order_id' => 'GD-' . uniqid(),
            'message' => 'Domain berhasil didaftarkan'
        ];
    }
}
