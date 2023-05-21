<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DynamicBlockResource\Pages;
use App\Filament\Resources\DynamicBlockResource\RelationManagers;
use App\Models\DynamicBlock;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class DynamicBlockResource extends Resource
{
    protected static ?string $model = DynamicBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('title')->label('Titre du block')->required()->maxLength(255),
                        TextInput::make('video_url')->label('Lien de la video')->required(),
                        Textarea::make('content')->label('Contenu du block')->required(),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('title')->label('Titre du block')->sortable()->searchable(),
                TextColumn::make('video_url')->label('Lien de la video')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListDynamicBlocks::route('/'),
            'create' => Pages\CreateDynamicBlock::route('/create'),
            'edit' => Pages\EditDynamicBlock::route('/{record}/edit'),
        ];
    }
}
