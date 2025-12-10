<div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.5rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); overflow: hidden;">
    <!-- Card Header -->
    <div style="background-color: #A8DE30; padding: 1rem;">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: black; margin: 0;">{{ $fasilitas->nama }}</h3>
    </div>

    <!-- Card Body -->
    <div style="padding: 1rem; display: flex; flex-direction: column; gap: 1rem;">
        <!-- Description -->
        <div>
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Deskripsi</p>
            <p style="font-size: 0.875rem; color: #374151; margin-top: 0.25rem; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                {{ $fasilitas->keterangan ?: 'Tidak ada deskripsi' }}
            </p>
        </div>

        <!-- Capacity -->
        <div>
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Kapasitas</p>
            <p style="font-size: 0.875rem; color: #111827; font-weight: 500; margin-top: 0.25rem;">{{ $fasilitas->kapasitas }} orang</p>
        </div>

        <!-- Status -->
        <div>
            <p style="font-size: 0.75rem; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 600; margin: 0;">Status</p>
            <p style="font-size: 0.875rem; color: #059669; font-weight: 600; margin-top: 0.25rem;">
                ✓ Tersedia
            </p>
        </div>
    </div>
</div>
