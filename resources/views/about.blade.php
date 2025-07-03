@extends('layouts.app')

@section('title', 'Tentang Kami - Webinasia')

@section('content')
    <div class="max-w-6xl mx-auto py-16 px-4">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Tentang Webinasia</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Platform penyedia jasa pembuatan website yang dirancang khusus untuk mendukung pelaku UMKM di era digital
            </p>
        </div>

        <!-- Profile Section -->
        <div class="grid md:grid-cols-2 gap-12 mb-16">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Profil Kami</h2>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Webinasia adalah <strong>platform penyedia jasa pembuatan website</strong> yang dirancang khusus untuk mendukung <strong>pelaku UMKM</strong> di era digital. Kami hadir sebagai mitra strategis yang menyediakan <strong>website profesional, desain grafis modern, dan layanan digital terintegrasi</strong> untuk membantu UMKM berkembang dan bersaing secara online.
                </p>
                <p class="text-gray-600 leading-relaxed">
                    Kami memahami tantangan yang dihadapi oleh UMKM, mulai dari keterbatasan sumber daya, akses teknologi, hingga minimnya visibilitas di dunia digital. Webinasia hadir untuk menjadi solusi yang tepat bagi bisnis Anda.
                </p>
            </div>
            <div class="bg-blue-50 p-8 rounded-lg">
                <h3 class="text-2xl font-bold text-blue-900 mb-4">Mengapa Webinasia Hadir?</h3>
                <ul class="space-y-3 text-gray-700">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3"></i>
                        <span>Meningkatkan visibilitas online melalui website yang menarik dan fungsional</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3"></i>
                        <span>Mendukung pertumbuhan bisnis dengan strategi digital yang tepat sasaran</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-blue-500 mt-1 mr-3"></i>
                        <span>Menyediakan layanan terjangkau tanpa mengorbankan kualitas</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Vision & Mission -->
        <div class="grid md:grid-cols-2 gap-8 mb-16">
            <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white p-8 rounded-lg">
                <h3 class="text-2xl font-bold mb-4">
                    <i class="fas fa-eye mr-2"></i>Visi Kami
                </h3>
                <p class="text-blue-100 leading-relaxed">
                    Menjadi mitra terpercaya bagi UMKM dalam meraih kesuksesan digital, melalui layanan website dan desain digital yang berkualitas dan mudah diakses.
                </p>
            </div>
            <div class="bg-gradient-to-br from-green-600 to-green-800 text-white p-8 rounded-lg">
                <h3 class="text-2xl font-bold mb-4">
                    <i class="fas fa-target mr-2"></i>Misi Kami
                </h3>
                <ul class="space-y-2 text-green-100">
                    <li>• Memberikan solusi pembuatan website yang profesional dan modern</li>
                    <li>• Menyediakan layanan yang terjangkau dan transparan bagi UMKM</li>
                    <li>• Mendorong pertumbuhan dan daya saing UMKM di era digital</li>
                    <li>• Menjadi partner yang selalu peduli dan memahami kebutuhan unik setiap bisnis</li>
                </ul>
            </div>
        </div>

        <!-- Services Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Layanan yang Ditawarkan</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-blue-500">
                    <div class="text-blue-500 text-3xl mb-4">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Pembuatan Website</h4>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Website profesional & responsif</li>
                        <li>• Desain custom sesuai identitas bisnis</li>
                        <li>• Website e-commerce & landing page</li>
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-purple-500">
                    <div class="text-purple-500 text-3xl mb-4">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Desain Grafis</h4>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Desain visual untuk branding</li>
                        <li>• Konten visual media sosial</li>
                        <li>• Konsultasi desain identitas bisnis</li>
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-green-500">
                    <div class="text-green-500 text-3xl mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Pengoptimalan SEO</h4>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Audit SEO & strategi optimasi</li>
                        <li>• Konten ramah mesin pencari</li>
                        <li>• Meningkatkan peringkat Google</li>
                    </ul>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-lg border-t-4 border-orange-500">
                    <div class="text-orange-500 text-3xl mb-4">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-3">Konsultasi & Dukungan</h4>
                    <ul class="text-gray-600 text-sm space-y-1">
                        <li>• Konsultasi strategi digital</li>
                        <li>• Maintenance & pembaruan</li>
                        <li>• Support ramah dan cepat</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Commitment Section -->
        <div class="bg-gray-50 rounded-lg p-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-8">Komitmen Kami</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-white text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Kualitas & Kepuasan</h4>
                    <p class="text-gray-600">Setiap UMKM berhak memiliki website berkualitas tinggi tanpa harus merogoh kocek dalam</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Harga Terjangkau</h4>
                    <p class="text-gray-600">Layanan premium dengan biaya yang bersahabat untuk UMKM</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-purple-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Pendekatan Personal</h4>
                    <p class="text-gray-600">Setiap klien adalah mitra kami. Kami selalu mendengarkan dan memberikan solusi yang sesuai</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="text-center mt-16">
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Siap Memulai Transformasi Digital?</h3>
            <p class="text-gray-600 mb-8">Mari wujudkan website impian untuk bisnis Anda bersama Webinasia</p>
            <a href="{{ route('contact') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
                <i class="fas fa-phone mr-2"></i>Hubungi Kami Sekarang
            </a>
        </div>
    </div>
@endsection
