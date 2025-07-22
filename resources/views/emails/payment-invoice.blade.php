{{-- resources/views/emails/payment-invoice.blade.php --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }
        .invoice-details {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .order-summary {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        .success-badge {
            background-color: #d4edda;
            color: #155724;
            padding: 8px 12px;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
        }
        .warning-box {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Successful!</h1>
        <p><strong>Invoice for Order #{{ $order->order_number }}</strong></p>
        <div class="success-badge">✓ PAID</div>
    </div>

    <div class="invoice-details">
        <h2>Invoice Details</h2>

        <table>
            <tr>
                <th>Order Number:</th>
                <td>{{ $order->order_number }}</td>
            </tr>
            <tr>
                <th>Payment Date:</th>
                <td>{{ $payment->paid_at->format('d M Y, H:i') }} WIB</td>
            </tr>
            <tr>
                <th>Payment Method:</th>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
            </tr>
            <tr>
                <th>Transaction ID:</th>
                <td>{{ $payment->transaction_id }}</td>
            </tr>
        </table>

        <h3>Customer Information</h3>
        <table>
            <tr>
                <th>Name:</th>
                <td>{{ $order->customer_data['name'] }}</td>
            </tr>
            <tr>
                <th>Email:</th>
                <td>{{ $order->customer_data['email'] }}</td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td>{{ $order->customer_data['phone'] }}</td>
            </tr>
            @if(isset($order->customer_data['address']))
            <tr>
                <th>Address:</th>
                <td>{{ $order->customer_data['address'] }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="order-summary">
        <h3>Order Summary</h3>
        <table>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th>Amount</th>
            </tr>
            <tr>
                <td>Template</td>
                <td>{{ $order->template->name ?? 'N/A' }}</td>
                <td>Rp {{ number_format($order->template_price, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Domain</td>
                <td>{{ $order->domain_name }}.{{ $order->domain_extension }}</td>
                <td>Rp {{ number_format($order->domain_price, 0, ',', '.') }}</td>
            </tr>
            @if($order->total_price < ($order->template_price + $order->domain_price))
            <tr>
                <td>Discount</td>
                <td>Promotional discount applied</td>
                <td style="color: green;">-Rp {{ number_format(($order->template_price + $order->domain_price) - $order->total_price, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="total-row">
                <td colspan="2"><strong>Total Amount</strong></td>
                <td><strong>Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="warning-box">
        <h4>📅 Template Delivery Information</h4>
        <p><strong>Important:</strong> Your website template will be delivered within <strong>7 working days</strong> from the payment confirmation.</p>
        <p>We will send you the template files and setup instructions to this email address once ready.</p>
        <p>Domain setup and configuration will also be completed during this period.</p>
    </div>

    <div class="invoice-details">
        <h3>What's Next?</h3>
        <ol>
            <li><strong>Template Preparation:</strong> Our team will customize your selected template</li>
            <li><strong>Domain Configuration:</strong> We'll set up your domain ({{ $order->domain_name }}.{{ $order->domain_extension }})</li>
            <li><strong>Quality Check:</strong> Final testing and optimization</li>
            <li><strong>Delivery:</strong> Template files and instructions sent via email</li>
        </ol>

        <p>You will receive regular updates about the progress of your order.</p>
    </div>

    <div class="footer">
        <p><strong>Thank you for your business!</strong></p>
        <p>If you have any questions about your order, please don't hesitate to contact our support team.</p>
        <p>This is an automated email. Please do not reply to this message.</p>

        <hr style="margin: 20px 0;">
        <p style="font-size: 12px; color: #888;">
            Invoice generated on {{ now()->format('d M Y, H:i') }} WIB<br>
            Order #{{ $order->order_number }} | Payment ID: {{ $payment->transaction_id }}
        </p>
    </div>
</body>
</html>
