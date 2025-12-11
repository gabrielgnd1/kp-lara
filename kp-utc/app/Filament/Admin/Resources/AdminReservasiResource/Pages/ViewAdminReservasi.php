<?php

namespace App\Filament\Admin\Resources\AdminReservasiResource\Pages;

use App\Filament\Admin\Resources\AdminReservasiResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms\Form;

class ViewAdminReservasi extends ViewRecord
{
    protected static string $resource = AdminReservasiResource::class;

    public function form(Form $form): Form
    {
        return AdminReservasiResource::form($form);
    }
}