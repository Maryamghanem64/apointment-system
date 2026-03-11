
{{-- resources/views/partials/platform-review-form.blade.php --}}
{{-- Platform Review Form - for all users (admin, provider, client) --}}

@php
$hasPlatformReview = \App\Models\Review::where('user_id', auth()->id())
    ->where('review_type', 'platform')
    ->exists();
$userPlatformReview = \App\Models\Review::where('user_id', auth()->id())
    ->where('review_type', 'platform')
    ->first();
@endphp

<div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/20 to-cyan-500/20 flex items-center justify-center border border-white/10">
            <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
        </div>
        <div>
            <h3 class="text-xl font-heading font-semibold text-white">Rate Your Experience</h3>
            <p class="text-white/50 text-sm">Share your thoughts about Schedora</p>
        </div>
    </div>

    @if(!$hasPlatformReview)
        {{-- Show Form --}}
        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="review_type" value="platform">
            
            <!-- Star Rating -->
            <div>
                <label class="block text-white/60 text-sm mb-3">Your Rating</label>
                <div class="flex items-center gap-2" id="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" 
                                class="star-btn text-3xl transition-all duration-200 hover:scale-110"
                                data-rating="{{ $i }}">
                            ★
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input" required>
                @error('rating')
                    <p class="text-rose-400 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Comment -->
            <div>
                <label class="block text-white/60 text-sm mb-3">Your Review (optional)</label>
                <textarea 
                    name="comment" 
                    rows="4" 
                    class="w-full bg-white/5 border border-white/10 rounded-xl p-4 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/20 transition-all duration-300 resize-none"
                    placeholder="Share your experience with Schedora..."
                    maxlength="500"
                ></textarea>
                <p class="text-white/30 text-xs mt-1 text-right"><span id="char-count">0</span>/500</p>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit"
                class="w-full bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:shadow-lg hover:shadow-cyan-500/25"
            >
                Submit Review
            </button>
        </form>
    @else
        {{-- Show Existing Review --}}
        <div class="space-y-4">
            <div class="flex items-center gap-1 mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <span class="{{ $i <= $userPlatformReview->rating ? 'text-cyan-400' : 'text-white/20' }} text-xl">★</span>
                @endfor
            </div>
            @if($userPlatformReview->comment)
                <p class="text-white/70 leading-relaxed">{{ $userPlatformReview->comment }}</p>
            @else
                <p class="text-white/40 italic">No comment provided</p>
            @endif
            <div class="text-white/40 text-sm">
                Reviewed on {{ $userPlatformReview->created_at->format('M d, Y') }}
            </div>
            @if($userPlatformReview->is_approved)
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                    Approved
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-amber-500/20 text-amber-400 border border-amber-500/30">
                    Pending Approval
                </span>
            @endif
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const starButtons = document.querySelectorAll('.star-btn');
        const ratingInput = document.getElementById('rating-input');
        const charCount = document.getElementById('char-count');
        const textarea = document.querySelector('textarea[name="comment"]');

        // Star rating functionality
        starButtons.forEach((btn, index) => {
            btn.addEventListener('click', function() {
                const rating = this.dataset.rating;
                ratingInput.value = rating;
                
                starButtons.forEach((star, i) => {
                    if (i < rating) {
                        star.classList.add('text-cyan-400');
                        star.classList.remove('text-white/20');
                    } else {
                        star.classList.remove('text-cyan-400');
                        star.classList.add('text-white/20');
                    }
                });
            });

            btn.addEventListener('mouseenter', function() {
                const rating = this.dataset.rating;
                starButtons.forEach((star, i) => {
                    if (i < rating) {
                        star.classList.add('text-cyan-400');
                        star.classList.remove('text-white/20');
                    }
                });
            });

            btn.addEventListener('mouseleave', function() {
                const currentRating = ratingInput.value || 0;
                starButtons.forEach((star, i) => {
                    if (i < currentRating) {
                        star.classList.add('text-cyan-400');
                        star.classList.remove('text-white/20');
                    } else {
                        star.classList.remove('text-cyan-400');
                        star.classList.add('text-white/20');
                    }
                });
            });
        });

        // Character counter
        if (textarea) {
            textarea.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }
    });
</script>
@endpush

<style>
    .star-btn {
        color: #525252;
    }
    .star-btn:hover {
        color: #22d3ee;
    }
</style>

