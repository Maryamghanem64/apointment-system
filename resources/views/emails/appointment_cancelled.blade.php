<!DOCTYPE html>
<html>
<head>
    <title>Appointment Cancelled - Schedora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; max-width: 600px; margin: 0 auto; padding: 20px; background: #0f172a; }
        .header { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; padding: 40px 20px; text-align: center; border-radius: 12px 12px 0 0; }
        .content { background: #1e293b; padding: 40px 30px; border-radius: 0 0 12px 12px; }
        .appointment-details { background: #334155; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block; margin: 10px 5px; }
        .status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .cancelled { background: #fee2e2; color: #dc2626; }
        footer { text-align: center; padding: 30px 20px; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 28px;">❌ Appointment Cancelled</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Your appointment request was declined</p>
    </div>

    <div class="content">
        <h2 style="color: white; margin-top: 0;">Hello {{ $appointment->client->name }},</h2>
        
        <p style="color: #94a3b8; margin-bottom: 30px;">
            Unfortunately, your appointment request with <strong>{{ $appointment->provider->user->name }}</strong> has been <strong>declined</strong>.
        </p>

        <div class="appointment-details">
            <h3 style="color: white; margin: 0 0 15px 0;">Cancelled Appointment</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 15px;">
                <div>
                    <strong>Provider:</strong><br>
                    {{ $appointment->provider->user->name }}
                </div>
                <div>
                    <strong>Service:</strong><br>
                    {{ $appointment->service->name ?? 'N/A' }}
                </div>
                <div>
                    <strong>Requested Date:</strong><br>
                    {{ $appointment->start_time->format('M d, Y') }}
                </div>
                <div>
                    <strong>Requested Time:</strong><br>
                    {{ $appointment->start_time->format('h:i A') }}
                </div>
            </div>
            <div style="grid-column: span 2; margin-top: 15px;">
                <span class="status cancelled">Cancelled ✕</span>
            </div>
        </div>

        <p style="color: #94a3b8; font-size: 15px; margin-bottom: 30px;">
            You can browse other providers or book a different time slot.
        </p>

        <div style="text-align: center;">
            <a href="{{ route('client.appointments') }}" class="btn">View Appointments →</a>
            <a href="{{ route('services.index') }}" class="btn">Browse Providers →</a>
        </div>

        <hr style="border: none; border-top: 1px solid #334155; margin: 40px 0;">

        <p style="color: #64748b; font-size: 14px;">
            Questions? <a href="#" style="color: #3b82f6;">Contact support</a>
        </p>
    </div>

    <footer>
        <p>Schedora - Professional Appointment Scheduling</p>
        <p style="margin-top: 5px; opacity: 0.7;">© 2024 All rights reserved.</p>
    </footer>
</body>
</html>

