<x-app-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <h2 class="text-2xl font-bold mb-4 text-green-600">Payment Canceled!</h2>
        <p class="mb-6">You have cancelled the transaction.</p>
        <a href="{{ route('student.dashboard') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200">Go to Dashboard</a>
    </div>
</x-app-layout>