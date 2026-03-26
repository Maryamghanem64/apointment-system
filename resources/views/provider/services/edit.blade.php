@extends('layouts.dark-app')

@section('title', 'Edit Service')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto px-6 lg:px-8">
        <div class="flex items-center mb-8">
            <a href="{{ route('provider.services.index') }}" class="text-white/60 hover:text-white p-2 -m-2 rounded-lg transition">
                ← Back to Services
            </a>
            <h1 class="text-3xl font-bold text-white ml-4">Edit Service</h1>
        </div>

        <div class="glass-card rounded-xl p-8">
            <form method="POST" action="{{ route('provider.services.update', $service) }}">
                @csrf @method('PUT')

                <div class="space-y-6">
                    <div>
                        <label class="block text-white/60 font-medium mb-2">Service Name <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" required 
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cyan-400 focus:ring-1 transition">
                        @error('name') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-white/60 font-medium mb-2">Price ($) <span class="text-rose-400">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $service->price) }}" required 
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cyan-400 focus:ring-1 transition">
                            @error('price') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-white/60 font-medium mb-2">Duration (minutes) <span class="text-rose-400">*</span></label>
                            <input type="number" min="1" name="duration" value="{{ old('duration', $service->duration) }}" required 
                                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cyan-400 focus:ring-1 transition">
                            @error('duration') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-white/60 font-medium mb-2">Description (optional)</label>
                        <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/30 focus:border-cyan-400 focus:ring-1 transition">{{ old('description', $service->description) }}</textarea>
                        @error('description') <p class="text-rose-400 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-4 mt-8">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-blue-500 to-cyan-400 text-white font-medium py-3 rounded-xl hover:shadow-lg transition-all">
                        Update Service
                    </button>
                    <a href="{{ route('provider.services.index') }}" class="flex-1 text-center text-white/60 py-3 border border-white/10 rounded-xl hover:text-white hover:bg-white/5 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
