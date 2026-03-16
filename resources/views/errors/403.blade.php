@extends('layouts.dark-app')
@section('content')
<div class="min-h-screen flex flex-col items-center justify-center text-center px-4">
    <div class="text-9xl font-black mb-4"
         style="font-family:'Syne',sans-serif;
                background:linear-gradient(135deg,#6b7280,#4b5563);
                -webkit-background-clip:text;
                -webkit-text-fill-color:transparent;">
        403
    </div>
    <h1 class="text-2xl font-bold text-white mb-3" style="font-family:'Syne',sans-serif;">
        Access Denied
    </h1>
    <p class="text-white/50 text-sm mb-8 max-w-md">
        You don't have permission to access this page.
    </p>
    <a href="{{ route('dashboard') }}"
       class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-8 py-3 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300">
        Back to Dashboard
    </a>
</div>
@endsection

