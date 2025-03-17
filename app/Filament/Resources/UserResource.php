<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Components\ImageEntry;
use App\Filament\Resources\UserResource\Pages\EditUser;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use STS\FilamentImpersonate\Tables\Actions\Impersonate;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use Filament\Infolists\Components\Grid as ComponentsGrid;
use Filament\Infolists\Components\Split as ComponentsSplit;
use Filament\Infolists\Components\Section as ComponentsSection;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $defaultAvatar = 'flat-style-woman-avatar_90220-2876.avif';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::formSchema($form->getLivewire()->record));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('id', '!=', auth()->id())->orderBy('created_at', 'desc'))
            ->columns([
                ImageColumn::make('avatar_url')->label('Picture')->default(asset(self::$defaultAvatar))->circular(),
                TextColumn::make('name')->searchable()->sortable()
                    ->weight(FontWeight::Bold),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->dateTime()
                    ->sortable(),
                ToggleColumn::make('is_active')->label('Active'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Impersonate::make()->redirectTo(route('filament.admin.pages.dashboard')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            ComponentsSection::make([
                ComponentsSplit::make([
                    ImageEntry::make('avatar_url')->label('Profile Picture')
                        ->defaultImageUrl(asset(self::$defaultAvatar))->size(200)
                        ->grow(false),
                    ComponentsGrid::make(2)->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email'),
                        TextEntry::make('email_verified_at')->dateTime()->badge(),
                        IconEntry::make('is_active')->boolean()->label('Active'),
                        TextEntry::make('created_at')->badge(),
                        TextEntry::make('updated_at')->badge(),
                    ])
                ]),
            ])

        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function formSchema(?User $user = null): array
    {
        $strLength = 255;
        $section1 = [
            TextInput::make('name')
                ->required()
                ->maxLength($strLength),
            TextInput::make('email')
                ->email()
                ->unique(User::class, 'email', $user)
                ->required()
                ->maxLength($strLength),
        ];

        if (auth()->user()->is_admin) {
            if (empty($user)) {
                $section1[] = TextInput::make('password')->password()->minLength(6)->maxLength(32)->required();
            } else {
                $section1[] = TextInput::make('password')->password()->minLength(6)->maxLength(32);
            }
        }

        return [Grid::make([
            'default' => 1,
        ])
            ->schema([
                Split::make([
                    Section::make($section1),
                    Section::make([
                        FileUpload::make('avatar_url')
                            ->label('Profile Picture')
                            ->image()->imageEditor()
                            ->disk(config('filesystems.default'))
                            ->directory('avatars')
                            ->visibility('public'),
                        Toggle::make('is_active')->label('Active')->default(true),
                        DateTimePicker::make('email_verified_at')->disabled(),
                    ])->grow(false),
                ])->from('xl')
            ])];
    }
}
