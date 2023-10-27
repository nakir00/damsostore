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
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Models\Media;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Tables\Actions\Action;
use Filament\Actions\Action as HAction;
use Filament\Infolists\Components\Actions\Action as IAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action as ActionsAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\FontWeight;

class collection extends Page implements HasForms, HasTable, HasInfolists
{
    use InteractsWithInfolists,InteractsWithTable,InteractsWithForms;

    protected static string $resource = CollectionResource::class;

    protected static string $view = 'filament.resources.collection-resource.pages.collection';

    public ModelCollection $record;

    public function infolist(Infolist $infolist): Infolist
    {
        $datas=$this->infoData();
        $parent=[];
        if($datas['parents']==[]){
            $parent[]=TextEntry::make('Aucun parent pour cette collection');
        }
        else
        {
            foreach($datas['parents'] as $id=>$data){
                $parent[]=TextEntry::make('parents.'.$id.'.name') ->url(fn (): string => route('filament.admin.resources.collections.view', $data))->label('');
           }
           $parent=array_reverse($parent);
        }
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
                            $datas['media']==null?
                            TextEntry::make('')
                                ->weight(FontWeight::Bold)->label('aucun media'):
                                ImageEntry::make('media'),
                            TextEntry::make('record.name')
                                ->weight(FontWeight::Bold)->label('nom'),
                            TextEntry::make('record.group.name')
                                ->label('Groupe'),
                            Actions::make([
                                IAction::make('edit')
                                ->url(route('filament.admin.resources.collections.edit', ['record' => $this->record])),
                                IAction::make('supprimer')
                                    ->requiresConfirmation()
                                    ->before(function () {
                                        $fils=ModelCollection::where('parent_id',$this->record->id)->get();
                                        if($this->record->parent_id==null)
                                        {
                                            foreach($fils as $enfant){
                                                $enfant->parent_id=null;
                                                $enfant->save();
                                            }
                                        }else{
                                            foreach($fils as $enfant){
                                                $enfant->parent_id=$this->record->parent_id;
                                                $enfant->save();
                                            }
                                        }
                                        $this->record->delete();
                                        redirect(route('filament.admin.resources.collections.index'));

                                    })
                                ]),
                    ])->grow(),
                    Section::make('Parents')->schema($parent)
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
                Action::make('edit')
                ->fillForm(fn (ModelCollection $record): array => [
                    'image' => $record->getMedia('collection'),
                    'name'=> $record->name,
                    /* 'collection_group_id' => $record->group(),
                    'parent' */
                ])
                ->form([
                    CuratorPicker::make('featured_image_id')
                    ->relationship('featuredImage', 'id'),
                        TextInput::make('name')->required()->maxLength(50),

                        Select::make('collection_group_id')
                            ->relationship(name: 'group', titleAttribute: 'name')
                            ->label('group'),
                        Select::make('parent_id')
                            ->relationship(name: 'parent', titleAttribute: 'name')
                            ->label('collection parent'),

                ])
                ->action(function (array $data, ModelCollection $record): void {
                    $record->save([$data]);
                })
            ])
            ->bulkActions([
                // ...
            ])
            ->emptyStateActions([
                Action::make('create')
                ->form([
                        CuratorPicker::make('featured_image_id')
                        ->relationship('featuredImage', 'id'),
                        TextInput::make('name')->required()->maxLength(50),

                ])
                ->action(function (array $data, ModelCollection $record=null): void {
                    $record=new ModelCollection(['name'=>$data['name'],'featured_image_id'=>$data['featured_image_id'],'collection_group_id'=>$this->record->collection_group_id,'parent_id'=>$this->record->id]);
                    $record->save();
                })
            ]);
    }


    public function infoData()
    {
        // =null;
        $media=Media::query()->where('id',$this->record->featured_image_id)->get(); //;
        if($media!==null)
        {
            $media = $media->first()->getSignedUrl();
            if (strpos($media, "/curator/") === 0) {
                $media=config('app.url').$media;
            }
        }
        else
        {
            $media=null;
        }

        $parents=$this->getAllParents($this->record);
        $datas=['parents'=>$parents,'media'=>$media,'record'=>$this->record];
        return $datas;
    }

    public function getAllParents($object, $allParents = [])
    {
        if ($object->parent_id!=null) {
            $parent=$object->parent()->get()->first();
            $allParents[] = $parent;
            return $this->getAllParents($parent, $allParents);
        }
        return $allParents;
    }
}
