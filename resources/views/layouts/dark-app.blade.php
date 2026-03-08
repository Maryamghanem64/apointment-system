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
        
        <!-- Alpine.js for dropdowns and mobile menu -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            'heading': ['Syne', 'sans-serif'],
                            'body': ['DM Sans', 'sans-serif'],
                        },
                        colors: {
                            'accent-blue': '#3B82F6',
                            'accent-cyan': '#06B6D4',
                        },
                        animation: {
                            'fade-up': 'fadeUp 0.8s ease-out forwards',
                            'float': 'float 6s ease-in-out infinite',
                            'glow-pulse': 'glowPulse 4s ease-in-out infinite',
                        },
                        keyframes: {
                            fadeUp: {
                                '0%': { opacity: '0', transform: 'translateY(30px)' },
                                '100%': { opacity: '1', transform: 'translateY(0)' },
                            },
                            float: {
                                '0%, 100%': { transform: 'translateY(0px) rotate(0deg)' },
                                '50%': { transform: 'translateY(-20px) rotate(2deg)' },
                            },
                            glowPulse: {
                                '0%, 100%': { opacity: '0.5', transform: 'scale(1)' },
                                '50%': { opacity: '0.8', transform: 'scale(1.1)' },
                            },
                        },
                    },
                },
            }
        </script>

        <style>
            /* Custom Animations */
            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(30px); }
                to { opacity: 1; transform: translateY(0); }
            }

            @keyframes float {
                0%, 100% { transform: translateY(0px) rotate(0deg); }
                50% { transform: translateY(-20px) rotate(2deg); }
            }

            @keyframes glowPulse {
                0%, 100% { opacity: 0.4; transform: scale(1); }
                50% { opacity: 0.7; transform: scale(1.15); }
            }

            .animate-fade-up { animation: fadeUp 0.8s ease-out forwards; opacity: 0; }
            .animate-float { animation: float 8s ease-in-out infinite; }
            .animate-glow-pulse { animation: glowPulse 6s ease-in-out infinite; }

            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
            .delay-400 { animation-delay: 400ms; }
            .delay-500 { animation-delay: 500ms; }

            /* Glass morphism */
            .glass {
                background: rgba(255, 255, 255, 0.03);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }

            .glass-card {
                background: rgba(255, 255, 255, 0.05);
                backdrop-filter: blur(20px);
                -webkit-backdrop-filter: blur(20px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .glass-card:hover {
                background: rgba(255, 255, 255, 0.08);
                border-color: rgba(59, 130, 246, 0.3);
                transform: translateY(-4px);
                box-shadow: 0 20px 40px -12px rgba(59, 130, 246, 0.2);
            }

            /* Form inputs dark theme */
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

            /* Button styles */
            .btn-primary {
                background: linear-gradient(135deg, #3B82F6 0%, #06B6D4 100%);
                box-shadow: 0 4px 15px rgba(59, 130, 246, 0.35), 0 0 30px rgba(6, 182, 212, 0.15);
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(59, 130, 246, 0.45), 0 0 40px rgba(6, 182, 212, 0.25);
            }

            .btn-secondary {
                background: transparent;
                border: 1px solid rgba(255, 255, 255, 0.2);
                transition: all 0.3s ease;
            }

            .btn-secondary:hover {
                border-color: rgba(59, 130, 246, 0.5);
                box-shadow: 0 0 20px rgba(59, 130, 246, 0.15);
                background: rgba(59, 130, 246, 0.08);
                transform: translateY(-2px);
            }

            /* Table styles */
            .table-dark th {
                background: rgba(255, 255, 255, 0.05);
                color: rgba(255, 255, 255, 0.6);
                font-weight: 500;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }

            .table-dark td {
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                color: rgba(255, 255, 255, 0.8);
            }

            .table-dark tr:hover td {
                background: rgba(255, 255, 255, 0.05);
            }

            /* Links */
            .link-cyan {
                color: #22D3EE;
                transition: color 0.2s ease;
            }

            .link-cyan:hover {
                color: #67E8F9;
            }

            /* Background orbs */
            .bg-orb {
                position: absolute;
                border-radius: 50%;
                filter: blur(100px);
                pointer-events: none;
            }

            /* Scrollbar */
            ::-webkit-scrollbar { width: 8px; }
            ::-webkit-scrollbar-track { background: #020617; }
            ::-webkit-scrollbar-thumb {
                background: linear-gradient(135deg, #3B82F6, #06B6D4);
                border-radius: 4px;
            }

            /* Scroll animations */
            .scroll-animate {
                opacity: 0;
                transform: translateY(30px);
                transition: all 0.6s ease-out;
            }

            .scroll-animate.visible {
                opacity: 1;
                transform: translateY(0);
            }

            /* Select dropdown styling */
            select {
                background-color: #0f172a !important;
                color: #ffffff !important;
                border: 1px solid rgba(255,255,255,0.1) !important;
                border-radius: 0.75rem !important;
                padding: 0.75rem 1rem !important;
                appearance: none;
                -webkit-appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2322d3ee'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 1rem center;
                background-size: 1rem;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            select:focus {
                outline: none !important;
                border-color: #22d3ee !important;
                box-shadow: 0 0 0 3px rgba(34,211,238,0.15) !important;
            }

            select option {
                background-color: #0f172a !important;
                color: #ffffff !important;
                padding: 0.5rem 1rem !important;
            }

            select option:hover,
            select option:focus,
            select option:checked {
                background-color: #1e3a5f !important;
                color: #22d3ee !important;
            }

            select option:disabled {
                color: rgba(255,255,255,0.3) !important;
            }
        </style>
    </head>
    <body class="font-body antialiased min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-blue-950 text-white">
        <!-- Animated Background Blobs -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="bg-orb w-[600px] h-[600px] bg-blue-600/20 animate-glow-pulse top-[-15%] left-[-10%]"></div>
            <div class="bg-orb w-[500px] h-[500px] bg-cyan-500/15 animate-glow-pulse bottom-[-10%] right-[-5%]" style="animation-delay: 2s;"></div>
            <div class="absolute w-[400px] h-[400px] bg-blue-500/10 rounded-full blur-3xl animate-float top-1/3 right-1/4"></div>
            <div class="absolute inset-0 bg-[linear-gradient(rgba(59,130,246,0.02)_1px,transparent_1px),linear-gradient(90deg,rgba(59,130,246,0.02)_1px,transparent_1px)] bg-[size:60px_60px]"></div>
        </div>

        <!-- Navbar -->
        <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300" id="navbar" x-data="{ mobileMenuOpen: false }">
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-xl border-b border-white/5 opacity-0 transition-opacity duration-300" id="navbar-bg"></div>
            <div class="relative max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-xl blur-lg opacity-40"></div>
                            <div class="relative">
                                <svg class="h-10 w-10" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                        <a href="{{ route('dashboard') }}" class="text-2xl font-heading font-bold tracking-tight hover:text-cyan-400 transition-colors">Schedora</a>
                    </div>

                    <!-- Nav Links (Desktop) -->
                    <div class="hidden md:flex items-center gap-6">
                        @auth
                            {{-- Admin Navigation --}}
                            @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Dashboard</a>
                                <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Users</a>
                                <a href="{{ route('providers.index') }}" class="{{ request()->routeIs('providers.*') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Providers</a>
                                <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Services</a>
                                <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.index') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Appointments</a>
                                <a href="{{ route('payments.index') }}" class="{{ request()->routeIs('payments.index') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Payments</a>
                                <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Settings</a>
                            
                            {{-- Provider Navigation --}}
                            @elseif(auth()->user()->hasRole('provider'))
                                <a href="{{ route('provider.dashboard') }}" class="{{ request()->routeIs('provider.dashboard') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Dashboard</a>
                                <a href="{{ route('appointments.index') }}" class="{{ request()->routeIs('appointments.index') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Appointments</a>
                                <a href="{{ route('provider.profile') }}" class="{{ request()->routeIs('provider.profile') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Profile</a>
                                <a href="{{ route('provider.settings') }}" class="{{ request()->routeIs('provider.settings') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Settings</a>
                            
                            {{-- Client Navigation --}}
                            @elseif(auth()->user()->hasRole('client'))
                                <a href="{{ route('client.dashboard') }}" class="{{ request()->routeIs('client.dashboard') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Dashboard</a>
                                <a href="{{ route('client.appointments') }}" class="{{ request()->routeIs('client.appointments') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">My Appointments</a>
                                <a href="{{ route('client.profile') }}" class="{{ request()->routeIs('client.profile') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Profile</a>
                                <a href="{{ route('client.settings') }}" class="{{ request()->routeIs('client.settings') ? 'text-cyan-400 border-b border-cyan-400' : 'text-white/60 hover:text-white' }} transition-colors duration-200 text-sm font-medium py-1">Settings</a>
                            @endif
                        @else
                            <a href="#features" class="text-white/60 hover:text-white transition-colors duration-200 text-sm font-medium">Features</a>
                            <a href="#" class="text-white/60 hover:text-white transition-colors duration-200 text-sm font-medium">Pricing</a>
                        @endauth
                    </div>

                    <!-- Auth Buttons / User Menu -->
                    <div class="flex items-center gap-4">
                        @if (Route::has('login'))
                            @auth
                                <!-- User Menu Dropdown -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="flex items-center gap-2 text-white/80 hover:text-white transition-colors duration-200">
                                        <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                        @if(auth()->user()->hasRole('admin'))
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/30">Admin</span>
                                        @elseif(auth()->user()->hasRole('provider'))
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">Provider</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Client</span>
                                        @endif
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>
                                    
                                    <!-- Dropdown Menu -->
                                    <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 glass-card rounded-xl py-2" style="display: none;">
                                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-white/70 hover:text-white hover:bg-white/5 transition-colors">
                                            Profile Settings
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-white/70 hover:text-white hover:bg-white/5 transition-colors">
                                                Log Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-white/70 hover:text-white font-medium transition-colors duration-200 text-sm">
                                    Sign in
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="btn-primary text-white font-semibold py-2.5 px-5 rounded-xl text-sm">
                                        Get Started
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white/70 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" class="md:hidden glass border-t border-white/10" style="display: none;">
                <div class="px-4 py-4 space-y-3">
                    @auth
                        <div class="flex items-center gap-3 pb-3 border-b border-white/10">
                            <span class="text-sm font-medium text-white">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->hasRole('admin'))
                                <span class="px-2 py-0.5 text-xs rounded-full bg-rose-500/20 text-rose-400 border border-rose-500/30">Admin</span>
                            @elseif(auth()->user()->hasRole('provider'))
                                <span class="px-2 py-0.5 text-xs rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">Provider</span>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">Client</span>
                            @endif
                        </div>
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="block py-2 text-white/70 hover:text-white">Dashboard</a>
                            <a href="{{ route('admin.users') }}" class="block py-2 text-white/70 hover:text-white">Users</a>
                            <a href="{{ route('providers.index') }}" class="block py-2 text-white/70 hover:text-white">Providers</a>
                            <a href="{{ route('services.index') }}" class="block py-2 text-white/70 hover:text-white">Services</a>
                            <a href="{{ route('appointments.index') }}" class="block py-2 text-white/70 hover:text-white">Appointments</a>
                            <a href="{{ route('payments.index') }}" class="block py-2 text-white/70 hover:text-white">Payments</a>
                            <a href="{{ route('admin.settings') }}" class="block py-2 text-white/70 hover:text-white">Settings</a>
                        @elseif(auth()->user()->hasRole('provider'))
                            <a href="{{ route('provider.dashboard') }}" class="block py-2 text-white/70 hover:text-white">Dashboard</a>
                            <a href="{{ route('appointments.index') }}" class="block py-2 text-white/70 hover:text-white">Appointments</a>
                            <a href="{{ route('provider.profile') }}" class="block py-2 text-white/70 hover:text-white">Profile</a>
                            <a href="{{ route('provider.settings') }}" class="block py-2 text-white/70 hover:text-white">Settings</a>
                        @else
                            <a href="{{ route('client.dashboard') }}" class="block py-2 text-white/70 hover:text-white">Dashboard</a>
                            <a href="{{ route('client.appointments') }}" class="block py-2 text-white/70 hover:text-white">My Appointments</a>
                            <a href="{{ route('client.profile') }}" class="block py-2 text-white/70 hover:text-white">Profile</a>
                            <a href="{{ route('client.settings') }}" class="block py-2 text-white/70 hover:text-white">Settings</a>
                        @endif
                        <div class="pt-3 border-t border-white/10">
                            <a href="{{ route('profile.edit') }}" class="block py-2 text-white/70 hover:text-white">Profile Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left py-2 text-white/70 hover:text-white">Log Out</button>
                            </form>
                        </div>
                    @else
                        <a href="#features" class="block py-2 text-white/70 hover:text-white">Features</a>
                        <a href="#" class="block py-2 text-white/70 hover:text-white">Pricing</a>
                        <div class="pt-3 border-t border-white/10">
                            <a href="{{ route('login') }}" class="block py-2 text-white/70 hover:text-white">Sign in</a>
                            @if(Route::has('register'))
                                <a href="{{ route('register') }}" class="block py-2 text-cyan-400 hover:text-cyan-300">Get Started</a>
                            @endif
                        </div>
                    @endauth
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="relative z-10 pt-20">
            @yield('content')
        </div>

        <!-- Footer -->
        <footer class="relative z-10 border-t border-white/5 py-8 mt-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3">
                        <svg class="h-7 w-7" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="footerLogoGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3B82F6"/>
                                    <stop offset="100%" style="stop-color:#06B6D4"/>
                                </linearGradient>
                            </defs>
                            <rect width="40" height="40" rx="10" fill="url(#footerLogoGrad)"/>
                            <path d="M12 20C12 16.6863 14.6863 14 18 14H22C25.3137 14 28 16.6863 28 20C28 23.3137 25.3137 26 22 26H18C14.6863 26 12 23.3137 12 20Z" fill="white" fill-opacity="0.95"/>
                            <circle cx="20" cy="20" r="4" fill="#0891B2"/>
                        </svg>
                        <span class="font-heading text-lg font-semibold">Schedora</span>
                    </div>
                    <div class="text-white/30 text-sm">
                        &copy; {{ date('Y') }} Schedora. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>

        <script>
            // Navbar blur on scroll
            const navbar = document.getElementById('navbar');
            const navbarBg = document.getElementById('navbar-bg');
            
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbarBg.classList.remove('opacity-0');
                } else {
                    navbarBg.classList.add('opacity-0');
                }
            });

            // Intersection Observer for scroll animations
            const observerOptions = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            document.querySelectorAll('.scroll-animate').forEach(el => {
                observer.observe(el);
            });
        </script>

        @stack('scripts')
    </body>
</html>
