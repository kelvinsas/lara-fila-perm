<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers;
use App\Models\Batch;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BatchResource extends Resource
{
    protected static ?string $model = Batch::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $modelLabel = 'Lote';

    protected static ?string $pluralModelLabel = 'Lotes';

    public static function form(Form $form): Form
    {
        $user = auth()->user();

        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                    ->label('Lote')
                    ->hidden(fn (string $context): bool => $context === 'create')
                    ->disabled()
                    ->numeric()
                    ->minValue(1),
                Forms\Components\TextInput::make('amount')
                    ->label('Quantidade')
                    ->required()
                    ->disabled(fn (string $context): bool => $context === 'edit')
                    ->numeric()
                    ->minValue(1),
                Forms\Components\Select::make('product_id')
                    ->label('Produto')
                    ->required()
                    ->disabled(fn (string $context): bool => $context === 'edit')
                    ->relationship('Products', 'name')
                    ->preload(),    
                Forms\Components\TextInput::make('discard')
                    ->label('Descartados')
                    ->required()
                    ->hidden(fn (string $context): bool => $context === 'create')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('defect')
                    ->label('Defeitos')
                    ->required()
                    ->hidden(fn (string $context): bool => $context === 'create')
                    ->numeric()
                    ->minValue(0),
                Forms\Components\TextInput::make('approved')
                    ->label('Aprovados')
                    ->required()
                    ->hidden(fn (string $context): bool => $context === 'create')
                    ->numeric()
                    ->minValue(0),                    
                Forms\Components\DatePicker::make('date')
                    ->label('Data')
                    ->displayFormat('d/m/Y')
                    ->default(now())
                    ->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        1 => 'Aberto',
                        2 => 'Conferindo',
                        3 => 'Fechado',
                    ])
                    ->default(1)
                    ->disabled(fn (string $context): bool => $context === 'create'),   
                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->default($user->id)
                    ->required()
                    ->relationship('Users', 'name')
                    ->disabled()
                    ->preload(),        
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Lote')
                    ->sortable(),
                Tables\Columns\TextColumn::make('products.name')
                    ->label('Produto'),
                Tables\Columns\TextColumn::make('users.name')
                    ->label('Usuario'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Quantidade'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->date('d/m/Y'),
                // Tables\Columns\TextColumn::make('status'),
                Tables\Columns\BadgeColumn::make('status')
                    ->enum([
                        1 => 'Aberto',
                        2 => 'Conferindo',
                        3 => 'Fechado',
                    ])
                    ->colors([
                        'primary' => 2,
                        'success' => 1,
                        'danger' => 3,
                    ])
                    ->icons([
                        'heroicon-o-clipboard-check' => 2,
                        'heroicon-o-lock-open' => 1,
                        'heroicon-o-lock-closed' => 3,
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Alterado em')
                    ->dateTime('d/m/Y h:i'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Batch $record): bool => $record->status != 3),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBatches::route('/'),
            'create' => Pages\CreateBatch::route('/create'),
            'edit' => Pages\EditBatch::route('/{record}/edit'),
        ];
    }    

}
