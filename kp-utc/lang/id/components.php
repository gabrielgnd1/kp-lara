<?php

return [
    'pagination' => [
        'fields' => [
            'records_per_page' => [
                'label' => 'Per halaman',
                'options' => [
                    'all' => 'Semua',
                ],
            ],
            'page' => [
                'label' => 'Halaman',
            ],
        ],
        'buttons' => [
            'previous' => 'Sebelumnya',
            'next' => 'Berikutnya',
        ],
        'labels' => [
            'showing_results' => 'Menampilkan :first - :last dari :total hasil',
            'no_records' => 'Tidak ada data',
        ],
    ],
];