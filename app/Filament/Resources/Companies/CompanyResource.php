<?php

namespace App\Filament\Resources\Companies;

use App\Filament\Resources\Companies\Pages\CreateCompany;
use App\Filament\Resources\Companies\Pages\EditCompany;
use App\Filament\Resources\Companies\Pages\ListCompanies;
use App\Filament\Resources\Companies\Schemas\CompanyForm;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use App\Models\Company;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\Branches\BranchResource;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $maxContentWidth = 'full';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return CompanyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->searchable(),
                TextColumn::make('email')->limit(25),
                TextColumn::make('phone'),
                TextColumn::make('city'),
                TextColumn::make('country'),

                // hidden by default, toggle from the columns button
                TextColumn::make('legal_name')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('registration_number')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('tax_number')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('website')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('postal_code')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->striped()
            ->paginated([10, 25, 50])
            ->persistColumnsInSession()
            ->actions([
                EditAction::make(),
                Action::make('manageBranches')
                    ->label('Branches')
                    ->icon('heroicon-o-building-office-2')
                    ->url(fn($record) => BranchResource::getUrl('index', [
                        'company' => $record->id,
                    ])),
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
            'index' => ListCompanies::route('/'),
            'create' => CreateCompany::route('/create'),
            'edit' => EditCompany::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
