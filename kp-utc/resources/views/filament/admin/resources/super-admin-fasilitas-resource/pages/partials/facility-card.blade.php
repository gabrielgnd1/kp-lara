@php
    // Convert to collection if it's an array
    if (is_array($variants)) {
        $variants = collect($variants);
    }
    
    // Group variants by day and menginap type
    $weekday = $variants->where('day', 'Weekday');
    $weekend = $variants->where('day', 'Weekend');
    
    $facilityName = $variants->first()->nama ?? '';
    $capacity = $variants->first()->kapasitas ?? 0;
    $description = $variants->first()->keterangan ?? '';
    
    // Get first facility for edit button
    $firstFacility = $variants->first();
@endphp

<div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); overflow: hidden;">
    <!-- Card Header -->
    <div style="background-color: #A8DE30; padding: 1rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">{{ $facilityName }}</h3>
    </div>

    <!-- Card Body -->
    <div style="padding: 1rem; display: flex; flex-direction: column; gap: 1rem;">
        <!-- Description -->
        <div>
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Description</p>
            <p style="font-size: 0.875rem; color: #374151; margin-top: 0.25rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                {{ $description ?: 'No description' }}
            </p>
        </div>

        <!-- Capacity -->
        <div>
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Capacity</p>
            <p style="font-size: 0.875rem; color: #111827; font-weight: 500; margin-top: 0.25rem;">{{ $capacity }} persons</p>
        </div>

        <!-- Price Section -->
        <div style="border-top: 1px solid #e5e7eb; padding-top: 1rem; display: flex; flex-direction: column; gap: 0.75rem;">
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Price</p>
            
            <!-- Weekday -->
            <div style="background-color: #eff6ff; border-radius: 0.25rem; padding: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem;">
                <p style="font-weight: 600; color: #111827; font-size: 0.875rem; margin: 0;">Weekday</p>
                @php
                    $weekdayMenginap = $weekday->where('menginap', 'Menginap')->first();
                    $weekdayTidakMenginap = $weekday->where('menginap', 'Tidak Menginap')->first();
                @endphp
                <div style="font-size: 0.875rem; color: #374151; display: flex; flex-direction: column; gap: 0.25rem;">
                    <p style="display: flex; justify-content: space-between; margin: 0;">
                        <span>• Menginap</span>
                        <span style="font-weight: 500;">Rp{{ $weekdayMenginap ? number_format($weekdayMenginap->harga, 0, ',', '.') : '0' }}</span>
                    </p>
                    <p style="display: flex; justify-content: space-between; margin: 0;">
                        <span>• Tidak Menginap</span>
                        <span style="font-weight: 500;">Rp{{ $weekdayTidakMenginap ? number_format($weekdayTidakMenginap->harga, 0, ',', '.') : '0' }}</span>
                    </p>
                </div>
            </div>

            <!-- Weekend -->
            <div style="background-color: #fff7ed; border-radius: 0.25rem; padding: 0.75rem; display: flex; flex-direction: column; gap: 0.5rem;">
                <p style="font-weight: 600; color: #111827; font-size: 0.875rem; margin: 0;">Weekend</p>
                @php
                    $weekendMenginap = $weekend->where('menginap', 'Menginap')->first();
                    $weekendTidakMenginap = $weekend->where('menginap', 'Tidak Menginap')->first();
                @endphp
                <div style="font-size: 0.875rem; color: #374151; display: flex; flex-direction: column; gap: 0.25rem;">
                    <p style="display: flex; justify-content: space-between; margin: 0;">
                        <span>• Menginap</span>
                        <span style="font-weight: 500;">Rp{{ $weekendMenginap ? number_format($weekendMenginap->harga, 0, ',', '.') : '0' }}</span>
                    </p>
                    <p style="display: flex; justify-content: space-between; margin: 0;">
                        <span>• Tidak Menginap</span>
                        <span style="font-weight: 500;">Rp{{ $weekendTidakMenginap ? number_format($weekendTidakMenginap->harga, 0, ',', '.') : '0' }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Footer - Edit Button -->
    <div style="background-color: #f3f4f6; padding: 0.75rem 1rem; border-top: 1px solid #e5e7eb;">
        <button
            wire:click="openEditModal({{ $firstFacility->id ?? 0 }})"
            style="width: 100%; background-color: #A8DE30; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 0.5rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: background-color 0.15s ease;"
            onmouseover="this.style.backgroundColor='#96C81E'"
            onmouseout="this.style.backgroundColor='#A8DE30'"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
            </svg>
            Edit
        </button>
    </div>
</div>

