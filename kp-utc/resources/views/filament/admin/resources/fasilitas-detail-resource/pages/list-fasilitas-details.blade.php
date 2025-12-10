<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Search Form Section -->
        <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1.5rem;">
            <h2 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 1.5rem; color: #111827;">Cari Ketersediaan Fasilitas</h2>
            
            <form wire:submit="search" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <!-- Tanggal Mulai -->
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            Tanggal & Jam Mulai
                        </label>
                        <input
                            type="datetime-local"
                            wire:model="tanggal_mulai"
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                            required
                        />
                        @error('tanggal_mulai')
                            <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai -->
                    <div>
                        <label style="display: block; font-weight: 500; color: #374151; margin-bottom: 0.5rem; font-size: 0.875rem;">
                            Tanggal & Jam Selesai
                        </label>
                        <input
                            type="datetime-local"
                            wire:model="tanggal_selesai"
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem; font-size: 0.875rem;"
                            required
                        />
                        @error('tanggal_selesai')
                            <p style="color: #dc2626; font-size: 0.875rem; margin-top: 0.25rem;">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Search Button -->
                <div>
                    <button
                        type="submit"
                        style="background-color: #A8DE30; color: white; padding: 0.5rem 1.5rem; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; transition: background-color 0.15s ease;"
                        onmouseover="this.style.backgroundColor='#96C81E'"
                        onmouseout="this.style.backgroundColor='#A8DE30'"
                    >
                        Cari Fasilitas
                    </button>
                </div>
            </form>
        </div>

        <!-- Results Section -->
        @if ($this->hasSearched)
            @php
                $facilities = $this->availableFacilities;
                $hasResults = false;
                foreach ($facilities as $category) {
                    if (!empty($category)) {
                        $hasResults = true;
                        break;
                    }
                }
            @endphp

            @if (!$hasResults)
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 0.5rem; padding: 1.5rem; text-align: center;">
                    <p style="color: #991b1b; font-size: 0.875rem;">
                        Tidak ada fasilitas yang tersedia untuk periode yang dipilih
                    </p>
                </div>
            @else
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <!-- Hall Category -->
                    @if (!empty($facilities['hall']))
                        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                            <div style="background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">Hall</h2>
                                    <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: black;">
                                        {{ count($facilities['hall']) }} fasilitas
                                    </span>
                                </div>
                            </div>
                            <div style="background-color: #f9fafb; padding: 1.5rem;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                                    @foreach ($facilities['hall'] as $fasilitas)
                                        @include('filament.admin.resources.fasilitas-detail-resource.pages.partials.facility-detail-card', ['fasilitas' => $fasilitas])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Cottage Category -->
                    @if (!empty($facilities['cottage']))
                        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                            <div style="background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">Cottage</h2>
                                    <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: black;">
                                        {{ count($facilities['cottage']) }} fasilitas
                                    </span>
                                </div>
                            </div>
                            <div style="background-color: #f9fafb; padding: 1.5rem;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                                    @foreach ($facilities['cottage'] as $fasilitas)
                                        @include('filament.admin.resources.fasilitas-detail-resource.pages.partials.facility-detail-card', ['fasilitas' => $fasilitas])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- VIP Cottage Category -->
                    @if (!empty($facilities['vip_cottage']))
                        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                            <div style="background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">VIP Cottage</h2>
                                    <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: black;">
                                        {{ count($facilities['vip_cottage']) }} fasilitas
                                    </span>
                                </div>
                            </div>
                            <div style="background-color: #f9fafb; padding: 1.5rem;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                                    @foreach ($facilities['vip_cottage'] as $fasilitas)
                                        @include('filament.admin.resources.fasilitas-detail-resource.pages.partials.facility-detail-card', ['fasilitas' => $fasilitas])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Others Category -->
                    @if (!empty($facilities['others']))
                        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                            <div style="background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;">
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">Others</h2>
                                    <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: black;">
                                        {{ count($facilities['others']) }} fasilitas
                                    </span>
                                </div>
                            </div>
                            <div style="background-color: #f9fafb; padding: 1.5rem;">
                                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                                    @foreach ($facilities['others'] as $fasilitas)
                                        @include('filament.admin.resources.fasilitas-detail-resource.pages.partials.facility-detail-card', ['fasilitas' => $fasilitas])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </div>
</x-filament-panels::page>
