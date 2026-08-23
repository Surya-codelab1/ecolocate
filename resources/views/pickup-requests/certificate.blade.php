<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0B1720;
            margin: 0;
            padding: 0;
        }

        .page {
            padding: 26px;
        }

        .outer-border {
            border: 3px solid #10B981;
            border-radius: 4px;
            padding: 4px;
        }

        .inner-border {
            border: 1.5px dashed #6EE7B7;
            border-radius: 4px;
            padding: 28px 36px;
            position: relative;
        }

        /* Corner recycling glyphs for decoration */
        .corner {
            position: absolute;
            font-size: 22px;
            color: #A7F3D0;
        }
        .corner-tl { top: 8px; left: 10px; }
        .corner-tr { top: 8px; right: 10px; }
        .corner-bl { bottom: 8px; left: 10px; }
        .corner-br { bottom: 8px; right: 10px; }

        .header {
            text-align: center;
            margin-bottom: 6px;
        }
        .logo-wrap { margin-bottom: 8px; }
        .logo-wrap img {
            height: 46px;
        }

        .brand-name {
            font-size: 15px;
            font-weight: bold;
            color: #059669;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin: 0;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #0B1720;
            margin: 10px 0 2px 0;
        }
        .subtitle {
            font-size: 12px;
            color: #64748B;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .divider {
            width: 90px;
            height: 3px;
            background: #10B981;
            margin: 14px auto 18px auto;
            border-radius: 3px;
        }

        .intro-text {
            text-align: center;
            font-size: 12.5px;
            color: #475569;
            line-height: 1.6;
            margin-bottom: 4px;
        }
        .recipient-name {
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            color: #059669;
            margin: 8px 0 18px 0;
        }

        /* Stat badges row: credits + money */
        .stats-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 12px 0;
            margin-bottom: 20px;
        }
        .stat-box {
            background: #ECFDF5;
            border: 1px solid #A7F3D0;
            border-radius: 10px;
            padding: 12px 10px;
            text-align: center;
            width: 50%;
        }
        .stat-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #059669;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #0B1720;
        }

        /* Details table */
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .details-table td {
            padding: 7px 4px;
            font-size: 12px;
            border-bottom: 1px solid #F1F5F9;
        }
        .details-table td.label {
            font-weight: bold;
            width: 190px;
            color: #334155;
        }
        .details-table td.value {
            color: #64748B;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
        }
        .footer .cert-id {
            display: inline-block;
            background: #0B1720;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
            padding: 6px 16px;
            border-radius: 20px;
            margin-bottom: 10px;
        }
        .footer .fine-print {
            font-size: 10px;
            color: #94A3B8;
            line-height: 1.5;
        }

        .signature-row {
            width: 100%;
            margin-top: 34px;
        }
        .signature-box {
            width: 45%;
            display: inline-block;
            text-align: center;
            font-size: 11px;
            color: #64748B;
        }
        .signature-line {
            border-top: 1px solid #94A3B8;
            margin-bottom: 4px;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <div class="page">
        <div class="outer-border">
            <div class="inner-border">

                <div class="corner corner-tl">&#9851;</div>
                <div class="corner corner-tr">&#9851;</div>
                <div class="corner corner-bl">&#9851;</div>
                <div class="corner corner-br">&#9851;</div>

                <div class="header">
                    <div class="logo-wrap">
                        <img src="{{ public_path('assets/images/logo.png') }}" alt="EcoLocate">
                    </div>
                    <p class="brand-name">EcoLocate</p>
                    <p class="subtitle">Certificate of E-Waste Recycling</p>
                    <h1 class="title">&#9851; Recycling Certificate</h1>
                </div>

                <div class="divider"></div>

                <p class="intro-text">This certificate is proudly presented to</p>
                <p class="recipient-name">{{ $pickup->user->name ?? 'N/A' }}</p>
                <p class="intro-text">
                    for responsibly recycling their e-waste device through
                    <strong>{{ $facility->facility_name }}</strong>,
                    contributing to a cleaner and more sustainable planet.
                </p>

                <table class="stats-table">
                    <tr>
                        <td class="stat-box">
                            <div class="stat-label">Eco Credits Earned</div>
                            <div class="stat-value">{{ $pickup->device->eco_credits ?? 0 }} pts</div>
                        </td>
                        <td class="stat-box">
                            <div class="stat-label">Estimated Recycling Value</div>
                            <div class="stat-value">&#8377;{{ number_format($pickup->device->estimated_recycling_value ?? 0, 2) }}</div>
                        </td>
                    </tr>
                </table>

                <table class="details-table">
                    <tr>
                        <td class="label">Device Recycled:</td>
                        <td class="value">{{ $pickup->device->brand ?? '' }} {{ $pickup->device->model_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Device Category:</td>
                        <td class="value">{{ $pickup->device->category ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Pickup Address:</td>
                        <td class="value">{{ $pickup->pickup_address }}</td>
                    </tr>
                    <tr>
                        <td class="label">Facility Name:</td>
                        <td class="value">{{ $facility->facility_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">Facility Address:</td>
                        <td class="value">{{ $facility->full_address }}</td>
                    </tr>
                    <tr>
                        <td class="label">Completed On:</td>
                        <td class="value">{{ $pickup->completed_at?->format('d M Y, h:i A') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Status:</td>
                        <td class="value">{{ $pickup->status }}</td>
                    </tr>
                </table>

                <div class="signature-row">
                    <div class="signature-box" style="margin-right: 8%;">
                        <div class="signature-line">Facility Representative</div>
                        {{ $facility->contact_person ?? '' }}
                    </div>
                    <div class="signature-box">
                        <div class="signature-line">EcoLocate</div>
                        Verified &amp; Issued
                    </div>
                </div>

                <div class="footer">
                    <div class="cert-id">CERT-{{ str_pad($pickup->id, 6, '0', STR_PAD_LEFT) }}</div>
                    <p class="fine-print">
                        This certificate confirms responsible e-waste recycling through EcoLocate.<br>
                        Generated on {{ now()->format('d M Y, h:i A') }}
                    </p>
                </div>

            </div>
        </div>
    </div>

</body>
</html>