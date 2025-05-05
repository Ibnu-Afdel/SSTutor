<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InstructorApplicationResource\Pages;
use App\Filament\Resources\InstructorApplicationResource\RelationManagers;
use App\Models\InstructorApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InstructorApplicationResource extends Resource
{
    protected static ?string $model = InstructorApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Applications';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('full_name')
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required(),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required(),
                Forms\Components\DatePicker::make('date_of_birth')
                    ->required(),
                Forms\Components\TextInput::make('adress'),
                Forms\Components\TextInput::make('webiste'),
                Forms\Components\TextInput::make('linkedin')
                    ->required(),
                Forms\Components\Textarea::make('resume')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('higest_qualification')
                    ->required(),
                Forms\Components\TextInput::make('current_ocupation')
                    ->required(),
                Forms\Components\Textarea::make('reason')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_of_birth')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('adress')
                    ->searchable(),
                Tables\Columns\TextColumn::make('webiste')
                    ->searchable(),
                Tables\Columns\TextColumn::make('linkedin')
                    ->searchable(),
                Tables\Columns\TextColumn::make('higest_qualification')
                    ->searchable(),
                Tables\Columns\TextColumn::make('current_ocupation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
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
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->requiresConfirmation()
                    ->action(function (InstructorApplication $record) {
                        // Use a transaction to ensure atomicity
                        DB::transaction(function () use ($record) {
                            $record->update(['status' => 'approved']);
                            $record->user()->update(['role' => 'instructor']);
                        });

                        \Filament\Notifications\Notification::make()
                            ->title('Application Approved')
                            ->success()
                            ->send();
                    })

                    ->visible(fn(InstructorApplication $record): bool => $record->status === 'pending'),

                Action::make('reject')
                    ->label('Reject')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->requiresConfirmation()
                    // have to add zis ..Add a modal form to ask for a rejection reason
                    // ->form([
                    //     Forms\Components\Textarea::make('reason')->required(),
                    // ])
                    ->action(function (InstructorApplication $record/*, array $data*/) {
                        $record->update([
                            'status' => 'rejected',
                            // 'reason_for_rejection' => $data['reason'] 
                        ]);
                        // $record->user()->update(['status' => 'rejected']); 
                        \Filament\Notifications\Notification::make()
                            ->title('Application Rejected')
                            ->success() // Use success for completed action
                            ->send();
                    })->visible(fn(InstructorApplication $record): bool => $record->status === 'pending'),
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
            'index' => Pages\ListInstructorApplications::route('/'),
            'create' => Pages\CreateInstructorApplication::route('/create'),
            'edit' => Pages\EditInstructorApplication::route('/{record}/edit'),
        ];
    }
}
