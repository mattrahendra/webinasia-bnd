<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;
use App\Services\GoDaddyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    protected $goDaddyService;

    public function __construct(GoDaddyService $goDaddyService)
    {
        $this->goDaddyService = $goDaddyService;
    }

    public function index()
    {
        // Get featured domains for highlight section
        $featuredDomains = Cache::remember('home_featured_domains', 3600, function () {
            return array_slice($this->goDaddyService->getFeaturedDomains(), 0, 3);
        });

        // Get categories with template count for tabs
        $categories = Cache::remember('home_categories', 3600, function () {
            return Category::withCount('templates')->get();
        });

        // Get featured templates (latest 6 templates)
        $featuredTemplates = Cache::remember('home_featured_templates', 3600, function () {
            return Template::with(['category', 'images'])
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        });

        // Get pricing packages
        $pricingPackages = Cache::remember('home_pricing_packages', 3600, function () {
            return array_slice($this->goDaddyService->getPricing(), 0, 3);
        });

        // Why choose us content
        $whyChooseUs = [
            [
                'icon' => 'fas fa-rocket',
                'title' => 'Fast Setup',
                'description' => 'Get your website up and running in minutes with our streamlined process.'
            ],
            [
                'icon' => 'fas fa-shield-alt',
                'title' => 'Secure Hosting',
                'description' => 'Industry-leading security measures to keep your website safe and protected.'
            ],
            [
                'icon' => 'fas fa-headset',
                'title' => '24/7 Support',
                'description' => 'Our dedicated support team is available round-the-clock to assist you.'
            ]
        ];

        return view('home', compact(
            'featuredDomains',
            'featuredTemplates',
            'categories',
            'pricingPackages',
            'whyChooseUs'
        ));
    }
}
