<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <!-- Hall Category -->
        <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; overflow: hidden;">
            <button
                wire:click="$toggle('expandedGroups.hall')"
                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer; transition: background-color 0.15s ease;"
                onmouseover="this.style.backgroundColor='#96C81E'"
                onmouseout="this.style.backgroundColor='#A8DE30'"
            >
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">Hall</h2>
                </div>
                <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: white;">
                    {{ count($this->facilities['hall'] ?? []) }} facilities
                </span>
            </button>
            @if (isset($this->facilities['hall']))
                <div style="background-color: #f9fafb; padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                        @foreach ($this->facilities['hall'] as $facilityName => $variants)
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
                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer; transition: background-color 0.15s ease;"
                onmouseover="this.style.backgroundColor='#96C81E'"
                onmouseout="this.style.backgroundColor='#A8DE30'"
            >
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">Cottage</h2>
                </div>
                <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: white;">
                    {{ count($this->facilities['cottage'] ?? []) }} facilities
                </span>
            </button>
            @if (isset($this->facilities['cottage']))
                <div style="background-color: #f9fafb; padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                        @foreach ($this->facilities['cottage'] as $facilityName => $variants)
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
                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer; transition: background-color 0.15s ease;"
                onmouseover="this.style.backgroundColor='#96C81E'"
                onmouseout="this.style.backgroundColor='#A8DE30'"
            >
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">VIP Cottage</h2>
                </div>
                <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: white;">
                    {{ count($this->facilities['vip_cottage'] ?? []) }} facilities
                </span>
            </button>
            @if (isset($this->facilities['vip_cottage']))
                <div style="background-color: #f9fafb; padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                        @foreach ($this->facilities['vip_cottage'] as $facilityName => $variants)
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
                style="width: 100%; display: flex; align-items: center; justify-content: space-between; background-color: #A8DE30; color: black; padding: 1rem; cursor: pointer; transition: background-color 0.15s ease;"
                onmouseover="this.style.backgroundColor='#96C81E'"
                onmouseout="this.style.backgroundColor='#A8DE30'"
            >
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                    </svg>
                    <h2 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">Others</h2>
                </div>
                <span style="font-size: 0.875rem; background-color: rgba(168, 222, 48, 0.3); padding: 0.25rem 0.75rem; border-radius: 9999px; color: white;">
                    {{ count($this->facilities['others'] ?? []) }} facilities
                </span>
            </button>
            @if (isset($this->facilities['others']))
                <div style="background-color: #f9fafb; padding: 1.5rem;">
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
                        @foreach ($this->facilities['others'] as $facilityName => $variants)
                            @include('filament.admin.resources.super-admin-fasilitas-resource.pages.partials.facility-card', ['variants' => $variants])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Edit Modal -->
    @if ($showEditModal && $editingFacility)
        <div style="position: fixed; inset: 0; background-color: rgba(0,0,0,0.5); z-index: 50; display: flex; align-items: center; justify-content: center; padding: 1rem;">
            <div style="background-color: white; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); max-width: 28rem; width: 100%; max-height: 90vh; overflow-y: auto;">
                <!-- Modal Header -->
                <div style="background-color: #A8DE30; padding: 1.5rem; color: white;">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin: 0;">Ubah Fasilitas</h3>
                        <button
                            wire:click="closeEditModal"
                            style="background: none; border: none; color: white; cursor: pointer; padding: 0;"
                            onmouseover="this.style.opacity='0.8'"
                            onmouseout="this.style.opacity='1'"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <p style="font-size: 0.875rem; opacity: 0.9; margin-top: 0.25rem; margin-bottom: 0;">{{ $editingFacility->nama }}</p>
                </div>

                <!-- Modal Body -->
                <form wire:submit="saveFacility" style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                    <!-- Facility Name -->
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                            Nama Fasilitas
                        </label>
                        <input
                            type="text"
                            wire:model="editFormData.nama"
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: #f3f4f6; color: #374151;"
                            disabled
                        />
                        <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Nama fasilitas tidak dapat diubah</p>
                    </div>

                    <!-- Capacity -->
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                            Kapasitas
                        </label>
                        <input
                            type="number"
                            wire:model="editFormData.kapasitas"
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #111827;"
                            required
                            min="1"
                        />
                    </div>

                    <!-- Description -->
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                            Keterangan
                        </label>
                        <textarea
                            wire:model="editFormData.keterangan"
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #111827;"
                            rows="3"
                        ></textarea>
                    </div>

                    <!-- Price Section -->
                    <div style="border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem;">
                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #374151; margin: 0 0 1rem 0;">Harga</h4>
                        
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                            <!-- Harga Weekday Internal -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Weekday Internal
                                </label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem;">Rp</span>
                                    <input
                                        type="number"
                                        wire:model="editFormData.harga_weekday_internal"
                                        value="{{ $editFormData['harga_weekday_internal'] ?? '' }}"
                                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #111827;"
                                        required
                                        min="0"
                                    />
                                </div>
                            </div>

                            <!-- Harga Weekday Eksternal -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Weekday Eksternal
                                </label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem;">Rp</span>
                                    <input
                                        type="number"
                                        wire:model="editFormData.harga_weekday_eksternal"
                                        value="{{ $editFormData['harga_weekday_eksternal'] ?? '' }}"
                                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #111827;"
                                        required
                                        min="0"
                                    />
                                </div>
                            </div>

                            <!-- Harga Weekend Internal -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Weekend Internal
                                </label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem;">Rp</span>
                                    <input
                                        type="number"
                                        wire:model="editFormData.harga_weekend_internal"
                                        value="{{ $editFormData['harga_weekend_internal'] ?? '' }}"
                                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #111827;"
                                        required
                                        min="0"
                                    />
                                </div>
                            </div>

                            <!-- Harga Weekend Eksternal -->
                            <div>
                                <label style="display: block; font-size: 0.75rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                                    Weekend Eksternal
                                </label>
                                <div style="position: relative;">
                                    <span style="position: absolute; left: 0.75rem; top: 50%; transform: translateY(-50%); color: #6b7280; font-size: 0.875rem;">Rp</span>
                                    <input
                                        type="number"
                                        wire:model="editFormData.harga_weekend_eksternal"
                                        value="{{ $editFormData['harga_weekend_eksternal'] ?? '' }}"
                                        style="width: 100%; padding: 0.5rem 0.75rem 0.5rem 2.25rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #111827;"
                                        required
                                        min="0"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 0.25rem;">
                            Status
                        </label>
                        <select
                            wire:model="editFormData.status"
                            style="width: 100%; padding: 0.5rem 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: white; color: #111827;"
                            required
                        >
                            <option value="">Pilih Status</option>
                            <option value="Available">Tersedia</option>
                            <option value="Not Available">Tidak Tersedia</option>
                        </select>
                    </div>

                    <!-- Modal Footer -->
                    <div style="display: flex; gap: 0.75rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb; margin-top: 1.5rem;">
                        <button
                            type="button"
                            wire:click="closeEditModal"
                            style="flex: 1; padding: 0.5rem 1rem; border: 1px solid #d1d5db; color: #374151; border-radius: 0.5rem; background-color: white; font-weight: 500; cursor: pointer; transition: background-color 0.15s ease;"
                            onmouseover="this.style.backgroundColor='#f3f4f6'"
                            onmouseout="this.style.backgroundColor='white'"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            style="flex: 1; padding: 0.5rem 1rem; background-color: #A8DE30; color: white; border-radius: 0.5rem; border: none; font-weight: 500; cursor: pointer; transition: background-color 0.15s ease;"
                            onmouseover="this.style.backgroundColor='#96C81E'"
                            onmouseout="this.style.backgroundColor='#A8DE30'"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</x-filament-panels::page>
