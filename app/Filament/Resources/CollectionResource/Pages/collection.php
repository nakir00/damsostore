<?php

namespace App\Filament\Resources\CollectionResource\Pages;

use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Filament\Resources\CollectionResource;
use App\Models\Collection as ModelCollection;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Tables\Actions\Action;
use Filament\Actions\Action as HAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\FontWeight;

class collection extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithInfolists,InteractsWithTable,InteractsWithForms;

    protected static string $resource = CollectionResource::class;

    protected static string $view = 'filament.resources.collection-resource.pages.collection';

    public ModelCollection $record;

    protected function getHeaderActions(): array
{
    return [
        HAction::make('edit')
            ->url(route('filament.admin.resources.collections.edit', $this->record)),
    ];
}

    public function infolist(Infolist $infolist): Infolist
    {
        $datas=$this->infoData();

        $other=[
            // ...
            ImageEntry::make('media'),
            TextEntry::make('record.name'),
            TextEntry::make('record.group.name'),//group.name
            TextEntry::make('record.created_at')->date(),
        ];
        $parent=[];
        if($datas['parents']==[]){
            $parent[]=TextEntry::make('Aucun parent pour cette collection');
        }
        else
        {
            foreach($datas['parents'] as $id=>$data){
                $parent[]=TextEntry::make('parents.'.$id.'.name') ->url(fn (): string => route('filament.admin.resources.collections.view', $data));
               //dd($data,$id);
           }
        }

        //dd($parent);
        //$datas[]=$parent;
        $infolist
            ->state($datas)
            ->schema([
                Split::make([
                    Section::make('Informations')
                    ->columns(['default' => 1,
                                'sm' => 1,
                                'md' => 2,
                                'lg' => 2,
                                'xl' => 2,
                                '2xl' => 2,
                        ])
                    ->schema([
                            ImageEntry::make('media'),
                            TextEntry::make('record.name')
                                ->weight(FontWeight::Bold)->label('nom'),
                            TextEntry::make('record.group.name')
                                ->label('Groupe'),
                    ])->grow(),
                    Section::make('Parents')->schema(/* [
                        TextEntry::make('parents.0.name')
                        ->listWithLineBreaks()

                    ] */$parent)
                ])->from('md')
            ]);
        return $infolist;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(ModelCollection::query()->where('parent_id',$this->record->id))
            ->columns([
                TextColumn::make('name'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
                Action::make('voir')
                ->url(fn (ModelCollection $record): string => route('filament.admin.resources.collections.view', ['record'=>$record])),
            ])
            ->bulkActions([
                // ...
            ])
            ->emptyStateActions([
                Action::make('create')
                    ->label('ajouter une collection enfant')
                    ->url(route('filament.admin.resources.collections.create'))
                    ->icon('heroicon-m-plus')
                    ->button(),
            ]);
    }

    public function tableData()
    {

    }

    public function infoData()
    {

        $media = $this->record->getMedia("collection")[0]->getUrl();
        $parents=$this->getAllParents($this->record);
        $datas=['parents'=>$parents,'media'=>$media,'record'=>$this->record];
        return $datas;
    }

    public function getAllParents($object, $allParents = [])
    {
       // $allParents[] = $object;
        // Vérifiez si l'objet a un parent.

        if ($object->parent_id!=null) {
            $parent=$object->parent()->get()->first();
            // Récursivement, appelez la fonction pour le parent.
            $allParents[] = $parent;
            //$allParents[]=$this->record->parent;
            return $this->getAllParents($parent, $allParents);
        }

        return $allParents;
    }
}
