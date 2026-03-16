<!DOCTYPE html>
<html>
<head>
    <title>New Appointment Request - Schedora</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; line-height: 1.6; color: #374151; max-width: 600px; margin: 0 auto; padding: 20px; background: #0f172a; }
        .header { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 40px 20px; text-align: center; border-radius: 12px 12px 0 0; }
        .content { background: #1e293b; padding: 40px 30px; border-radius: 0 0 12px 12px; }
        .appointment-details { background: #334155; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .btn { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); color: white; padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 600; display: inline-block; margin: 10px 5px; }
        .status { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .pending { background: #fef3c7; color: #92400e; }
        footer { text-align: center; padding: 30px 20px; color: #64748b; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; font-size: 28px;">🗓️ New Appointment Request</h1>
        <p style="margin: 10px 0 0 0; opacity: 0.9;">Someone booked with you on Schedora</p>
    </div>

    <div class="content">
        <h2 style="color: white; margin-top: 0;">Hello {{ $appointment->provider->user->name ?? 'Provider' }},</h2>
        
        <p style="color: #94a3b8; margin-bottom: 30px;">You have received a <strong>new appointment request</strong> from a client. Please review and take action.</p>

        <div class="appointment-details">
            <h3 style="color: white; margin: 0 0 15px 0;">Appointment Details</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; font-size: 15px;">
                <div>
                    <strong>Client:</strong><br>
                    {{ $appointment->client->name }}<br>
                    <small style="color: #94a3b8;">{{ $appointment->client->email }}</small>
                </div>
                <div>
                    <strong>Service:</strong><br>
                    {{ $appointment->service->name ?? 'N/A' }}
                </div>
                <div>
                    <strong>Date:</strong><br>
                    {{ $appointment->start_time->format('M d, Y') }}
                </div>
                <div>
                    <strong>Time:</strong><br>
                    {{ $appointment->start_time->format('h:i A') }} - {{ $appointment->end_time->format('h:i A') }}
                </div>
            </div>
            <div style="span 2; margin-top: 15px;">
                <span class="status pending">Pending Review</span>
            </div>
        </div>

        <p style="color: #94a3b8; font-size: 15px; margin-bottom: 30px;">
            Log in to your <strong>Schedora dashboard</strong> to accept or reject this appointment.
        </p>

        <div style="text-align: center;">
            <a href="{{ route('provider.appointments') }}" class="btn">View Dashboard →</a>
        </div>

        <hr style="border: none; border-top: 1px solid #334155; margin: 40px 0;">

        <p style="color: #64748b; font-size: 14px;">
            Need help? <a href="#" style="color: #60a5fa;">Contact support</a>
        </p>
    </div>

    <footer>
        <p>Schedora - Professional Appointment Scheduling</p>
        <p style="margin-top: 5px; opacity: 0.7;">© 2024 All rights reserved.</p>
    </footer>
</body>
</html>

