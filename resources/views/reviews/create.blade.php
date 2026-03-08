@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-2xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Leave a Review</h1>
                <p class="text-white/60 mt-2">Share your experience with {{ $appointment->provider->user->name ?? 'your provider' }}</p>
            </div>

            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="glass-card rounded-xl p-8">
                <!-- Appointment Info -->
                <div class="bg-white/5 rounded-lg p-4 mb-6 border border-white/10">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white/60 text-sm">Service</p>
                            <p class="text-white font-medium">{{ $appointment->service->name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white/60 text-sm">Date</p>
                            <p class="text-white font-medium">{{ \Carbon\Carbon::parse($appointment->start_time)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('reviews.store') }}" method="POST">
                    @csrf

                    <input type="hidden" name="appointment_id" value="{{ $appointment->id }}">

                    <!-- Star Rating -->
                    <div class="mb-6">
                        <label class="block text-white font-medium mb-3">Rating</label>
                        <div class="flex items-center gap-2" id="star-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <button type="button" 
                                    class="star-btn text-3xl transition-all duration-200 hover:scale-110 focus:outline-none"
                                    data-value="{{ $i }}"
                                    onclick="selectRating({{ $i }})">
                                    <span class="text-white/20">★</span>
                                </button>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="">
                        @error('rating')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                        <p class="text-white/50 text-sm mt-2" id="rating-text">Click to select a rating</p>
                    </div>

                    <!-- Comment -->
                    <div class="mb-6">
                        <label for="comment" class="block text-white font-medium mb-3">Your Review (Optional)</label>
                        <textarea 
                            name="comment" 
                            id="comment" 
                            rows="4" 
                            class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-white/40 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition-colors"
                            placeholder="Share your experience..."
                            maxlength="1000"
                        >{{ old('comment') }}</textarea>
                        <p class="text-white/40 text-xs mt-1">Maximum 1000 characters</p>
                        @error('comment')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex gap-4">
                        <a href="{{ route('client.appointments') }}" class="flex-1 text-center py-3 px-6 rounded-lg border border-white/20 text-white/70 hover:bg-white/5 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 hover:shadow-lg hover:shadow-cyan-500/25">
                            Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function selectRating(rating) {
            document.getElementById('rating-input').value = rating;
            
            const stars = document.querySelectorAll('.star-btn');
            const ratingTexts = ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'];
            
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.innerHTML = '<span class="text-cyan-400">★</span>';
                } else {
                    star.innerHTML = '<span class="text-white/20">★</span>';
                }
            });
            
            document.getElementById('rating-text').textContent = ratingTexts[rating];
            document.getElementById('rating-text').classList.add('text-cyan-400');
        }
    </script>
@endsection
