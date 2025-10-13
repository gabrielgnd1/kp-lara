<?php

return [
    'pages' => [
        'actions' => [
            'create' => [
                'label' => 'Buat',
            ],
            'edit' => [
                'label' => 'Edit',
            ],
            'delete' => [
                'label' => 'Hapus',
            ],
            'view' => [
                'label' => 'Lihat',
            ],
            'save' => [
                'label' => 'Simpan',
            ],
            'cancel' => [
                'label' => 'Batal',
            ],
        ],
    ],
    'table' => [
        'actions' => [
            'edit' => [
                'label' => 'Edit',
            ],
            'delete' => [
                'label' => 'Hapus',
            ],
            'view' => [
                'label' => 'Lihat',
            ],
        ],
        'bulk_actions' => [
            'delete' => [
                'label' => 'Hapus yang dipilih',
            ],
        ],
        'filters' => [
            'trigger' => [
                'label' => 'Filter',
            ],
            'reset' => [
                'label' => 'Reset filter',
            ],
        ],
    ],
    'resources' => [
        'actions' => [
            'create' => [
                'label' => 'Buat :resource',
            ],
        ],
    ],
    'components' => [
        'pagination' => [
            'fields' => [
                'records_per_page' => [
                    'label' => 'Per halaman',
                ],
            ],
            'actions' => [
                'go_to_page' => [
                    'label' => 'Ke halaman :page',
                ],
                'next' => [
                    'label' => 'Berikutnya',
                ],
                'previous' => [
                    'label' => 'Sebelumnya',
                ],
            ],
        ],
    ],
];