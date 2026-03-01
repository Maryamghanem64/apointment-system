<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Schedora') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Syne:wght@600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS via CDN -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'heading': ['Syne', 'sans-serif'],
                            'body': ['DM Sans', 'sans-serif'],
                        },
                    },
                },
            }
        </script>

        <style>
            .input-dark {
                background: rgba(255, 255, 255, 0.05);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: white;
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                transition: all 0.3s ease;
            }

            .input-dark::placeholder {
                color: rgba(255, 255, 255, 0.3);
            }

            .input-dark:focus {
                outline: none;
                border-color: #06B6D4;
                box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
            }

            .btn-primary {
                background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%);
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35), 0 0 30px rgba(6, 182, 212, 0.15);
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.45), 0 0 40px rgba(6, 182, 212, 0.25);
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .bg-orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(100px);
                pointer-events: none;
            }

            @keyframes glowPulse {
                0%, 100% { opacity: 0.4; transform: scale(1); }
                50% { opacity: 0.7; transform: scale(1.1); }
            }

            .animate-glow-pulse {
                animation: glowPulse 6s ease-in-out infinite;
            }

            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #020617; }
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #3B82F6, #06B6D4);
                border-radius: 4px;
            }
        </style>
    </head>
    <body class="font-body antialiased min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white">
        <!-- Background -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="bg-orb w-[600px] h-[600px] bg-blue-600/20 animate-glow-pulse top-[-15%] left-[-10%]"></div>
            <div class="bg-orb w-[500px] h-[500px] bg-cyan-500/15 animate-glow-pulse bottom-[-10%] right-[-5%]" style="animation-delay: 2s;"></div>
        </div>

        <!-- Logo -->
        <div class="relative z-10 flex justify-center pt-12">
            <a href="/" class="flex items-center gap-3">
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-xl blur-lg opacity-40"></div>
                    <div class="relative">
                        <svg class="h-12 w-12" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="logoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3B82F6"/>
                                    <stop offset="100%" style="stop-color:#06B6D4"/>
                                </linearGradient>
                            </defs>
                            <rect width="40" height="40" rx="10" fill="url(#logoGrad)"/>
                            <path d="M12 20C12 16.6863 14.6863 14 18 14H22C25.3137 14 28 16.6863 28 20C28 23.3137 25.3137 26 22 26H18C14.6863 26 12 23.3137 12 20Z" fill="white" fill-opacity="0.95"/>
                            <circle cx="20" cy="20" r="4" fill="#0891B2"/>
                        </svg>
                    </div>
                </div>
                <span class="text-3xl font-heading font-bold tracking-tight">Schedora</span>
            </a>
        </div>

        <!-- Auth Card -->
        <div class="relative z-10 flex flex-col items-center justify-center min-h-[calc(100vh-120px)] px-4">
            <div class="w-full max-w-md">
                <div class="glass-card rounded-2xl p-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </body>
</html>
