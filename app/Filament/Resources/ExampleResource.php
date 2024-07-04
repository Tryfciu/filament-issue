<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExampleResource\CreateExample;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;

class ExampleResource extends Resource
{
    protected static ?string $model = User::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('select1')->options([
                    'option1' => 'Option 1',
                ])->afterStateUpdated(function (Get $get, Set $set) {
                    // after option selection, change value of select2 to `option2`
                    $set('select2', 'option2');
                })
                    ->live(),
                Select::make('select2')->options(function (Get $get) {
                    $select1Value = $get('select1');

                    // if select1 has no value then return following list
                    if($select1Value === null) {
                        return [
                            'option4' => 'Option 4',
                            'option3' => 'Option 3',
                            'option2' => 'Option 2',
                            'option1' => 'Option 1',
                        ];
                    }

                    // if select1 has value then return following list
                    return [
                        'option1' => 'Option 1',
                        'option2' => 'Option 2',
                        'option3' => 'Option 3',
                    ];
                })
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => CreateExample::route('/'),
        ];
    }
}
