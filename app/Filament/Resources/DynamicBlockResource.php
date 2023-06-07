<?php

namespace App\Filament\Resources;


use Filament\Forms\Components\Builder;
use App\Filament\Resources\DynamicBlockResource\Pages;
use App\Filament\Resources\DynamicBlockResource\RelationManagers;
use App\Models\DynamicBlock;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class DynamicBlockResource extends Resource
{
    protected static ?string $model = DynamicBlock::class;


    protected static ?string $navigationGroup = 'Ressources mobiles';

    //Ressources sur l'obésité , menu section
    //"Gestion des blocs d'informations" ou "Création de blocs d'informations

    // demandeur de rendez-vous //page title
    protected static ?string $navigationIcon = 'heroicon-s-collection';

    public static ?string $label='Ressources'; //darurya // button new ...

    public static ?string $slug = '/resource-obesite';  //darurya

    protected static ?string $activeNavigationIcon = 'heroicon-s-collection';



    protected static ?string $breadcrumb = 'Ressources Obésité'; // // for menu //darurya



    protected static ?string $navigationLabel = 'Ressources Obésité'; //side bar

    protected static ?string $pluralLabel = 'Ressources Obésité'; // page name // //darurya


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('title')->label('Titre')->required()->maxLength(255),
//                        TextInput::make('video_url')->label('Video')->required(),
//                        Textarea::make('content')->label('Contenu du block')->required(),


                        Card::make()->label("Ressources")
                            ->schema([
                                Builder::make('dynamic_fields')->label('Ressources')
                                    ->blocks([

                                        Builder\Block::make('texte')->label('Texte')

                                            ->schema([
                                                MarkdownEditor::make('contenu')
                                                    ->label('Contenu de la ressource')
                                            ]),

                                        Builder\Block::make('videos_associees')->label('Vidéos associées')
                                            ->schema([
                                                FileUpload::make('Videos_associees')
                                                    ->label('Vidéos associées')->acceptedFileTypes($types = [
                                                        'video/mp4',
                                                        'video/quicktime',
                                                        'video/x-msvideo',
                                                    ])
                                                    ->multiple(),

                                            ]),

                                        Builder\Block::make('Images associees')->label('Images associées')
                                            ->schema([
                                                FileUpload::make('Images_associees')
                                                    ->label('Images associées')
                                                    ->image()->multiple(),

                                            ]),
                                        Builder\Block::make('Lien_video')->label('Lien vidéo')
                                            //                            ->statePath('antecedents')
                                            ->schema([

                                                TextInput::make('Lien_video')->label('Lien vidéo')->url(),

                                            ]),


                            ])
                        ])
            ]) ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Intitulé')->sortable()->searchable()->toggleable(),
                TextColumn::make('created_at')->label('Date de création')->dateTime()->toggleable(),
                TextColumn::make('updated_at')->label('Date de dernière modification')->dateTime()->toggleable(),


//                TextColumn::make('title')->label('Titre du block')->sortable()->searchable(),
//                TextColumn::make('video_url')->label('Lien de la video')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Modifier'),
                //Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make()->label(''),
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
            'view' => Pages\ViewDynamicBlock::route('/{record}'),
        ];
    }
}
