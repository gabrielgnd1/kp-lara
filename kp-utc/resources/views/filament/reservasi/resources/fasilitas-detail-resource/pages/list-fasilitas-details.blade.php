<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Search Form -->
        <div style="background-color: white; border-radius: 8px; padding: 20px; border: 1px solid #e5e7eb;">
            <h2 style="font-size: 18px; font-weight: 600; margin-bottom: 16px; color: #1f2937;">
                Cari Fasilitas
            </h2>

            <form wire:submit="search" class="space-y-4">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">
                            Tanggal & Waktu Mulai
                        </label>
                        <input
                            type="datetime-local"
                            wire:model="tanggal_mulai"
                            style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        />
                        @error('tanggal_mulai')
                            <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label style="display: block; font-size: 14px; font-weight: 500; color: #374151; margin-bottom: 6px;">
                            Tanggal & Waktu Selesai
                        </label>
                        <input
                            type="datetime-local"
                            wire:model="tanggal_selesai"
                            style="width: 100%; padding: 8px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 14px;"
                        />
                        @error('tanggal_selesai')
                            <span style="color: #dc2626; font-size: 12px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <button
                    type="submit"
                    style="background-color: #A8DE30; color: black; padding: 10px 20px; border-radius: 6px; font-weight: 600; cursor: pointer; border: none; font-size: 14px;"
                    onmouseover="this.style.backgroundColor='#96C81E'"
                    onmouseout="this.style.backgroundColor='#A8DE30'"
                >
                    Cari Fasilitas
                </button>
            </form>
        </div>

        <!-- Results Section -->
        @if ($hasSearched)
            @php $facilities = $this->facilities; @endphp

            <!-- Hall Category -->
            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                <button
                    wire:click="$toggle('expandedGroups.hall')"
                    style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;"
                    onmouseover="this.style.backgroundColor='#96C81E'"
                    onmouseout="this.style.backgroundColor='#A8DE30'"
                >
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;"> Hall</h2>
                    </div>
                    <span style="font-size: 0.875rem; background-color: rgba(0,0,0,0.2); padding: 0.25rem 0.75rem; border-radius: 9999px;">
                        {{ count($facilities['hall'] ?? []) }} facilities
                    </span>
                </button>
                @if ($expandedGroups['hall'] && count($facilities['hall'] ?? []) > 0)
                    <div style="background-color: #f9fafb; padding: 1.5rem;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                            @foreach ($facilities['hall'] as $facilityName => $variants)
                                @include('filament.admin.resources.super-admin-fasilitas-resource.pages.partials.facility-card', ['variants' => $variants])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Cottage Category -->
            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                <button
                    wire:click="$toggle('expandedGroups.cottage')"
                    style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;"
                    onmouseover="this.style.backgroundColor='#96C81E'"
                    onmouseout="this.style.backgroundColor='#A8DE30'"
                >
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;"> Cottage</h2>
                    </div>
                    <span style="font-size: 0.875rem; background-color: rgba(0,0,0,0.2); padding: 0.25rem 0.75rem; border-radius: 9999px;">
                        {{ count($facilities['cottage'] ?? []) }} facilities
                    </span>
                </button>
                @if ($expandedGroups['cottage'] && count($facilities['cottage'] ?? []) > 0)
                    <div style="background-color: #f9fafb; padding: 1.5rem;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                            @foreach ($facilities['cottage'] as $facilityName => $variants)
                                @include('filament.admin.resources.super-admin-fasilitas-resource.pages.partials.facility-card', ['variants' => $variants])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- VIP Cottage Category -->
            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                <button
                    wire:click="$toggle('expandedGroups.vip_cottage')"
                    style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;"
                    onmouseover="this.style.backgroundColor='#96C81E'"
                    onmouseout="this.style.backgroundColor='#A8DE30'"
                >
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;"> VIP Cottage</h2>
                    </div>
                    <span style="font-size: 0.875rem; background-color: rgba(0,0,0,0.2); padding: 0.25rem 0.75rem; border-radius: 9999px;">
                        {{ count($facilities['vip_cottage'] ?? []) }} facilities
                    </span>
                </button>
                @if ($expandedGroups['vip_cottage'] && count($facilities['vip_cottage'] ?? []) > 0)
                    <div style="background-color: #f9fafb; padding: 1.5rem;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                            @foreach ($facilities['vip_cottage'] as $facilityName => $variants)
                                @include('filament.admin.resources.super-admin-fasilitas-resource.pages.partials.facility-card', ['variants' => $variants])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Others Category -->
            <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
                <button
                    wire:click="$toggle('expandedGroups.others')"
                    style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer;"
                    onmouseover="this.style.backgroundColor='#96C81E'"
                    onmouseout="this.style.backgroundColor='#A8DE30'"
                >
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;"> Others</h2>
                    </div>
                    <span style="font-size: 0.875rem; background-color: rgba(0,0,0,0.2); padding: 0.25rem 0.75rem; border-radius: 9999px;">
                        {{ count($facilities['others'] ?? []) }} facilities
                    </span>
                </button>
                @if ($expandedGroups['others'] && count($facilities['others'] ?? []) > 0)
                    <div style="background-color: #f9fafb; padding: 1.5rem;">
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                            @foreach ($facilities['others'] as $facilityName => $variants)
                                @include('filament.admin.resources.super-admin-fasilitas-resource.pages.partials.facility-card', ['variants' => $variants])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>
