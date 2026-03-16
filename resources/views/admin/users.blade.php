@extends('layouts.dark-app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <!-- Page Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="font-heading text-2xl font-bold text-white">Manage Users</h1>
                    <p class="text-white/50 mt-2 text-sm">View and manage all system users</p>
                </div>
                <a href="{{ route('users.create') }}" class="inline-flex items-center bg-gradient-to-r from-blue-500 to-cyan-400 hover:from-blue-600 hover:to-cyan-500 text-white font-semibold py-3 px-6 rounded-xl transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-cyan-500/25">
                    <svg class="mr-2 -ml-1 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Add New User') }}
                </a>
            </div>
            
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
            
            <div class="glass-card rounded-2xl p-6">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('ID') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Name') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Email') }}</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Role') }}</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-white/40 uppercase tracking-wider">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($users as $user)
                                <tr class="hover:bg-white/10 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-white/60">{{ $user->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-white">{{ $user->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-white/80">{{ $user->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($user->roles as $role)
                                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </td>
<td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('users.edit', $user->id) }}" class="text-cyan-400 hover:text-cyan-300 mr-4 font-medium">{{ __('Edit') }}</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-400 hover:text-rose-300 font-medium" onclick="return confirm('Are you sure?')">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-white/50">{{ __('No users found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
