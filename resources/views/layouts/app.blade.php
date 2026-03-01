<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Schedora') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Custom Blue Theme Styles -->
        <style>
            /* Blue Theme Color Palette */
            :root {
                --blue-50: #eff6ff;
                --blue-100: #dbeafe;
                --blue-200: #bfdbfe;
                --blue-300: #93c5fd;
                --blue-400: #60a5fa;
                --blue-500: #3b82f6;
                --blue-600: #2563eb;
                --blue-700: #1d4ed8;
                --blue-800: #1e40af;
                --blue-900: #1e3a8a;
            }

            /* Smooth Scroll */
            html {
                scroll-behavior: smooth;
            }

            /* Custom Button Styles */
            .btn-primary {
                @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300;
            }

            .btn-primary:hover {
                @apply shadow-lg transform scale-105;
            }

            /* Link Styles */
            .link-blue {
                @apply text-blue-600 hover:text-blue-800 underline-offset-4 hover:underline transition-colors duration-200;
            }

            /* Card Styles */
            .card {
                @apply bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300;
            }

            /* Section Backgrounds */
            .section-blue-gradient {
                background: linear-gradient(to right, var(--blue-500), var(--blue-700));
            }

            /* Custom Scrollbar */
            ::-webkit-scrollbar {
                width: 8px;
            }

            ::-webkit-scrollbar-track {
                background: var(--blue-50);
            }

            ::-webkit-scrollbar-thumb {
                background: var(--blue-300);
                border-radius: 4px;
            }

            ::-webkit-scrollbar-thumb:hover {
                background: var(--blue-500);
            }

            /* Animation Classes */
            .fade-in {
                animation: fadeIn 0.5s ease-in-out;
            }

            .fade-in-up {
                animation: fadeInUp 0.6s ease-out;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }
                to {
                    opacity: 1;
                }
            }

            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Staggered Animation Delays */
            .delay-100 { animation-delay: 100ms; }
            .delay-200 { animation-delay: 200ms; }
            .delay-300 { animation-delay: 300ms; }
            .delay-400 { animation-delay: 400ms; }
            .delay-500 { animation-delay: 500ms; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm border-b border-blue-100">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
