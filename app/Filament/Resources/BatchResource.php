<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BatchResource\Pages;
use App\Filament\Resources\BatchResource\RelationManagers;
use App\Models\Batch;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Carbon\Carbon;

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
                    ->searchable()
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
                    ->label(fn ( $record): string =>  $record->status !== 2 ? 'Aprovados' : 'Aprovados Anteriormente')
                    ->disabled(fn ( $record): bool =>  $record->status === 2)
                    ->hidden(fn (string $context): bool => $context === 'create')
                    ->numeric()
                    ->minValue(0),              
                Forms\Components\TextInput::make('new-approved')
                    ->label('Novos Aprovados')
                    ->default("0")
                    ->required()
                    ->hidden(fn ( $record , string $context): bool =>  $context === 'create' ? true : $record->status !== 2)
                    ->numeric()
                    ->minValue(0),                                 
                Forms\Components\Select::make('status')
                    ->options([
                        2 => 'Conferindo ...',
                        3 => 'Fechado',
                    ])
                    ->hidden(fn (string $context): bool => $context === 'create'),                        
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
                    ->label('Produto')
                    ->sortable(),
                Tables\Columns\TextColumn::make('producers.name')
                    ->label('Produtor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Quantidade'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->enum([
                        1 => 'Aberto',
                        2 => 'Conferindo',
                        3 => 'Fechado',
                        4 => 'Liberado',
                    ])
                    ->colors([
                        'primary' => 2,
                        'success' => 4,
                        'danger' => 3,
                    ])
                    ->icons([
                        'heroicon-o-clipboard-check' => 2,
                        'heroicon-o-lock-open' => 1,
                        'heroicon-o-lock-closed' => 3,
                        'heroicon-o-check' => 4,
                    ])
                    ->sortable(),
                Tables\Columns\TextColumn::make('lecturers.name')
                    ->label('Conferente')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        4 => 'Liberado',
                        3 => 'Fechado',
                        2 => 'Conferindo',
                        1 => 'Aberto',
                    ])
                    ->attribute('status'),
                    Tables\Filters\SelectFilter::make('product')
                        ->relationship('products', 'name')
                        ->label('Produto'),
                    Tables\Filters\Filter::make('date')
                        ->form([Forms\Components\DatePicker::make('date')->format('Y-m-d')->displayFormat('d/m/Y')])
                        ->indicateUsing(function (array $data): ?string {
                            if (! $data['date']) {
                                return null;
                            }
                     
                            return 'Data: ' . Carbon::parse($data['date'])->toFormattedDateString('Y-m-d');
                        })
                        ->query(function (Builder $query, array $data): Builder {
                        
                            if ($data['date'] !== null){
                                return $query
                                    ->whereDate('date',  
                                        $data['date']
                                    );
                            }else{
                                return $query;
                            }    
                            
                        })
                        ->label('Data'),    
                    Tables\Filters\SelectFilter::make('lecturer_id')
                        ->relationship('lecturers', 'name')
                        ->label('Conferente'),            
                    Tables\Filters\SelectFilter::make('producer_id')
                        ->relationship('producers', 'name')
                        ->label('Produtor')           
            ])
            ->actions([
                
                Tables\Actions\Action::make('updateLiberate')
                    ->label('Liberar')  
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Batch $record): bool => $record->status == 3 && auth()->user()->hasRole(['Admin', 'Manager']))
                    ->mountUsing(fn (Forms\ComponentContainer $form, Batch $record) => $form->fill([
                        'liberted' => $record->approved,
                    ]))
                    ->action(function (Batch $record, array $data): void {
          
                        $record->status = 4;
                        $record->liberted = $data['liberted'];
                        $record->save();
                    })
                    ->form([
                        Forms\Components\TextInput::make('liberted')
                        ->label('Liberados')
                        ->numeric()
                        ->minValue(0)              
                        ->required(),
                    ]),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Batch $record): bool => $record->status != 3 || auth()->user()->hasRole(['Admin', 'Manager'])),
                Tables\Actions\DeleteAction::make(),
                ])
            ->bulkActions([
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

    public static function getEloquentQuery(): Builder
    {
        return auth()->user()->hasRole(['Admin', 'Manager', 'Lecturer'])
        ? parent::getEloquentQuery()
        : parent::getEloquentQuery()->whereHas(
            'producers',
            fn(Builder $query) => $query->where('id', auth()->user()->id)
        );
    }

}
