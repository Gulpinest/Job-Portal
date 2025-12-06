<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">Pilih Paket Langganan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <div class="bg-gray-200 rounded-lg p-8 text-center shadow-lg transform hover:scale-105 transition duration-300">
                    <h3 class="text-xl font-semibold mb-4 tracking-widest">Bronze</h3>
                    <p class="text-2xl font-bold text-gray-800 mb-6">Rp. 300.000</p>
                    <p class="text-lg text-gray-600 mb-8">6 Loker</p>
                    <form action="{{ route('subscription.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="packet" value="bronze">
                        <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-700">Pilih Paket</button>
                    </form>
                </div>

                <div class="bg-gray-200 rounded-lg p-8 text-center shadow-lg transform hover:scale-105 transition duration-300 border-2 border-gray-400">
                    <h3 class="text-xl font-semibold mb-4 tracking-widest">Silver</h3>
                    <p class="text-2xl font-bold text-gray-800 mb-6">Rp. 450.000</p>
                    <p class="text-lg text-gray-600 mb-8">13 Loker</p>
                    <form action="{{ route('subscription.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="packet" value="silver">
                        <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-700">Pilih Paket</button>
                    </form>
                </div>

                <div class="bg-gray-200 rounded-lg p-8 text-center shadow-lg transform hover:scale-105 transition duration-300">
                    <h3 class="text-xl font-semibold mb-4 tracking-widest">Gold</h3>
                    <p class="text-2xl font-bold text-gray-800 mb-6">Rp. 600.000</p>
                    <p class="text-lg text-gray-600 mb-8">25 Loker</p>
                    <form action="{{ route('subscription.buy') }}" method="POST">
                        @csrf
                        <input type="hidden" name="packet" value="gold">
                        <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded hover:bg-gray-700">Pilih Paket</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>