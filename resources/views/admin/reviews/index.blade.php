@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="font-heading text-3xl font-bold text-white">Reviews Management</h1>
                <p class="text-white/60 mt-2">Manage all reviews and ratings</p>
            </div>

            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="glass-card rounded-xl p-6 mb-6">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Filter by Rating</label>
                        <select name="rating" class="bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-cyan-500">
                            <option value="" class="text-black">All Ratings</option>
                            <option value="5" class="text-black">5 Stars</option>
                            <option value="4" class="text-black">4 Stars</option>
                            <option value="3" class="text-black">3 Stars</option>
                            <option value="2" class="text-black">2 Stars</option>
                            <option value="1" class="text-black">1 Star</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-white/60 text-sm mb-2">Filter by Status</label>
                        <select name="approved" class="bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-cyan-500">
                            <option value="" class="text-black">All Status</option>
                            <option value="1" class="text-black">Approved</option>
                            <option value="0" class="text-black">Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-2 px-6 rounded-lg transition-all duration-300">
                        Filter
                    </button>
                </form>
            </div>

            <!-- Reviews Table -->
            <div class="glass-card rounded-xl p-6">
                @if($reviews->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">Provider</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">Rating</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">Comment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-white/60 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @foreach($reviews as $review)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-blue-400">
                                                        {{ substr($review->user->name ?? 'U', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-white">{{ $review->user->name ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/80">
                                            {{ $review->provider->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="{{ $i <= $review->rating ? 'text-cyan-400' : 'text-white/20' }}">★</span>
                                                @endfor
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-white/70 text-sm max-w-xs truncate">
                                                {{ $review->comment ?? 'No comment' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-white/60 text-sm">
                                            {{ $review->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($review->is_approved)
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-500/20 text-green-400 border border-green-500/30">
                                                    Approved
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">
                                                    Pending
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <!-- Approve Toggle -->
                                                <form method="POST" action="{{ route('reviews.approve', $review->id) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="is_approved" value="{{ $review->is_approved ? '0' : '1' }}">
                                                    <button type="submit" class="p-2 rounded-lg hover:bg-white/10 transition-colors {{ $review->is_approved ? 'text-yellow-400' : 'text-green-400' }}" title="{{ $review->is_approved ? 'Unapprove' : 'Approve' }}">
                                                        @if($review->is_approved)
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        @else
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                            </svg>
                                                        @endif
                                                    </button>
                                                </form>
                                                
                                                <!-- Delete -->
                                                <form method="POST" action="{{ route('reviews.destroy', $review->id) }}" onsubmit="return confirm('Are you sure you want to delete this review?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-lg hover:bg-white/10 transition-colors text-red-400" title="Delete">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-6">
                        {{ $reviews->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        <p class="mt-2 text-white/50">No reviews found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
