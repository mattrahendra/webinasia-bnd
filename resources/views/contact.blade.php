@extends('layouts.app')

@section('title', 'Hubungi Kami - Webinasia')

@section('content')
    <div class="max-w-6xl mx-auto py-16 px-4">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Hubungi Kami</h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Siap memulai transformasi digital untuk bisnis Anda? Tim ahli kami siap membantu mewujudkan website impian Anda!
            </p>
        </div>

        <div class="grid lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-envelope text-blue-500 mr-2"></i>Kirim Pesan
                </h2>
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-2">Nama Lengkap*</label>
                            <input type="text" name="name" id="name"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="Masukkan nama lengkap Anda" required>
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-medium mb-2">No. WhatsApp*</label>
                            <input type="tel" name="phone" id="phone"
                                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                   placeholder="08XXXXXXXXXX" required>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-gray-700 font-medium mb-2">Email*</label>
                        <input type="email" name="email" id="email"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               placeholder="nama@email.com" required>
                    </div>

                    <div>
                        <label for="business_type" class="block text-gray-700 font-medium mb-2">Jenis Bisnis</label>
                        <select name="business_type" id="business_type"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih jenis bisnis Anda</option>
                            <option value="umkm">UMKM</option>
                            <option value="toko_online">Toko Online</option>
                            <option value="jasa">Jasa</option>
                            <option value="restaurant">Restaurant/Kuliner</option>
                            <option value="fashion">Fashion</option>
                            <option value="kesehatan">Kesehatan & Kecantikan</option>
                            <option value="pendidikan">Pendidikan</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label for="service" class="block text-gray-700 font-medium mb-2">Layanan yang Diinginkan</label>
                        <select name="service" id="service"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih layanan</option>
                            <option value="website_profil">Website Profil Bisnis</option>
                            <option value="website_ecommerce">Website E-Commerce</option>
                            <option value="landing_page">Landing Page</option>
                            <option value="desain_grafis">Desain Grafis</option>
                            <option value="seo">Optimasi SEO</option>
                            <option value="konsultasi">Konsultasi Digital</option>
                            <option value="paket_lengkap">Paket Lengkap</option>
                        </select>
                    </div>

                    <div>
                        <label for="budget" class="block text-gray-700 font-medium mb-2">Estimasi Budget</label>
                        <select name="budget" id="budget"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                            <option value="">Pilih range budget</option>
                            <option value="1-3jt">Rp 1-3 Juta</option>
                            <option value="3-5jt">Rp 3-5 Juta</option>
                            <option value="5-10jt">Rp 5-10 Juta</option>
                            <option value="10jt+">Rp 10 Juta+</option>
                            <option value="diskusi">Perlu Diskusi</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-gray-700 font-medium mb-2">Pesan & Kebutuhan Khusus*</label>
                        <textarea name="message" id="message" rows="5"
                                  class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                                  placeholder="Ceritakan tentang bisnis Anda dan kebutuhan website yang diinginkan..." required></textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                        <i class="fas fa-paper-plane mr-2"></i>Kirim Pesan
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="space-y-8">
                <!-- Quick Contact -->
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white rounded-lg p-8">
                    <h3 class="text-2xl font-bold mb-6">Kontak Langsung</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                <i class="fab fa-whatsapp text-2xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold">WhatsApp</p>
                                <a href="https://wa.me/6281234567890" class="text-blue-200 hover:text-white transition duration-200">+62 812-3456-7890</a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold">Email</p>
                                <a href="mailto:info@webinasia.com" class="text-blue-200 hover:text-white transition duration-200">info@webinasia.com</a>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold">Jam Operasional</p>
                                <p class="text-blue-200">Senin - Jumat: 09:00 - 18:00 WIB</p>
                                <p class="text-blue-200">Sabtu: 09:00 - 15:00 WIB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Why Choose Us -->
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Mengapa Memilih Webinasia?</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Konsultasi Gratis</h4>
                                <p class="text-gray-600 text-sm">Diskusi kebutuhan website tanpa biaya</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Harga Terjangkau</h4>
                                <p class="text-gray-600 text-sm">Solusi digital berkualitas untuk UMKM</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Support 24/7</h4>
                                <p class="text-gray-600 text-sm">Tim support siap membantu kapan saja</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-check text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Garansi Kepuasan</h4>
                                <p class="text-gray-600 text-sm">Revisi hingga Anda puas dengan hasilnya</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="bg-gray-50 rounded-lg p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-pink-600 rounded-full flex items-center justify-center text-white hover:bg-pink-700 transition duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-400 rounded-full flex items-center justify-center text-white hover:bg-blue-500 transition duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-blue-800 rounded-full flex items-center justify-center text-white hover:bg-blue-900 transition duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Pertanyaan Umum</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">Berapa lama proses pembuatan website?</h4>
                    <p class="text-gray-600">Waktu pembuatan website biasanya 7-14 hari kerja, tergantung kompleksitas dan kebutuhan fitur.</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">Apakah ada biaya maintenance?</h4>
                    <p class="text-gray-600">Kami menyediakan free maintenance selama 3 bulan pertama, selanjutnya dengan biaya yang sangat terjangkau.</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">Apakah website mobile-friendly?</h4>
                    <p class="text-gray-600">Ya, semua website yang kami buat sudah responsive dan mobile-friendly untuk semua perangkat.</p>
                </div>
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-3">Bagaimana sistem pembayaran?</h4>
                    <p class="text-gray-600">Pembayaran dapat dilakukan dengan sistem DP 50% di awal, dan pelunasan setelah website selesai.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
