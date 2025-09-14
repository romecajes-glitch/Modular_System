<x-app-layout>
    <div class="p-6 text-center">
        <h1 class="text-3xl font-bold text-green-600 mb-4">Payment Successful!</h1>
        <p class="text-lg text-gray-700 mb-6">Your session has been credited to your account.</p>
        <a href="{{ route('student.dashboard') }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">Return to Dashboard</a>
    </div>
</x-app-layout>