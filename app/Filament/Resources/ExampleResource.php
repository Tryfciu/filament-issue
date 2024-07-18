<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExampleResource\CreateExample;
use App\Filament\Resources\ExampleResource\ListExample;
use App\Models\Appointment;
use App\Models\Enums\AnonymousReportContactType;
use App\Models\Enums\AppointmentFinishReason;
use App\Models\User;
use App\Services\AppointmentService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use function Sentry\captureException;

class ExampleResource extends Resource
{
    protected static ?string $model = User::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('select1')
                    ->options(['option1' => 'Option 1'])
                    ->afterStateUpdated(function (Get $get, Set $set) {
                    // after option selection, change value of select2 to `option2`
                    $set('select2', 'option2');
                })
                    ->live(),
                Select::make('select2')
                    ->extraAttributes(function (Get $get) {
                        return ['wire:key', $get('select1')];
                    })
                    ->options(function (Get $get) {
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

    public static function table(Table $table): Table
    {
        return $table
            ->paginationPageOptions([100])
            ->filters([
                Filter::make('super_filter')
                    ->label('Super filter')
                    ->form([
                        Select::make('super_filter')
                            ->label('Super filter')
                            ->options(User::all()->reduce(fn (array $options, User $user) => $options + [$user->email => $user->email], []))
                            ->live(true),
                    ])
                    ->indicateUsing(function (array $data): ?string {
                        if ($data['super_filter']) {
                            $labels = Collection::make($data['super_filter'])->map(fn (string $filter) => $filter);

                            return 'Super filter: '.$labels;
                        }

                        return null;
                    })
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when($data['super_filter'], fn (Builder $query, $superFilter): Builder => $query->where('email', '=', $superFilter))
                    ),
            ])
            ->columns([
                TextColumn::make('name')->state(fn (User $record) => $record->email),
                TextColumn::make('name2')->state(fn (User $record) => $record->name),
                TextColumn::make('name3')->state(fn (User $record) => $record->email),
                TextColumn::make('name4')->state(fn (User $record) => $record->email),
                TextColumn::make('name5')->state(fn (User $record) => $record->email),
                TextColumn::make('name6')->state(fn (User $record) => $record->email),
                TextColumn::make('name7')->state(fn (User $record) => $record->email),
                TextColumn::make('name8')->state(fn (User $record) => $record->email),
                TextColumn::make('name9')->state(fn (User $record) => $record->email),
                TextColumn::make('name10')->state(fn (User $record) => $record->email),
                TextColumn::make('name11')->state(fn (User $record) => $record->email),
                TextColumn::make('name12')->state(fn (User $record) => $record->email),
                TextColumn::make('name13')->state(fn (User $record) => $record->email),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('do_something')
                        ->label('Do something')
                        ->deselectRecordsAfterCompletion()
                        ->color('danger')
                        ->action(function () {
                                    Notification::make()
                                        ->title('Something!')
                                        ->body('Something!')
                                        ->danger()
                                        ->send();
                        }),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => CreateExample::route('/'),
            'list' => ListExample::route('/list'),
        ];
    }
}
