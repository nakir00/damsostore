<?php

namespace App\Filament\Resources\ProductResource\Pages;


use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use App\Filament\Resources\ProductResource;
use App\Models\Collection;
use App\Models\kit;
use App\Models\Product as ModelProduct;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Awcodes\Curator\Models\Media;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\Modal\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Set;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions\Action as IAction;
use Filament\Infolists\Components\SpatieTagsEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Tables\Actions\Action as TablesAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\ToggleColumn;
use Livewire\Features\SupportQueryString\Url;
use Illuminate\Support\Str;

use function Livewire\Volt\rules;

class Product extends Page implements HasForms, HasTable, HasInfolists
{

    use InteractsWithInfolists,InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.product';

    protected function getHeaderActions(): array
    {
        return [
            ActionsAction::make('publier')
            ->disabled($this->record->variants()->get()->toArray()===[])
            ->hidden($this->record->status==="Publie")
            ->color('success')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="Publie";
                $this->record->save();
            }),
            ActionsAction::make('cacher')
            ->hidden($this->record->status==="cache"||$this->record->status==="enPreparation")
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="cache";
                $this->record->save();
            }),
        ];
    }

    public ModelProduct $record;

    #[Url]
    public ?string $activeTab = null;

    public function mount(): void
    {
        static::authorizeResourceAccess();

        if (
            blank($this->activeTab) &&
            count($tabs = $this->getTabs())
        ) {
            $this->activeTab = array_key_first($tabs);
        }
    }

    public function infolist(Infolist $infolist): Infolist
    {
        $datas=$this->infoData();
        $parent=$this->genParentElement($datas);
        $parent[]=$this->addActionElement();
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
                        $datas['media']==null?TextEntry::make('image')->default('aucune image'):ImageEntry::make('media')
                            ->stacked(),
                            TextEntry::make('record.name')->weight(FontWeight::Bold)->label('nom'),
                            TextEntry::make('record.productType.name')->label('type'),
                            TextEntry::make('record.old_price')->label('prix général'),
                            SpatieTagsEntry::make('tags'),
                            Actions::make([
                                IAction::make('edit')
                                ->url(route('filament.admin.resources.products.edit', ['record' => $this->record])),
                                IAction::make('supprimer')
                                    ->requiresConfirmation()
                                    ->before(function () {
                                        /* $fils=ModelProduct::where('parent_id',$this->record->id)->get();
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
                                        redirect(route('filament.admin.resources.collections.index')); */

                                    })
                                ])
                    ])->grow(),
                    Section::make('collections ou le produit est visible')->schema($parent)
                ])->from('md')
            ]);
        return $infolist;
    }

    public function getActionProducts()
    {
        return[
            /* TablesAction::make('assoicier')
        //->hidden(fn(): bool => $this->record->group()->get()->first()->product_option_id?true:false)
        ->form([
                TextInput::make('type'),
                Select::make('product')
                    ->options(ModelProduct::whereNot('status','enPreparation')->pluck('name','id'))
        ])
        ->action(function (array $data): void {
            $id=$data['product'];
            unset($data['product']);
            $this->record->associations()->attach($id,$data);
        }) */];
    }

    public function getColumnsProducts()
    {
        return[
            TextColumn::make('name')->label("nom"),
                TextColumn::make('slug')->label("slug")->searchable(),
                TextColumn::make('productType.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('old_price')->label("prix général")->suffix(' Francs cfa')->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'enPreparation' => 'gray',
                        'cache' => 'warning',
                        'Publie' => 'success'
                    })
        ];
    }

    public function getRowActionProducts()
    {
        return[];
    }

    public function getActionKits()
    {
        return[];
    }

    public function getColumnsKits()
    {
        return[
        CuratorColumn::make('featured_image_id')
        ->size(40),
        TextColumn::make('name')->label("nom")
            ->searchable(),
        TextColumn::make('collectionGroup.name')
            ->searchable(),
        TextColumn::make('price')->label("prix général")->suffix(' Francs cfa')->sortable(),
        TextColumn::make('status')
            ->badge()
            ->color(fn (string $state): string => match ($state) {
                'enPreparation' => 'gray',
                'cache' => 'warning',
                'Publie' => 'success'
            })
            ->sortable()];
    }

    public function getRowActionKits()
    {
        return[TablesAction::make('voir')
        ->url(fn (kit $record): string => route('filament.admin.resources.kits.view', $record)),];
    }

    public function getActionCollections()
    {
        return[TablesAction::make('Attacher')
        ->form([
                Select::make('product')
                    ->options(Collection::query()->whereHas('group',function ($query) {$query->where('product_option_id', null);})->pluck('name','id'))
        ])
        ->action(function (array $data): void {
            $this->record->collections()->attach($data['product']);
        })];
    }

    public function getColumnsCollections()
    {
        return[CuratorColumn::make('featured_image_id')->size(50)->rounded(),
        TextColumn::make('name')->label("nom")->searchable(),
        TextColumn::make('slug')->label("slug")->searchable(),
        TextColumn::make('group.name')->label('groupe'),
    ];
    }

    public function getRowActionCollections()
    {
        return[
            TablesAction::make('voir')
                ->url(fn (Collection $record): string => route('filament.admin.resources.collections.view', $record)),
            TablesAction::make('détacher')
                ->color("danger")
                ->requiresConfirmation()
                ->action(fn(Product $record)=>$this->record->collections()->detach($record->id)),
        ];
    }

    public function table(Table $table): Table
    {
        $tabs=$this->getTabs();
        if($this->activeTab==='produits_associés'){
            $Action=$this->getActionProducts();
            $columns=$this->getColumnsProducts();
            $rowAction=$this->getRowActionProducts();
        }elseif($this->activeTab==='Kits_associés'){
            $Action=$this->getActionKits();
            $columns=$this->getColumnsKits();
            $rowAction=$this->getRowActionKits();
        }elseif($this->activeTab==='collections_associés'){
            $Action=$this->getActionCollections();
            $columns=$this->getColumnsCollections();
            $rowAction=$this->getRowActionCollections();
        }

        return $table
            ->query($tabs[$this->activeTab][1])
            ->columns($columns)
            ->actions($rowAction)
            ->headerActions($Action)
            ->emptyStateActions($Action);
    }

    public function infoData()
    {
        $media = $this->getMediaUrls();
        $parents=$this->record->collection_id!==null?array_reverse($this->getAllParents($this->record->collection)):[];
        $parentsToSend=array_merge($parents,$this->record->collection?[$this->record->collection]:[]);
        $datas=['parents'=>$parentsToSend,'media'=>$media,'record'=>$this->record];
        return $datas;
    }



    public function getMediaUrls()
    {
        $media=null;
        $all=$this->record->variants()->get();
        $images=[];
        foreach($all as $variant){
            if([]!==$variant->images()->get()->toArray())
            {
                $images[]=$variant;
            }
        }
        if($images!==null)
        {
            $media=[];
            foreach($images as $var){
                $got = $var->images()->get()->first()->getSignedUrl();
                if (strpos($got, "/curator/") === 0) {
                    $got=config('app.url').$got;
                }
                $media[]=$got;
            }
        }
        return $media;
    }

    public function getAllParents($object, $allParents = []):array
    {
        if ($object->parent_id!=null) {
            $parent=$object->parent()->get()->first();
            $allParents[] = $parent;
            return $this->getAllParents($parent, $allParents);
        }
        return $allParents;
    }

    public function genParentElement($data):array
    {
        $parent=[];
        if($data['parents']==[]){
            $parent[]=TextEntry::make("les collections que vous attacherez s'affichent ici ");
        }
        else
        {
            foreach($data['parents'] as $id=>$data){
                $parent[]=TextEntry::make('parents.'.$id.'.name') ->url(fn (): string => route('filament.admin.resources.collections.view', $data))->label('');
           }
           //$parent=array_reverse($parent);
        }
        return $parent;
    }

    public function detachChildCollections($array)
    {
            $this->record->collections()->detach($array);
    }

    public function isCollectionAttachable(Collection $collection):bool
    {
        $flag=false;
        if(is_null($this->record->product_option_id))
        {
            $flag=true;
        }
        else{
            if($this->record->variants()->get()->toArray()===[])
            {
                $flag=true;
            }
            else{
                if($collection->group()->first()->productOption()->first()->id===$this->record->product_option_id)
                {
                    $flag=true;
                }
            }
        }
        return$flag;
    }

    public function manageCollectionAttachement($id)
    {
        //on recupere la collection à travers son id
        $collection=Collection::find($id);
        if($this->isCollectionAttachable($collection))
        {
            //on recupere les parents de cette collection
            $parents=$this->getAllParents($collection);
            //on filtre seulement les collections qui doivent etre attachee
            [$toAttach,$toDetach]=$this->getAttaches(array_merge($parents,[$collection]));
            //on attache les collections concernées
            $this->attachToCollection($toAttach);
            //on detache les collections qui ne sont plus concernées
            $this->detachChildCollections($toDetach);
            //on save la collection pour la retrouver sur le record
            $this->record->collection_id=$collection->id;
            //on save le productOption sur le record
            $this->record->product_option_id=$collection->group()->first()->productOption()->first()->id;
            //on enregistre les modifications sur le record
            $this->record->save();
            Notification::make()
                ->title('Attaché avec succés')
                ->success()
                ->seconds(8)
                ->body('vous pouvez créer des variantes maintenant')
                ->send();
        }
        else{
            Notification::make()
                ->title('Collection non attaché')
                ->danger()
                ->seconds(8)
                ->body('cette collection ne respecte pas les conditions pour etre attachée')
                ->send();
        }

    }

    public function attachToCollection($array)
    {
        foreach($array as $id){
            $this->record->collections()->attach($id);
        }
    }

    public function getAttaches($array)
    {
        $array=array_map(fn($collection) => $collection->id, $array);
        $datas=$this->record->collections()->wherePivot('product_id',$this->record->id)->get()->toJson();
        $datas=json_decode($datas,true);
        $dbAttachement=array_map(fn($collection) => $collection['id'], $datas);
        $toDetach=array_diff($dbAttachement,$array);
        $toAttach=array_diff($array,$dbAttachement);
        return [$toAttach,$toDetach];
    }

    public function addActionElement()
    {
        return Actions::make([
            IAction::make('Attacher Collection')
            ->fillForm(fn (): array => [
                'collections' => $this->record->collection_id,
                ])
            ->form([
                Select::make('collections')
                ->options(function(){
                    if($this->record->variants()->get()->toArray()===[])
                    {return Collection::query()->whereHas('group',function ($query) {$query->whereHas('productOption');})->pluck('name', 'id');}
                    else
                    {return Collection::query()->whereHas('group',function ($query) {$query->whereHas('productOption', function ($query) {$query->where('id', $this->record->product_option_id);});})->pluck('name', 'id');}
                })
                ->searchable()
                ->required()
                    //->default('enPreparation'),
            ])->action(function (array $data): void {
                $this->manageCollectionAttachement($data['collections']);
            }),
            IAction::make('Detacher')
                ->hidden($this->record->variants()->get()->toArray()!==[])
                ->requiresConfirmation()
                ->before(function () {
                     $this->record->collections()->detach();
                     $this->record->collection_id=null;

                     $this->record->save();
                }),

        ]);
    }

    public function getTabs(): array
    {
        return [
            'produits_associés' => [Tab::make(),ModelProduct::query()->whereHas('associations',function ($query) {$query->where('product_parent_id', $this->record->id);})],
            'Kits_associés' => [Tab::make(),kit::query()->whereHas('products',function ($query) {$query->where('product_id', $this->record->id);})],
            'collections_associés' => [Tab::make(),Collection::query()->whereHas('products',function ($query) {$query->where('product_id', $this->record->id);})->whereHas('group',function ($query) {$query->where('product_option_id', null);})],
        ];
    }

    public function generateTabLabel(string $key): string
    {
        return (string) str($key)
            ->replace(['_', '-'], ' ')
            ->ucfirst();
    }

}
