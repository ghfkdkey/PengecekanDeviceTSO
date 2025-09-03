<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Status Device</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #dc2626; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background-color: #f9f9f9; }
        .alert { padding: 15px; margin: 15px 0; border-radius: 5px; }
        .alert-danger { background-color: #fef2f2; border-left: 4px solid #dc2626; color: #991b1b; }
        .alert-warning { background-color: #fffbeb; border-left: 4px solid #f59e0b; color: #92400e; }
        .info-table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        .info-table th, .info-table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        .info-table th { background-color: #f3f4f6; font-weight: bold; }
        .footer { text-align: center; padding: 15px; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Pemberitahuan Status Device</h1>
        </div>
        
        <div class="content">
            <p>Halo {{ $picName }},</p>
            
            <div class="alert {{ $status === 'failed' ? 'alert-danger' : 'alert-warning' }}">
                <strong>{{ $statusText }} Peringatan!</strong><br>
                Device "{{ $deviceName }}" telah diberi status <strong>{{ $statusText }}</strong> dan memerlukan perhatian segera.
            </div>
            
            <h3>Informasi Device</h3>
            <table class="info-table">
                <tr>
                    <th>Nama Device</th>
                    <td>{{ $deviceName }}</td>
                </tr>
                <tr>
                    <th>Tipe Device</th>
                    <td>{{ $deviceType }}</td>
                </tr>
                <tr>
                    <th>Nomor Seri</th>
                    <td>{{ $serialNumber }}</td>
                </tr>
                <tr>
                    <th>Lokasi</th>
                    <td>{{ $location }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><strong style="color: {{ $status === 'failed' ? '#dc2626' : '#f59e0b' }}">{{ strtoupper($statusText) }}</strong></td>
                </tr>
                <tr>
                    <th>Item Checklist</th>
                    <td>{{ $checklistItem }}</td>
                </tr>
                @if($notes)
                <tr>
                    <th>Catatan</th>
                    <td>{{ $notes }}</td>
                </tr>
                @endif
            </table>
            
            <h3>Informasi Pemeriksaan</h3>
            <table class="info-table">
                <tr>
                    <th>Diperiksa Oleh</th>
                    <td>{{ $checkedBy }}</td>
                </tr>
                <tr>
                    <th>Tanggal Pemeriksaan</th>
                    <td>{{ $checkedAt }}</td>
                </tr>
            </table>
            
            <p><strong>Tindakan Diperlukan:</strong> Harap segera lakukan investigasi dan tindakan yang sesuai untuk device ini.</p>
        </div>
        
        <div class="footer">
            <p>Ini adalah pemberitahuan otomatis dari Device Checker.<br>
            Harap tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>