<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            size: A4;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #334155;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .receipt-container {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .side-accent {
            position: absolute;
            top: 0;
            left: 0;
            width: 12px;
            height: 100%;
            background-color: {{ \App\Services\SettingsService::get('brand_primary_color', '#1e293b') }};
            z-index: 10;
        }
        .content-wrapper {
            padding: 40px 40px 40px 60px;
            position: relative;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .header-title {
            font-size: 24px;
            font-weight: bold;
            color: {{ \App\Services\SettingsService::get('brand_primary_color', '#1e293b') }};
            text-transform: uppercase;
            margin: 0;
        }
        .header-subtitle {
            font-size: 14px;
            color: #64748b;
            margin: 5px 0 0 0;
        }
        .receipt-meta {
            text-align: right;
            font-size: 12px;
            color: #64748b;
        }
        .profile-section {
            width: 100%;
            margin-bottom: 30px;
        }
        .passport-frame {
            width: 110px;
            height: 130px;
            border: 1px solid #e2e8f0;
            padding: 4px;
            background: #fff;
            display: inline-block;
            vertical-align: middle;
        }
        .passport-img {
            width: 100px;
            height: 120px;
            object-fit: cover;
        }
        .student-details {
            display: inline-block;
            vertical-align: middle;
            margin-left: 20px;
            width: 70%;
        }
        .detail-row {
            margin-bottom: 8px;
        }
        .detail-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            font-weight: bold;
            display: block;
        }
        .detail-value {
            font-size: 15px;
            color: #1e293b;
            font-weight: bold;
        }
        .payment-grid {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        .payment-grid td {
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }
        .payment-label {
            font-weight: bold;
            color: #64748b;
            width: 30%;
        }
        .total-box {
            background-color: {{ \App\Services\SettingsService::get('brand_primary_color', '#1e293b') }};
            color: #ffffff;
            padding: 20px;
            text-align: right;
            border-radius: 6px;
            margin-top: 20px;
        }
        .total-label {
            font-size: 12px;
            text-transform: uppercase;
            opacity: 0.8;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            display: block;
        }
        .verification-area {
            margin-top: 40px;
            border-top: 1px solid #e2e8f0;
            padding-top: 20px;
            width: 100%;
        }
        .qr-wrapper {
            float: right;
            text-align: center;
        }
        .qr-url {
            float: left;
            font-size: 9px;
            color: #94a3b8;
            width: 60%;
            word-break: break-all;
            padding-top: 20px;
        }
        .signature-section {
            margin-top: 60px;
            width: 100%;
            clear: both;
        }
        .sig-box {
            width: 45%;
            display: inline-block;
            text-align: center;
            vertical-align: top;
        }
        .sig-line {
            border-top: 1px solid #334155;
            margin-top: 40px;
            padding-top: 8px;
            font-weight: bold;
            font-size: 13px;
            color: #1e293b;
        }
        .sig-title {
            font-size: 11px;
            color: #64748b;
            margin-bottom: 5px;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="side-accent"></div>
        <div class="content-wrapper">
            <!-- Header -->
            <table class="header-table">
                <tr style="border-collapse: collapse;">
                    <td style="vertical-align: middle; width: 80px;">
                        @if($logo)
                            @php
                                // If logo is a relative path from the public disk, convert to absolute path for dompdf
                                $logoPath = str_contains($logo, 'http') ? $logo : storage_path('app/public/' . $logo);
                            @endphp
                            <img src="{{ $logoPath }}" style="max-width: 70px; max-height: 70px; object-fit: contain;">
                        @endif
                    </td>
                    <td style="vertical-align: middle; padding-left: 15px;">
                        <h1 class="header-title">{{ $institution }}</h1>
                        <p class="header-subtitle">Official Student Union Government Receipt</p>
                    </td>
                    <td class="receipt-meta" style="vertical-align: middle;">
                        <strong>Receipt No:</strong> {{ $receipt->receipt_no }}<br>
                        <strong>Date:</strong> {{ $receipt->issued_at->format('d M, Y H:i') }}
                    </td>
                </tr>
            </table>

            <!-- Profile Section -->
            <div class="profile-section">
                <div class="passport-frame">
                    @if($passport)
                        <img src="{{ $passport }}" class="passport-img">
                    @else
                        <div style="width: 100px; height: 120px; background: #f1f5f9; text-align: center; font-size: 9px; color: #94a3b8; padding-top: 40px;">
                            No Photo
                        </div>
                    @endif
                </div>
                <div class="student-details">
                    <div class="detail-row">
                        <span class="detail-label">Student Full Name</span>
                        <span class="detail-value">{{ $student->user->name }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Matriculation Number</span>
                        <span class="detail-value">{{ $student->matric_no }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Department</span>
                        <span class="detail-value">{{ $student->department->name }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <table class="payment-grid">
                <tr>
                    <td class="payment-label">Fee Description</td>
                    <td style="font-weight: bold; color: #1e293b;">{{ $fee->name }}</td>
                </tr>
                <tr>
                    <td class="payment-label">Transaction Reference</td>
                    <td style="font-family: monospace; color: #64748b;">{{ $payment->transaction_ref }}</td>
                </tr>
            </table>

            <div class="total-box">
                <span class="total-label">Total Amount Paid</span>
                <span class="total-amount">₦{{ number_format($payment->amount, 2) }}</span>
            </div>

            <!-- Verification -->
            <div class="verification-area">
                <div class="qr-wrapper">
                    <div style="background: white; padding: 5px; display: inline-block;">
                        {!! $barcode !!}
                    </div>
                    <div style="font-size: 8px; color: #94a3b8; margin-top: 5px;">Official Barcode</div>
                </div>
                <div class="qr-url">
                    <strong style="font-size: 9px; color: #64748b;">Verification Link:</strong><br>
                    {{ $verificationUrl }}
                </div>
            </div>

            <!-- Signatures -->
            <div class="signature-section">
                <div class="sig-box" style="float: left;">
                    <div class="sig-title">Verified By:</div>
                    <div class="sig-line">SUG Treasurer</div>
                </div>
                <div class="sig-box" style="float: right;">
                    <div class="sig-title">Approved By:</div>
                    <div class="sig-line">SUG President</div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <div class="footer">
                This is a computer-generated document. No signature is required for digital verification.
            </div>
        </div>
    </div>
</body>
</html>