@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-2xl font-bold text-white">{{ __('Welcome back, ') }} {{ Auth::user()->name }}!</h3>
                <p class="text-white/60 mt-2">{{ __('Manage your appointments and schedule.') }}</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Today\'s Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $todayAppointments->count() }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Total Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $totalAppointments }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Completed') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $completedAppointments }}</div>
                </div>
                
                <!-- Average Rating -->
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Average Rating') }}</div>
                    <div class="flex items-center mt-1">
                        <div class="text-3xl font-bold text-white">{{ number_format($averageRating, 1) }}</div>
                        <div class="flex ml-2">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= round($averageRating))
                                    <span class="text-cyan-400 text-xl">★</span>
                                @else
                                    <span class="text-white/20 text-xl">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="text-sm text-white/40 mt-1">{{ __('Based on ') . $totalReviews . __(' reviews') }}</div>
                </div>
            </div>
            
            <!-- My Reviews Section -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('My Reviews') }}</h3>
                
                @if($reviews->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($reviews as $review)
                            <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center mr-2">
                                            <span class="text-sm font-medium text-blue-400">
                                                {{ substr($review->user->name ?? 'U', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="text-white font-medium">{{ $review->user->name ?? 'Anonymous' }}</div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <span class="text-cyan-400 text-lg">★</span>
                                            @else
                                                <span class="text-white/20 text-lg">★</span>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-white/60 text-sm mb-2">{{ Str::limit($review->comment, 100) }}</p>
                                @endif
                                <div class="text-xs text-white/40">{{ $review->created_at->format('M d, Y') }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-white/50">{{ __('No reviews yet.') }}</p>
                    </div>
                @endif
            </div>
            
            <!-- Today's Appointments -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('Today\'s Appointments') }}</h3>
                
                @if($todayAppointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Client') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Time') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($todayAppointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $appointment->client->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/80">
                                            {{ $appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
                                                    'confirmed' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
                                                    'pending' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
                                                    'cancelled' => 'bg-rose-500/20 text-rose-400 border border-rose-500/30',
                                                ];
                                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-slate-500/20 text-slate-400 border border-slate-500/30';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($appointment->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($appointment->status === 'pending')
                                                <div class="flex gap-1">
                                                    <form method="POST" action="{{ route('appointments.accept', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium p-1 rounded transition-colors">✓</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('appointments.reject', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-medium p-1 rounded transition-colors">✕</button>
                                                    </form>
                                                </div>
                                            @elseif($appointment->status === 'confirmed' && $appointment->payment && $appointment->payment->status === 'paid')
                                                <form method="POST" action="{{ route('appointments.complete', $appointment) }}" style="display: inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-cyan-400 hover:text-cyan-300 text-xs font-medium p-1 rounded transition-colors">Complete</button>
                                                </form>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-white/50">{{ __('No appointments scheduled for today.') }}</p>
                @endif
            </div>
            
            <!-- Upcoming Appointments -->
            <div class="glass-card rounded-xl p-6">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('Upcoming Appointments') }}</h3>
                
                @if($upcomingAppointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Client') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Date & Time') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Status') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($upcomingAppointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $appointment->client->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/80">
                                            {{ $appointment->service->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60">
                                            {{ \Carbon\Carbon::parse($appointment->start_time)->format('Y-m-d H:i') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $statusClasses = [
                                                    'completed' => 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30',
                                                    'confirmed' => 'bg-blue-500/20 text-blue-400 border border-blue-500/30',
                                                    'pending' => 'bg-amber-500/20 text-amber-400 border border-amber-500/30',
                                                    'cancelled' => 'bg-rose-500/20 text-rose-400 border border-rose-500/30',
                                                ];
                                                $statusClass = $statusClasses[$appointment->status] ?? 'bg-slate-500/20 text-slate-400 border border-slate-500/30';
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ ucfirst($appointment->status ?? 'pending') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($appointment->status === 'pending')
                                                <div class="flex gap-1">
                                                    <form method="POST" action="{{ route('appointments.accept', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-emerald-400 hover:text-emerald-300 text-xs font-medium p-1 rounded transition-colors">✓</button>
                                                    </form>
                                                    <form method="POST" action="{{ route('appointments.reject', $appointment) }}" style="display: inline;">
                                                        @csrf @method('PATCH')
                                                        <button type="submit" class="text-rose-400 hover:text-rose-300 text-xs font-medium p-1 rounded transition-colors">✕</button>
                                                    </form>
                                                </div>
                                            @elseif($appointment->status === 'confirmed' && $appointment->payment && $appointment->payment->status === 'paid')
                                                <form method="POST" action="{{ route('appointments.complete', $appointment) }}" style="display: inline;">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-cyan-400 hover:text-cyan-300 text-xs font-medium p-1 rounded transition-colors">Complete</button>
                                                </form>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-white/50">{{ __('No upcoming appointments.') }}</p>
                @endif
            </div>
</div>
</div>

    {{-- ===== PLATFORM REVIEW SECTION ===== --}}
<div class="mt-10 mb-10 flex flex-col items-center text-center">

        {{-- Section Header --}}
        <div class="mb-6">
            <h2 style="font-family:'Syne',sans-serif;"
                class="text-2xl font-bold text-white mb-1">
                Rate Your Experience
            </h2>
            <p class="text-white/50 text-sm">
                Help us improve by sharing your feedback about Schedora
            </p>
        </div>

@php
            $existingReview = \App\Models\Review::where('user_id', auth()->id())
                ->where('review_type', 'platform')
                ->first();
        @endphp

        {{-- FORM — only if no review yet --}}
        @if(!$existingReview)
<div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 w-full max-w-2xl mx-auto transition-all duration-300"
>
            {{-- Success Message --}}
            @if(session('success'))
            <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            {{-- Error Message --}}
            @if(session('error'))
            <div class="mb-6 bg-rose-500/20 border border-rose-500/30 text-rose-400 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
                <span>✕</span>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="review_type" value="platform">

                {{-- Star Rating --}}
                <div class="mb-6">
                    <label class="block text-white/60 text-sm font-medium mb-3">
                        Your Rating <span class="text-rose-400">*</span>
                    </label>
<div class="flex gap-3 justify-center" id="platform-stars">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button"
                            class="star-btn text-5xl text-white/20 transition-all duration-200 cursor-pointer hover:scale-110"
                            data-value="{{ $i }}">
                            ★
                        </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="rating-input" value="">
                    @error('rating')
                        <p class="text-rose-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Comment --}}
                <div class="mb-6">
                    <label class="block text-white/60 text-sm font-medium mb-2">
                        Your Comment
                        <span class="text-white/30 font-normal">(optional)</span>
                    </label>
                    <textarea
                        name="comment"
                        rows="4"
                        maxlength="500"
                        placeholder="Share your experience with Schedora..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 resize-none"
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-rose-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-8 py-3 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>

        {{-- EXISTING REVIEW — show if already reviewed --}}
        @else
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 max-w-2xl">
            <p class="text-white/40 text-xs uppercase tracking-wider mb-4">
                Your Review
            </p>

            {{-- Stars --}}
            <div class="flex gap-1 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <span class="text-3xl {{ $i <= $existingReview->rating ? 'text-cyan-400' : 'text-white/20' }}">
                        ★
                    </span>
                @endfor
            </div>

            {{-- Comment --}}
            @if($existingReview->comment)
            <p class="text-white/70 text-sm leading-relaxed mb-3">
                "{{ $existingReview->comment }}"
            </p>
            @endif

            {{-- Date --}}
            <p class="text-white/30 text-xs">
                Submitted {{ $existingReview->created_at->diffForHumans() }}
            </p>

            {{-- Approval badge --}}
            @if($existingReview->is_approved)
            <span class="inline-flex items-center gap-1 mt-3 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full px-3 py-1 text-xs">
                ✓ Approved
            </span>
            @else
            <span class="inline-flex items-center gap-1 mt-3 bg-amber-500/20 text-amber-400 border border-amber-500/30 rounded-full px-3 py-1 text-xs">
                ⏳ Pending Approval
            </span>
            @endif
        </div>
        @endif
    </div></div> 

    {{-- Star Rating JS --}}
    <script>
    (function() {
        const stars = document.querySelectorAll('#platform-stars .star-btn');
        const input = document.getElementById('rating-input');
        if (!stars.length) return;

        stars.forEach((star, index) => {
            // hover
            star.addEventListener('mouseover', () => {
                stars.forEach((s, i) => {
                    s.style.color = i <= index ? '#22d3ee' : 'rgba(255,255,255,0.2)';
                    s.style.transform = i <= index ? 'scale(1.1)' : 'scale(1)';
                });
            });
            // mouseout
            star.addEventListener('mouseout', () => {
                const val = parseInt(input.value) || 0;
                stars.forEach((s, i) => {
                    s.style.color = i < val ? '#22d3ee' : 'rgba(255,255,255,0.2)';
                    s.style.transform = 'scale(1)';
                });
            });
            // click
            star.addEventListener('click', () => {
                input.value = star.dataset.value;
                stars.forEach((s, i) => {
                    s.style.color = i <= index ? '#22d3ee' : 'rgba(255,255,255,0.2)';
                });
            });
        });
    })();
    </script>
@endsection
