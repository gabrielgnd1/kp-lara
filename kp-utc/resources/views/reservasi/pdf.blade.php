<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Reservasi Form</title>
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .no-print-section {
                display: none;
            }
        }

         @font-face {
            font-family: 'DejaVu Sans';
            src: url("{{ storage_path('fonts/DejaVuSans.ttf') }}") format("truetype");
        }
        
        :root {
            --color-green: #A8DE30;
            --color-black: #31312C;
            --color-white: #FFFFFF;
            --color-purple: #493852;
        }
        
        * {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: var(--color-white);
            color: var(--color-black);
            line-height: 1.6;
                font-size: 12px;
            min-height: 100vh;
        }

         .container {
            max-width: 100%;
            padding: 0.5rem;
            margin: 0 auto;
        }

        .no-print-section {
            margin-bottom: 1rem;
                text-align: right;
        }

        @page {
            margin: 20px;
                 size: A4;
        }

        /* Print Button Styles */
        .no-print-section button {
            background-color: #A8DE30;
            color: #31312C;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }

        .no-print-section button:hover {
            background-color: #9ACC28;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
         .no-print-section button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print-section">
            @if(isset($reservasi) && $reservasi['status_reservasi'] === 'ACC')
                <x-print-button />
            @endif
        </div>
         @if(isset($reservasi_list))
            {{-- Batch Export Mode --}}
            @foreach($reservasi_list as $reservasi)
                @include('reservasi.partials.form-content')
                <div style="page-break-after: always;"></div>
            @endforeach
        @else
            {{-- Single Reservation Mode --}}
            @include('reservasi.partials.form-content')
        @endif
    </div>
</body>
</html>