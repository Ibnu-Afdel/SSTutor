<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriptionResource\Pages;
use App\Filament\Resources\SubscriptionResource\RelationManagers;
use App\Models\Subscription;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Payments';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('payment_method')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\TextInput::make('duration_in_days')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('screenshot_path'),
                Forms\Components\TextInput::make('transaction_reference'),
                Forms\Components\TextInput::make('paid_at'),
                Forms\Components\TextInput::make('starts_at'),
                Forms\Components\TextInput::make('expires_at'),
                Forms\Components\TextInput::make('notes'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration_in_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('screenshot_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_reference')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid_at')
                    ->searchable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->searchable(),
                Tables\Columns\TextColumn::make('expires_at')
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Active',
                        'expired' => 'Expired',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending'),

                SelectFilter::make('payment_method')
                    ->options([
                        'manual' => 'Manual',
                        'chapa' => 'Chapa',
                        'arifpay' => 'ArifPay',
                    ])
                    ->default('manual'),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function (Subscription $record) {
                        DB::transaction(function () use ($record) {
                            $startDate = now();
                            $expiresAt = now()->addDays($record->duration_in_days);


                            $record->update([
                                'status' => 'active',
                                'starts_at' => $startDate,
                                'expires_at' => $expiresAt,
                                'paid_at' => now(),
                                // will add admin note automatically
                                // 'notes' => ($record->notes ? $record->notes . "\n" : '') . 'Approved by admin on ' . now()->toDateTimeString(),
                            ]);


                            $user = $record->user;
                            $user->update([
                                'is_pro' => true,
                                'pro_expires_at' => $expiresAt,
                                'subscription_type' => $record->payment_method,
                                'subscription_status' => 'active',
                            ]);
                        });
                        \Filament\Notifications\Notification::make()
                            ->title('Subscription Approved')
                            ->success()
                            ->send();
                    })
                    ->visible(fn(Subscription $record): bool => $record->status === 'pending' && $record->payment_method === 'manual'),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()

                    ->form([
                        Forms\Components\Textarea::make('notes')
                            ->label('Rejection Reason (Optional)')
                            ->helperText('This note will be saved on the subscription record.'),
                    ])
                    ->action(function (Subscription $record, array $data) {
                        DB::transaction(function () use ($record, $data) {

                            $rejectionNote = $data['notes'] ?? 'Payment rejected by admin.';
                            $record->update([
                                'status' => 'rejected',
                                'notes' => ($record->notes ? $record->notes . "\n" : '') . $rejectionNote,
                            ]);


                            $user = $record->user;

                            if ($user->subscription_status !== 'active' || $user->pro_expires_at <= now()) {
                                $user->update([
                                    'is_pro' => false,
                                    'subscription_status' => 'rejected',
                                ]);
                            } else {
                            }
                        });
                        \Filament\Notifications\Notification::make()
                            ->title('Subscription Rejected')
                            ->success()
                            ->send();
                    })
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
            'index' => Pages\ListSubscriptions::route('/'),
            'create' => Pages\CreateSubscription::route('/create'),
            'edit' => Pages\EditSubscription::route('/{record}/edit'),
        ];
    }
}
