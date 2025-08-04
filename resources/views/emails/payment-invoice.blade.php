<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Pembayaran</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.5;
            color: #1f2937;
            background-color: #f9fafb;
            padding: 40px 20px;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: #111827;
            color: white;
            padding: 48px 32px;
            text-align: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.025em;
        }

        .header p {
            opacity: 0.8;
            font-size: 15px;
            margin-bottom: 24px;
        }

        .success-badge {
            background-color: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
            letter-spacing: 0.05em;
        }

        .content {
            padding: 40px 32px;
        }

        .section {
            margin-bottom: 40px;
        }

        .section:last-child {
            margin-bottom: 0;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 24px;
            letter-spacing: -0.025em;
        }

        .info-grid {
            display: grid;
            gap: 16px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 500;
            color: #6b7280;
            font-size: 14px;
        }

        .info-value {
            font-weight: 500;
            color: #111827;
            text-align: right;
        }

        .order-summary {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 32px;
            border: 1px solid #f3f4f6;
        }

        .order-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-info h4 {
            font-size: 15px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 4px;
        }

        .item-info p {
            font-size: 14px;
            color: #6b7280;
        }

        .item-price {
            font-weight: 600;
            color: #111827;
        }

        .discount-price {
            color: #10b981;
        }

        .total-row {
            background-color: #111827;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-top: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 16px;
            font-weight: 600;
        }

        .total-price {
            font-size: 18px;
            font-weight: 700;
        }

        .notice-box {
            background-color: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 24px;
            border-left: 4px solid #f59e0b;
        }

        .notice-title {
            font-size: 16px;
            font-weight: 600;
            color: #92400e;
            margin-bottom: 12px;
        }

        .notice-box p {
            color: #92400e;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 8px;
        }

        .notice-box p:last-child {
            margin-bottom: 0;
        }

        .highlight {
            background-color: #92400e;
            color: #fef3c7;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }

        .steps {
            background-color: #f8fafc;
            border-radius: 12px;
            padding: 24px;
        }

        .steps ol {
            list-style: none;
            counter-reset: step-counter;
        }

        .steps li {
            counter-increment: step-counter;
            margin-bottom: 16px;
            padding-left: 40px;
            position: relative;
            color: #4b5563;
            line-height: 1.6;
        }

        .steps li:last-child {
            margin-bottom: 0;
        }

        .steps li::before {
            content: counter(step-counter);
            position: absolute;
            left: 0;
            top: 0;
            background-color: #111827;
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
        }

        .steps strong {
            color: #111827;
        }

        .footer {
            background-color: #f9fafb;
            padding: 32px;
            border-top: 1px solid #f3f4f6;
            text-align: center;
        }

        .footer p {
            color: #6b7280;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .footer p:first-child {
            color: #111827;
            font-weight: 600;
            font-size: 15px;
        }

        .footer-divider {
            width: 60px;
            height: 1px;
            background-color: #d1d5db;
            margin: 24px auto;
        }

        .footer-meta {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.5;
        }

        @media (max-width: 640px) {
            body {
                padding: 20px 16px;
            }

            .header {
                padding: 32px 24px;
            }

            .content {
                padding: 32px 24px;
            }

            .order-summary {
                padding: 24px;
            }

            .footer {
                padding: 24px;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .info-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Pembayaran Berhasil</h1>
            <p>Invoice untuk Pesanan #{{ $order->order_number }}</p>
            <div class="success-badge">✓ LUNAS</div>
        </div>

        <div class="content">
            <div class="section">
                <h2 class="section-title">Detail Pembayaran</h2>
                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Nomor Pesanan</span>
                        <span class="info-value">{{ $order->order_number }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Pembayaran</span>
                        <span class="info-value">{{ $payment->paid_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Metode Pembayaran</span>
                        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">ID Transaksi</span>
                        <span class="info-value">{{ $payment->transaction_id }}</span>
                    </div>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Informasi Pelanggan</h2>
                <div class="info-grid">
                    <div class="info-row">
                        <span class="info-label">Nama</span>
                        <span class="info-value">{{ $order->customer_data['name'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $order->customer_data['email'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Telepon</span>
                        <span class="info-value">{{ $order->customer_data['phone'] }}</span>
                    </div>
                    @if(isset($order->customer_data['address']))
                    <div class="info-row">
                        <span class="info-label">Alamat</span>
                        <span class="info-value">{{ $order->customer_data['address'] }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Ringkasan Pesanan</h2>
                <div class="order-summary">
                    <div class="order-item">
                        <div class="item-info">
                            <h4>Template</h4>
                            <p>{{ $order->template->name ?? 'N/A' }}</p>
                        </div>
                        <div class="item-price">Rp {{ number_format($order->template_price, 0, ',', '.') }}</div>
                    </div>

                    <div class="order-item">
                        <div class="item-info">
                            <h4>Domain</h4>
                            <p>{{ $order->domain_name }}.{{ $order->domain_extension }}</p>
                        </div>
                        <div class="item-price">Rp {{ number_format($order->domain_price, 0, ',', '.') }}</div>
                    </div>

                    @if($order->total_price < ($order->template_price + $order->domain_price))
                    <div class="order-item">
                        <div class="item-info">
                            <h4>Diskon</h4>
                            <p>Diskon promosi diterapkan</p>
                        </div>
                        <div class="item-price discount-price">-Rp {{ number_format(($order->template_price + $order->domain_price) - $order->total_price, 0, ',', '.') }}</div>
                    </div>
                    @endif

                    <div class="total-row">
                        <span class="total-label">Total Pembayaran</span>
                        <span class="total-price">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="notice-box">
                    <div class="notice-title">📅 Informasi Pengiriman Template</div>
                    <p><strong>Penting:</strong> Template website Anda akan dikirim dalam waktu <span class="highlight">7 hari kerja</span> setelah konfirmasi pembayaran.</p>
                    <p>Kami akan mengirimkan file template dan petunjuk setup ke alamat email ini setelah siap.</p>
                    <p>Setup dan konfigurasi domain juga akan diselesaikan dalam periode ini.</p>
                </div>
            </div>

            <div class="section">
                <h2 class="section-title">Langkah Selanjutnya</h2>
                <div class="steps">
                    <ol>
                        <li><strong>Persiapan Template:</strong> Tim kami akan menyesuaikan template yang Anda pilih</li>
                        <li><strong>Konfigurasi Domain:</strong> Kami akan mengatur domain Anda ({{ $order->domain_name }}.{{ $order->domain_extension }})</li>
                        <li><strong>Pengecekan Kualitas:</strong> Testing dan optimisasi akhir</li>
                        <li><strong>Pengiriman:</strong> File template dan petunjuk dikirim via email</li>
                    </ol>
                </div>
                <p style="margin-top: 20px; color: #6b7280; font-size: 14px;">Anda akan menerima update berkala tentang progress pesanan Anda.</p>
            </div>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda!</p>
            <p>Jika Anda memiliki pertanyaan tentang pesanan ini, jangan ragu untuk menghubungi tim support kami.</p>
            <p>Ini adalah email otomatis. Mohon jangan membalas pesan ini.</p>

            <div class="footer-divider"></div>

            <div class="footer-meta">
                Invoice dibuat pada {{ now()->format('d M Y, H:i') }} WIB<br>
                Pesanan #{{ $order->order_number }} | ID Pembayaran: {{ $payment->transaction_id }}
            </div>
        </div>
    </div>
</body>
</html>
