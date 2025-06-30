<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;


class GoDaddyService
{
    protected $apiKey;
    protected $apiSecret;
    protected $baseUrl;
    protected $isSandbox;

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
                    $results[] = [
                        'domain' => $fullDomain,
                        'extension' => $extension,
                        'available' => $data['available'],
                        'price' => $data['price'] ?? 0,
                        'renewal_price' => $data['price'] ?? 0, // GoDaddy usually same price
                        'currency' => $data['currency'] ?? 'USD'
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
                $results[] = [
                    'domain' => $fullDomain,
                    'extension' => $extension,
                    'available' => false,
                    'error' => 'Service temporarily unavailable'
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
        try {
            $response = Http::withHeaders([
                'Authorization' => 'sso-key ' . $this->apiKey . ':' . $this->apiSecret,
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . '/v1/domains/tlds');

            if ($response->successful()) {
                $tlds = $response->json();
                $pricing = [];

                foreach ($tlds as $tld) {
                    if (in_array($tld['name'], ['com', 'net', 'org', 'id', 'co.id', 'web.id'])) {
                        $pricing[] = [
                            'extension' => $tld['name'],
                            'price' => $tld['price'] ?? 0,
                            'currency' => 'USD'
                        ];
                    }
                }

                return $pricing;
            }
        } catch (\Exception $e) {
            Log::error('GoDaddy Pricing API Error: ' . $e->getMessage());
        }

        // Fallback pricing if API fails
        return [
            ['extension' => 'com', 'price' => 12.99, 'currency' => 'USD'],
            ['extension' => 'net', 'price' => 14.99, 'currency' => 'USD'],
            ['extension' => 'org', 'price' => 14.99, 'currency' => 'USD'],
            ['extension' => 'id', 'price' => 25.00, 'currency' => 'USD'],
        ];
    }

    /**
     * Purchase domain (called after payment success)
     */
    public function purchaseDomain(string $domain, array $customerData): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'sso-key ' . $this->apiKey . ':' . $this->apiSecret,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/v1/domains/purchase', [
                'domain' => $domain,
                'period' => 1, // 1 year
                'nameServers' => [
                    'ns1.example.com', // Your hosting nameservers
                    'ns2.example.com'
                ],
                'renewAuto' => false,
                'privacy' => true,
                'consent' => [
                    'agreementKeys' => ['DNRA'],
                    'agreedBy' => $customerData['email'],
                    'agreedAt' => now()->toISOString()
                ],
                'contactAdmin' => $customerData,
                'contactBilling' => $customerData,
                'contactRegistrant' => $customerData,
                'contactTech' => $customerData
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];
        } catch (\Exception $e) {
            Log::error('GoDaddy Purchase Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
