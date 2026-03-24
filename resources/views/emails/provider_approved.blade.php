<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DM Sans', -apple-system, sans-serif;
            background: #020617;
            color: #ffffff;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 20px;
            overflow: hidden;
        }
        .header {
            padding: 40px;
            text-align: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .logo {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #3b82f6, #22d3ee);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }
        .badge {
            display: inline-block;
            background: rgba(34,211,238,0.1);
            border: 1px solid rgba(34,211,238,0.3);
            color: #22d3ee;
            padding: 4px 16px;
            border-radius: 999px;
            font-size: 12px;
        }
        .body {
            padding: 40px;
        }
        .title {
            font-size: 24px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
        }
        .subtitle {
            color: rgba(255,255,255,0.5);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .credentials-card {
            background: rgba(34,211,238,0.05);
            border: 1px solid rgba(34,211,238,0.2);
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 32px;
        }
        .credentials-title {
            color: #22d3ee;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
        }
        .credential-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .credential-row:last-child { border-bottom: none; }
        .credential-label {
            color: rgba(255,255,255,0.4);
            font-size: 13px;
        }
        .credential-value {
            color: #ffffff;
            font-weight: 600;
            font-size: 14px;
            font-family: monospace;
            background: rgba(255,255,255,0.05);
            padding: 4px 12px;
            border-radius: 8px;
        }
        .btn {
            display: block;
            width: 100%;
            text-align: center;
            background: linear-gradient(135deg, #3b82f6, #22d3ee);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 14px;
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 24px;
        }
        .warning {
            background: rgba(251,191,36,0.1);
            border: 1px solid rgba(251,191,36,0.2);
            border-radius: 10px;
            padding: 16px;
            color: rgba(251,191,36,0.8);
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 24px;
        }
        .steps {
            margin-bottom: 32px;
        }
        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .step-number {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #3b82f6, #22d3ee);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
            color: white;
            line-height: 24px;
            text-align: center;
        }
        .step-text {
            color: rgba(255,255,255,0.6);
            font-size: 14px;
            line-height: 1.5;
            padding-top: 2px;
        }
        .footer {
            padding: 24px 40px;
            border-top: 1px solid rgba(255,255,255,0.05);
            text-align: center;
            color: rgba(255,255,255,0.2);
            font-size: 12px;
            background: rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <div class="logo">Schedora</div>
            <div class="badge">Provider Account Approved ✓</div>
        </div>

        {{-- Body --}}
        <div class="body">

            <h1 class="title">Welcome aboard, {{ $name }}! 🎉</h1>
            <p class="subtitle">
                Great news! Your provider application has been approved.
                Your account is ready — here are your login credentials to get started.
            </p>

            {{-- Credentials Card --}}
            <div class="credentials-card">
                <div class="credentials-title">🔐 Your Login Credentials</div>

                <div class="credential-row">
                    <span class="credential-label">Email</span>
                    <span class="credential-value">{{ $email }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Password</span>
                    <span class="credential-value">{{ $password }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Login URL</span>
                    <span class="credential-value">{{ url('/login') }}</span>
                </div>
            </div>

            {{-- Login Button --}}
            <a href="{{ url('/login') }}" class="btn">
                Login to Your Provider Dashboard →
            </a>

            {{-- Warning --}}
            <div class="warning">
                ⚠️ For security reasons, please change your password immediately after logging in for the first time.
            </div>

            {{-- Getting Started Steps --}}
            <div class="steps">
                <p style="color:rgba(255,255,255,0.4);font-size:12px;text-transform:uppercase;letter-spacing:1px;margin-bottom:16px;">
                    Getting Started
                </p>
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-text">Login using the credentials above</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-text">Set up your working hours and services</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-text">Start receiving appointments from clients</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-text">Get paid securely via Stripe</div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="footer">
            © {{ date('Y') }} Schedora. All rights reserved.<br>
            If you did not apply to be a provider, please ignore this email.
        </div>
    </div>
</body>
</html>
