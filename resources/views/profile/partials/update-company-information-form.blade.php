<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Perusahaan') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update informasi perusahaan Anda.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Company Name --}}
        <div>
            <x-input-label for="nama_perusahaan" :value="__('Nama Perusahaan')" />
            <x-text-input 
                id="nama_perusahaan" 
                name="nama_perusahaan" 
                type="text" 
                class="mt-1 block w-full" 
                :value="old('nama_perusahaan', $company->nama_perusahaan)" 
                required 
                autofocus 
                autocomplete="organization" 
            />
            <x-input-error class="mt-2" :messages="$errors->get('nama_perusahaan')" />
        </div>

        {{-- Company Phone --}}
        <div>
            <x-input-label for="no_telp_perusahaan" :value="__('Nomor Telepon')" />
            <x-text-input 
                id="no_telp_perusahaan" 
                name="no_telp_perusahaan" 
                type="tel" 
                class="mt-1 block w-full" 
                :value="old('no_telp_perusahaan', $company->no_telp_perusahaan)" 
                placeholder="Contoh: +62-812-3456-7890" 
            />
            <x-input-error class="mt-2" :messages="$errors->get('no_telp_perusahaan')" />
        </div>

        {{-- Company Address --}}
        <div>
            <x-input-label for="alamat_perusahaan" :value="__('Alamat Perusahaan')" />
            <textarea 
                id="alamat_perusahaan" 
                name="alamat_perusahaan" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                rows="4"
                placeholder="Masukkan alamat lengkap perusahaan..."
            >{{ old('alamat_perusahaan', $company->alamat_perusahaan) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('alamat_perusahaan')" />
        </div>

        {{-- Company Description --}}
        <div>
            <x-input-label for="desc_company" :value="__('Deskripsi Perusahaan')" />
            <textarea 
                id="desc_company" 
                name="desc_company" 
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                rows="5"
                placeholder="Ceritakan tentang perusahaan Anda, visi, misi, dan budaya kerja..."
            >{{ old('desc_company', $company->desc_company) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('desc_company')" />
        </div>

        {{-- Verification Status (Read-only) --}}
        <div>
            <x-input-label :value="__('Status Verifikasi')" />
            <div class="mt-2 p-4 rounded-lg {{ $company->is_verified ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold {{ $company->is_verified ? 'text-green-800' : 'text-amber-800' }}">
                            @if ($company->is_verified)
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    Terverifikasi
                                </span>
                            @else
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                    Menunggu Verifikasi
                                </span>
                            @endif
                        </p>
                        @if ($company->is_verified && $company->verified_at)
                            <p class="text-sm {{ $company->is_verified ? 'text-green-700' : 'text-amber-700' }} mt-1">
                                Diverifikasi pada: {{ $company->verified_at->format('d M Y H:i') }}
                            </p>
                        @else
                            <p class="text-sm {{ $company->is_verified ? 'text-green-700' : 'text-amber-700' }} mt-1">
                                Menunggu persetujuan dari administrator
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-2">Status verifikasi ditetapkan oleh administrator dan tidak dapat diubah dari sini.</p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-green-600"
                >{{ __('Berhasil disimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
