<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationGroup = 'Library Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Book Information')
                    ->schema([
                        Forms\Components\TextInput::make('isbn')
                            ->label('ISBN')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->required(),
                        
                        Forms\Components\Select::make('author_id')
                            ->relationship('author', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        
                        Forms\Components\TextInput::make('publisher')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('year')
                            ->numeric()
                            ->minValue(1000)
                            ->maxValue(date('Y'))
                            ->step(1),
                        
                        Forms\Components\Select::make('categories')
                            ->relationship('categories', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable()
                            ->label('Categories')
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                        
                        Forms\Components\Select::make('status')
                            ->options([
                                'available' => 'Available',
                                'borrowed' => 'Borrowed',
                            ])
                            ->required()
                            ->default('available'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('File Uploads')
                    ->schema([
                        Forms\Components\FileUpload::make('cover')
                            ->label('Cover Image (Required)')
                            ->image()
                            ->directory('book-covers')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->required()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->helperText('Maximum file size: 2MB. Accepted formats: JPEG, PNG, WebP'),
                        
                        Forms\Components\FileUpload::make('pdf_file')
                            ->label('PDF File (Optional)')
                            ->directory('book-pdfs')
                            ->visibility('public')
                            ->maxSize(10240)
                            ->acceptedFileTypes(['application/pdf'])
                            ->helperText('Maximum file size: 10MB. Upload the full book in PDF format (optional)')
                            ->downloadable()
                            ->openable(),
                    ])
                    ->columns(2),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-book-cover.png')),
                
                Tables\Columns\TextColumn::make('isbn')
                    ->label('ISBN')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(25),

                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                
                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('publisher')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('year')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('pdf_file')
                    ->label('PDF')
                    ->boolean()
                    ->trueIcon('heroicon-o-document-text')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->alignCenter()
                    ->tooltip(fn ($state) => $state ? 'PDF available' : 'No PDF'),

                
                Tables\Columns\TextColumn::make('categories.name')
                    ->label('Categories')
                    ->badge()
                    ->sortable()
                    ->searchable(),
         
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'available',
                        'warning' => 'borrowed',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'available',
                        'heroicon-o-clock' => 'borrowed',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'available' => 'Available',
                        'borrowed' => 'Borrowed',
                    ]),
                
                Tables\Filters\SelectFilter::make('year')
                    ->options(function () {
                        $currentYear = date('Y');
                        $years = [];
                        for ($year = $currentYear; $year >= 1950; $year--) {
                            $years[$year] = $year;
                        }
                        return $years;
                    })
                    ->searchable(),
                
                Tables\Filters\SelectFilter::make('author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'view' => Pages\ViewBook::route('/{record}'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
