<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt - {{ $payment->id }}</title>
    <style>
        @page { margin: 0; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
            background-color: #fff;
        }
        .receipt-container {
            width: 100%;
            min-height: 100vh;
            border: 10px solid #f8fafc;
            box-sizing: border-box;
            padding: 0;
            position: relative;
        }
        .header-banner {
            background-color: #4f46e5;
            color: #ffffff;
            padding: 20px 30px;
            text-align: center;
            position: relative;
        }
        .header-banner h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header-banner p {
            margin: 5px 0 0;
            font-size: 12px;
            opacity: 0.9;
        }
        .logo-container {
            position: absolute;
            top: 15px;
            left: 20px;
            width: 50px;
            height: 50px;
            background: #fff;
            border-radius: 8px;
            padding: 3px;
            display: block;
        }
        .logo-container img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .passport-frame {
            position: absolute;
            top: 15px;
            right: 20px;
            width: 70px;
            height: 70px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            overflow: hidden;
            background: #fff;
        }
        .passport-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content {
            padding: 30px 40px;
        }
        .status-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-title-badge {
            display: inline-block;
            background-color: #f1f5f9;
            color: #4f46e5;
            padding: 6px 20px;
            border-radius: 50px;
            font-weight: bold;
            font-size: 16px;
            border: 2px solid #4f46e5;
            text-transform: uppercase;
        }
        .amount-display {
            text-align: center;
            margin-bottom: 25px;
        }
        .amount-display .label {
            font-size: 13px;
            color: #64748b;
            text-transform: uppercase;
        }
        .amount-display .value {
            font-size: 30px;
            font-weight: bold;
            color: #4f46e5;
            display: block;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .info-grid td {
            padding: 10px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }
        .info-grid .label {
            color: #64748b;
            font-weight: normal;
            width: 35%;
        }
        .info-grid .value {
            font-weight: bold;
            color: #1e293b;
            text-align: right;
        }
        .footer-section {
            margin-top: 40px;
            border-top: 2px solid #f1f5f9;
            padding-top: 20px;
            text-align: center;
        }
        .verification-box {
            display: block;
            margin: 0 auto 40px auto;
            text-align: center;
            width: 120px;
        }
        .verification-box p {
            font-size: 9px;
            color: #64748b;
            margin-top: 5px;
        }
        .signature-container {
            width: 100%;
            margin-top: 40px;
        }
        .signature-item {
            display: inline-block;
            width: 220px;
            text-align: center;
            margin: 0 60px;
        }
        .signature-line {
            border-top: 1px solid #1e293b;
            margin-bottom: 5px;
        }
        .signature-text {
            font-size: 11px;
            color: #64748b;
            font-weight: bold;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 100px;
            color: rgba(79, 70, 229, 0.05);
            z-index: -1;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            width: 100%;
        }
        .logo-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 400px;
            height: 400px;
            opacity: 0.08;
            z-index: -1;
        }
        .logo-watermark img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="watermark">OFFICIAL RECEIPT</div>

        @if($fullLogoPath)
            <div class="logo-watermark">
                <img src="{{ $fullLogoPath }}" alt="Logo Watermark">
            </div>
        @endif

        <div class="header-banner">
            @if($fullLogoPath)
                <div class="logo-container">
                    <img src="{{ $fullLogoPath }}" alt="Institution Logo">
                </div>
            @endif
            <h1>{{ $settings['site_name'] ?? 'SUG Portal' }}</h1>
            <p>Official Payment Receipt of Student Union Government</p>

            <div class="passport-frame">
                <img src="{{ $fullPassportPath }}" alt="Student Passport">
            </div>
        </div>

        <div class="content">
            <div class="status-section">
                <span class="receipt-title-badge">Payment Receipt</span>
            </div>

            <div class="amount-display">
                <span class="label">Total Amount Paid</span>
                <span class="value">₦{{ number_format($payment->amount, 2) }}</span>
            </div>

            <table class="info-grid">
                <tr>
                    <td class="label">Student Full Name</td>
                    <td class="value">{{ $student->user->name }}</td>
                </tr>
                <tr>
                    <td class="label">Matriculation Number</td>
                    <td class="value">{{ $student->matric_no }}</td>
                </tr>
                <tr>
                    <td class="label">Programme</td>
                    <td class="value">{{ $student->programme->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Payment Date</td>
                    <td class="value">{{ $payment->created_at->format('d M, Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="label">Transaction Reference</td>
                    <td class="value" style="font-family: monospace;">{{ $payment->transaction_ref }}</td>
                </tr>
                <tr>
                    <td class="label">Receipt Number</td>
                    <td class="value">#{{ $payment->id }}</td>
                </tr>
            </table>

            <div class="footer-section">
                <div class="verification-box">
                    <img src="{{ $qrCodeBase64 }}" width="100" height="100">
                    <p>Scan to Verify</p>
                </div>

                <div class="signature-container">
                    <div class="signature-item">
                        <div class="signature-line"></div>
                        <span class="signature-text">SUG Treasurer</span>
                    </div>
                    <div class="signature-item">
                        <div class="signature-line"></div>
                        <span class="signature-text">SUG President</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
