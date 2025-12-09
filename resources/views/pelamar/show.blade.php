<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">{{ __("Profil Saya") }}</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-8">
                        <h3 class="text-xl font-extrabold text-gray-900 mb-6 border-b pb-4">Data Pribadi & Kontak</h3>
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-2xl mr-4">{{ strtoupper(substr($pelamar->nama_pelamar ?? "U", 0, 1)) }}</div>
                            <div>
                                <p class="text-2xl font-extrabold text-gray-900">{{ $pelamar->nama_pelamar ?? "Pelamar Baru" }}</p>
                                <p class="text-sm text-gray-500">{{ Auth::user()->email ?? "-" }}</p>
                            </div>
                        </div>
                        <dl class="divide-y divide-gray-100">
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600">Status Pekerjaan</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">{{ $pelamar->status_pekerjaan ?? "Belum Diisi" }}</dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600">No. Telepon</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">{{ $pelamar->no_telp ?? "-" }}</dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600">Alamat</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">{{ $pelamar->alamat ?? "-" }}</dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600">Jenis Kelamin</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">{{ $pelamar->jenis_kelamin ?? "-" }}</dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-600">Tanggal Lahir</dt>
                                <dd class="col-span-2 text-sm text-gray-900 font-medium">{{ $pelamar->tgl_lahir ? \Carbon\Carbon::parse($pelamar->tgl_lahir)->format("d F Y") : "-" }}</dd>
                            </div>
                        </dl>
                        <div class="mt-8">
                            <a href="{{ route("pelamar.edit") }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Edit Data Diri
                            </a>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-4 border-b pb-3">Ringkasan Resume</h3>
                        <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-lg mb-4">
                            <span class="text-sm font-semibold text-indigo-700">Total Resume:</span>
                            <span class="text-xl font-extrabold text-indigo-900">{{ $pelamar->resumes_count ?? 0 }}</span>
                        </div>
                        <a href="{{ route("resumes.index") }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-indigo-500 text-white text-sm font-semibold rounded-xl hover:bg-indigo-600 shadow-md">Lihat Semua Resume</a>
                    </div>
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6">
                        <h3 class="text-lg font-extrabold text-gray-900 mb-4 border-b pb-3">Pengaturan Akun</h3>
                        <div class="space-y-3">
                            <a href="{{ route("profile.edit") }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-100 border border-gray-300 rounded-xl font-semibold text-sm text-gray-800 hover:bg-gray-200 shadow-sm">Ubah Password & Email</a>
                            <form action="{{ route("pelamar.destroy") }}" method="POST" onsubmit="return confirm("Hapus akun permanen?");">
                                @csrf
                                @method("DELETE")
                                <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-3 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 shadow-md">Hapus Akun</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-8 bg-white shadow rounded-lg mt-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Skill & Keahlian</h3>
                    <a href="{{ route("skills.create") }}" class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">Tambah</a>
                </div>
                @if (count($skills) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($skills as $skill)
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gray-900">{{ $skill->nama_skill }}</h4>
                                    @php
                                        $colors = ["Beginner" => "bg-blue-100 text-blue-800", "Intermediate" => "bg-yellow-100 text-yellow-800", "Advanced" => "bg-orange-100 text-orange-800", "Expert" => "bg-red-100 text-red-800"];
                                        $class = $colors[$skill->level] ?? "bg-gray-100 text-gray-800";
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold {{ $class }}">{{ $skill->level }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">{{ $skill->years_experience ?? 0 }} tahun</p>
                                <div class="flex gap-2">
                                    <a href="{{ route("skills.edit", $skill->id_skill) }}" class="flex-1 inline-flex justify-center px-2 py-1 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-600">Edit</a>
                                    <form action="{{ route("skills.destroy", $skill->id_skill) }}" method="POST" class="flex-1" onsubmit="return confirm("Hapus?");">
                                        @csrf
                                        @method("DELETE")
                                        <button type="submit" class="w-full inline-flex justify-center px-2 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <p class="text-gray-500 mb-4">Belum ada skill</p>
                        <a href="{{ route("skills.create") }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">Tambah Skill</a>
                    </div>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <a href="{{ route("skills.index") }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-700 text-sm font-medium">Kelola Semua Skill </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
