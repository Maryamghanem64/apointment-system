@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-2xl font-bold text-white">{{ __('Welcome back, ') }} {{ Auth::user()->name }}!</h3>
                <p class="text-white/60 mt-2">{{ __('Manage your appointments and book new services.') }}</p>
            </div>
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Upcoming Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $upcomingAppointments->count() }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Past Appointments') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $pastAppointments }}</div>
                </div>
                
                <div class="glass-card rounded-xl p-6">
                    <div class="text-sm font-medium text-white/50">{{ __('Completed Services') }}</div>
                    <div class="text-3xl font-bold text-white mt-1">{{ $totalSpent }}</div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <a href="{{ route('client.appointments') }}" class="btn-primary text-white font-semibold py-4 px-6 rounded-xl text-center">
                    {{ __('View My Appointments') }}
                </a>
                <a href="{{ route('client.profile') }}" class="btn-secondary text-white font-semibold py-4 px-6 rounded-xl text-center border border-white/20">
                    {{ __('My Profile') }}
                </a>
            </div>
            
            <!-- My Reviews Section -->
            <div class="glass-card rounded-xl p-6 mb-8">
                <h3 class="font-heading text-lg font-semibold text-white mb-4">{{ __('My Reviews') }}</h3>
                
                @if($myReviews->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($myReviews as $review)
                            <div class="bg-white/5 rounded-lg p-4 border border-white/10">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="text-white font-medium">{{ $review->provider->user->name ?? 'N/A' }}</div>
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
                    @if($myReviews->count() >= 5)
                        <div class="mt-4 text-center">
                            <a href="{{ route('client.appointments') }}" class="text-cyan-400 hover:text-cyan-300 text-sm font-medium">
                                {{ __('View All Reviews') }} →
                            </a>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-white/50">{{ __('No reviews yet. Complete an appointment to leave a review!') }}</p>
                    </div>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Provider') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Service') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Date & Time') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">{{ __('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($upcomingAppointments as $appointment)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-white">
                                            {{ $appointment->provider->user->name ?? 'N/A' }}
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
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="py-4">
                                            <div class="flex items-center gap-2 mt-3">
                                                @php
                                                    $steps = ['pending' => 1, 'confirmed' => 2, 'paid' => 3, 'completed' => 4];
                                                    $currentStep = $steps[$appointment->status] ?? 1;
                                                @endphp
                                                
                                                @foreach(['Pending','Confirmed','Paid','Completed'] as $i => $label)
                                                <div class="flex items-center gap-1">
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                                        {{ $currentStep > $i ? 'bg-cyan-400 text-slate-900' : ($currentStep == $i+1 ? 'bg-cyan-400/30 text-cyan-400 border border-cyan-400' : 'bg-white/5 text-white/30') }}">
                                                        {{ $currentStep > $i ? '✓' : $i+1 }}
                                                    </div>
                                                    <span class="text-xs {{ $currentStep >= $i+1 ? 'text-white/70' : 'text-white/20' }}">
                                                        {{ $label }}
                                                    </span>
                                                    @if($i < 3)
                                                        <div class="w-6 h-px {{ $currentStep > $i+1 ? 'bg-cyan-400' : 'bg-white/10' }}"></div>
                                                    @endif
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-white/50">{{ __('No upcoming appointments.') }}</p>
                    <a href="{{ route('services.index') }}" class="mt-2 inline-block text-cyan-400 hover:text-cyan-300">
                        {{ __('Browse Services') }} →
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Platform Review Form --}}
<div class="mt-10 flex flex-col items-center text-center">
    <h2 class="text-2xl font-bold text-white mb-1" style="font-family: 'Syne', sans-serif;">
        Rate Your Experience
    </h2>
    <p class="text-white/50 text-sm mb-6">
        Share your feedback about Schedora
    </p>

    @if(!\App\Models\Review::where('user_id', auth()->id())->where('review_type', 'platform')->exists())
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 w-full max-w-2xl mx-auto">
            @if(session('success'))

                <div class="mb-6 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 rounded-xl px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="review_type" value="platform">

                <div class="mb-6">
                    <label class="block text-white/60 text-sm font-medium mb-3">
                        Your Rating
                    </label>
                    <div class="flex gap-2 justify-center" id="star-container">

                        @for($i = 1; $i <= 5; $i++)
                            <button type="button"
                                class="star-btn text-4xl text-white/20 hover:text-cyan-400 transition-all duration-200 cursor-pointer"
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

                <div class="mb-6">
                    <label class="block text-white/60 text-sm font-medium mb-2">
                        Your Comment <span class="text-white/30">(optional)</span>
                    </label>
                    <textarea
                        name="comment"
                        rows="4"
                        placeholder="Share your experience with Schedora..."
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 resize-none"
                    >{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-rose-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-center">
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium rounded-xl px-8 py-3 hover:shadow-lg hover:shadow-cyan-500/25 hover:-translate-y-0.5 transition-all duration-300">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
@else
        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 w-full max-w-2xl mx-auto">
            <p class="text-white/40 text-sm mb-3">Your review</p>

            @php
                $myReview = \App\Models\Review::where('user_id', auth()->id())->where('review_type', 'platform')->first();
            @endphp
            <div class="flex gap-1 mb-3 justify-center">
                @for($i = 1; $i <= 5; $i++)
                    <span class="text-2xl {{ $i <= $myReview->rating ? 'text-cyan-400' : 'text-white/20' }}">★</span>
                @endfor
            </div>

            @if($myReview->comment)
                <p class="text-white/70 text-sm">{{ $myReview->comment }}</p>
            @endif
            <p class="text-white/30 text-xs mt-3">Submitted {{ $myReview->created_at->diffForHumans() }}</p>
        </div>
    @endif
</div>


<script>
const platformStars = document.querySelectorAll('#star-container .star-btn');
platformStars.forEach((star, index) => {
    star.addEventListener('mouseover', function() {
        platformStars.forEach((s, i) => {
            s.style.color = i <= index ? '#22d3ee' : 'rgba(255,255,255,0.2)';
        });
    });
    star.addEventListener('mouseout', function() {
        const selected = document.getElementById('rating-input').value;
        platformStars.forEach((s, i) => {
            s.style.color = i < selected ? '#22d3ee' : 'rgba(255,255,255,0.2)';
        });
    });
    star.addEventListener('click', function() {
        document.getElementById('rating-input').value = this.dataset.value;
        platformStars.forEach((s, i) => {
            s.style.color = i <= index ? '#22d3ee' : 'rgba(255,255,255,0.2)';
        });
    });
});
</script>
@endsection
