{{-- resources/views/admin/reviews/index.blade.php --}}
@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Reviews Management</h1>
                <p class="text-white/60 mt-2">Manage all platform and provider reviews</p>
            </div>

            <!-- Success Message -->
@if(session('success'))
                <div class="bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Search bar --}}
            <div class="mb-6 flex flex-col md:flex-row gap-3">
                <input
                    type="text"
                    id="tableSearch"
                    placeholder="Search..."
                    class="bg-white/5 border border-white/10 rounded-xl px-4 py-2.5 text-white placeholder-white/30 focus:outline-none focus:border-cyan-400 focus:ring-1 focus:ring-cyan-400/30 transition-all duration-300 w-full md:max-w-sm">
            </div>

            <script>
            document.getElementById('tableSearch').addEventListener('input', function() {
                const search = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(search) ? '' : 'none';
                });
            });
            </script>

            <!-- Statistics Cards -->

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Reviews -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-blue-500/20 p-3">
                                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">Total Reviews</dt>
                                <dd class="text-2xl font-bold text-white">{{ $stats['total'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Platform Reviews -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-cyan-500/20 p-3">
                                <svg class="h-6 w-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">Platform Reviews</dt>
                                <dd class="text-2xl font-bold text-white">{{ $stats['platform'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Provider Reviews -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-purple-500/20 p-3">
                                <svg class="h-6 w-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">Provider Reviews</dt>
                                <dd class="text-2xl font-bold text-white">{{ $stats['provider'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Average Rating -->
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="rounded-xl bg-amber-500/20 p-3">
                                <svg class="h-6 w-6 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-white/50 truncate">Average Rating</dt>
                                <dd class="text-2xl font-bold text-white flex items-center">
                                    {{ $stats['average'] }}
                                    <span class="text-amber-400 ml-1">★</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 mb-8">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Type</label>
                        <select name="type" class="min-w-[150px]">
                            <option value="">All Types</option>
                            <option value="platform" {{ request('type') === 'platform' ? 'selected' : '' }}>Platform</option>
                            <option value="provider" {{ request('type') === 'provider' ? 'selected' : '' }}>Provider</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Rating</label>
                        <select name="rating" class="min-w-[150px]">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Status</label>
                        <select name="status" class="min-w-[150px]">
                            <option value="">All Status</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="hidden" {{ request('status') === 'hidden' ? 'selected' : '' }}>Hidden</option>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-2.5 px-5 rounded-xl text-sm transition-all duration-300">
                            Apply Filters
                        </button>
                    </div>
                    <div>
                        <a href="{{ route('admin.reviews.index') }}" class="text-white/60 hover:text-white text-sm underline">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            <!-- Reviews Table -->
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">User</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Provider</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Rating</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Comment</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Featured</th>
                                <th class="px-6 py-4 text-left text-xs font-medium text-white/40 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($reviews as $review)
                                <tr class="hover:bg-white/10 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                <span class="text-sm font-medium text-blue-400">
                                                    {{ substr($review->user->name ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-white">{{ $review->user->name ?? 'N/A' }}</div>
                                                <div class="text-xs text-white/40">{{ $review->user->roles->first()->name ?? 'User' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->review_type === 'platform')
                                            <span class="bg-blue-500/20 text-blue-400 border border-blue-500/30 px-3 py-1 rounded-lg text-xs font-medium">
                                                Platform
                                            </span>
                                        @else
                                            <span class="bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 px-3 py-1 rounded-lg text-xs font-medium">
                                                Provider
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->provider)
                                            <div class="text-sm text-white/80">{{ $review->provider->user->name ?? 'N/A' }}</div>
                                        @else
                                            <span class="text-white/30 text-sm">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <span class="text-cyan-400 text-xl">★</span>
                                                @else
                                                    <span class="text-white/20 text-xl">★</span>
                                                @endif
                                            @endfor
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-white/70 max-w-xs truncate">
                                            {{ $review->comment ? Str::limit($review->comment, 80) : 'No comment' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-white/60">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->is_approved)
                                            <span class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-1 rounded-lg text-xs font-medium">
                                                Approved
                                            </span>
                                        @else
                                            <span class="bg-white/5 text-white/40 border border-white/10 px-3 py-1 rounded-lg text-xs font-medium">
                                                Hidden
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($review->is_featured)
                                            <span class="bg-amber-500/20 text-amber-400 border border-amber-500/30 px-3 py-1 rounded-lg text-xs font-medium">
                                                Featured
                                            </span>
                                        @else
                                            <span class="bg-white/5 text-white/40 border border-white/10 px-3 py-1 rounded-lg text-xs font-medium">
                                                Normal
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <!-- Approve Toggle -->
                                            <form method="POST" action="{{ route('admin.reviews.approve', $review->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="{{ $review->is_approved ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-white/5 text-white/40 border border-white/10' }} px-3 py-1 rounded-lg text-xs font-medium hover:opacity-80 transition-opacity">
                                                    {{ $review->is_approved ? 'Hide' : 'Approve' }}
                                                </button>
                                            </form>
                                            
                                            <!-- Featured Toggle -->
                                            <form method="POST" action="{{ route('admin.reviews.featured', $review->id) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="{{ $review->is_featured ? 'bg-amber-500/20 text-amber-400 border border-amber-500/30' : 'bg-white/5 text-white/40 border border-white/10' }} px-3 py-1 rounded-lg text-xs font-medium hover:opacity-80 transition-opacity">
                                                    {{ $review->is_featured ? 'Unfeature' : 'Feature' }}
                                                </button>
                                            </form>
                                            
                                            <!-- Delete Button -->
                                            <form method="POST" action="{{ route('admin.reviews.destroy', $review->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-500/20 text-rose-400 border border-rose-500/30 rounded-lg px-3 py-1 text-xs font-medium hover:bg-rose-500/30 transition-colors">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center">
                                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                        <p class="mt-2 text-sm text-white/50">No reviews found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($reviews->hasPages())
                    <div class="px-6 py-4 border-t border-white/10">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

