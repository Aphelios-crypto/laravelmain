<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Admin – All Bookings
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- ✅ Success Message --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- ✅ Bookings Table --}}
        <div class="bg-white shadow rounded p-4">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="text-left p-3">User</th>
                        <th class="text-left p-3">Title</th>
                        <th class="text-left p-3">Date</th>
                        <th class="text-left p-3">Time</th>
                        <th class="text-left p-3">Status</th>
                        <th class="text-left p-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bookings as $booking)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $booking->user->name ?? 'N/A' }}</td>
                            <td class="p-3">{{ $booking->title }}</td>
                            <td class="p-3">{{ $booking->date }}</td>
                            <td class="p-3">{{ $booking->time }}</td>
                            <td class="p-3 capitalize">
                                {{-- Color-coded status --}}
                                @php
                                    $statusColors = [
                                        'pending' => 'text-yellow-600',
                                        'completed' => 'text-green-600',
                                        'cancelled' => 'text-red-600',
                                    ];
                                @endphp
                                <span class="{{ $statusColors[$booking->status] ?? 'text-gray-600' }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="p-3">
                                <form method="POST" 
                                      action="{{ route('admin.bookings.destroy', $booking) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this booking?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 p-4">
                                No bookings found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- ✅ Pagination --}}
            <div class="mt-4">
                {{ $bookings->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
