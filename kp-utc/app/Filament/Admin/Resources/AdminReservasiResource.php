<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AdminReservasiResource\Pages;
use App\Models\Reservasi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AdminReservasiResource extends Resource
{
    protected static ?string $model = Reservasi::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'Reservation Management';
    protected static ?string $navigationLabel = 'All Reservations';

    public static function getGloballySearchableAttributes(): array
    {
        return ['nama_pemesan', 'email', 'no_telepon', 'judul_kegiatan'];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Reservation Details')
                ->schema([
                    Forms\Components\TextInput::make('nama_pemesan')
                        ->label('Name')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('no_telepon')
                        ->label('Phone')
                        ->tel()
                        ->required()
                        ->maxLength(20),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(100),
                    Forms\Components\TextInput::make('judul_kegiatan')
                        ->label('Event Title')
                        ->required()
                        ->maxLength(100),
                    Forms\Components\DateTimePicker::make('waktu_check_in')
                        ->label('Check In Time')
                        ->required(),
                    Forms\Components\DateTimePicker::make('waktu_check_out')
                        ->label('Check Out Time')
                        ->required()
                        ->after('waktu_check_in'),
                    Forms\Components\Select::make('jenis_member')
                        ->label('Member Type')
                        ->options([
                            'Internal' => 'Internal',
                            'Eksternal' => 'External',
                        ])
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Status & Payment')
                ->schema([
                    Forms\Components\Select::make('status_reservasi')
                        ->label('Reservation Status')
                        ->options([
                            'ACC' => 'Accepted',
                            'NOT ACC' => 'Not Accepted',
                            'CANCELLED' => 'Cancelled'
                        ])
                        ->required(),
                    Forms\Components\Select::make('status_pembayaran')
                        ->label('Payment Status')
                        ->options([
                            'BARU' => 'New',
                            'DP' => 'Down Payment',
                            'LUNAS' => 'Fully Paid',
                            'BATAL' => 'Cancelled'
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('jumlah_laki')
                        ->label('Male Participants')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('jumlah_perempuan')
                        ->label('Female Participants')
                        ->numeric()
                        ->default(0),
                    Forms\Components\TextInput::make('harga_akhir')
                        ->label('Final Price')
                        ->disabled()
                        ->prefix('Rp'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_pemesan')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul_kegiatan')
                    ->label('Event')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('waktu_check_in')
                    ->label('Check In')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_reservasi')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ACC' => 'success',
                        'NOT ACC' => 'warning',
                        'CANCELLED' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Payment')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'LUNAS' => 'success',
                        'DP' => 'warning',
                        'BARU' => 'info',
                        'BATAL' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('harga_akhir')
                    ->label('Price')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_reservasi')
                    ->options([
                        'ACC' => 'Accepted',
                        'NOT ACC' => 'Not Accepted',
                        'CANCELLED' => 'Cancelled'
                    ]),
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->options([
                        'BARU' => 'New',
                        'DP' => 'Down Payment',
                        'LUNAS' => 'Fully Paid',
                        'BATAL' => 'Cancelled'
                    ]),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminReservasis::route('/'),
            'create' => Pages\CreateAdminReservasi::route('/create'),
            'edit' => Pages\EditAdminReservasi::route('/{record}/edit'),
        ];
    }
}