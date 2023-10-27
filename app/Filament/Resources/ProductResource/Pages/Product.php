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
use App\Models\Product as ModelProduct;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Models\ProductVariant;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use Awcodes\Curator\Components\Tables\CuratorColumn;
use Awcodes\Curator\Models\Media;
use Filament\Actions\Modal\Actions\Action;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Wizard\Step;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions\Action as IAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\Pages\ListRecords\Tab;
use Filament\Tables\Actions\Action as TablesAction;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Features\SupportQueryString\Url;

use function Livewire\Volt\rules;

class Product extends Page implements HasForms, HasTable, HasInfolists
{

    use InteractsWithInfolists,InteractsWithForms;
    use InteractsWithTable{makeTable as makeBaseTable;}

    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.resources.product-resource.pages.product';

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

    public function table(Table $table): Table
    {
        $tabs=$this->getTabs();
        if($this->activeTab==='variantes')
        {
            $headerAction=$this->record->collection_id===null?$this->getWarningHeader():$this->getAddingHeader();
        }

        return $table
            ->query($tabs[$this->activeTab][1])
            ->columns([
                CuratorColumn::make('images')
                ->size(40)
                ->ring(2) // options 0,1,2,4
                ->overlap(4) // options 0,2,3,4
                ->limit(3),
                TextColumn::make('name'),
                ToggleColumn::make('disponibility'),
                TextColumn::make('min_price')->suffix(' franc')->numeric(thousandsSeparator:" ,"),
            ])->actions([
                TablesAction::make('changer')
                    ->action(function (ProductVariant $record,$data) {
                        $this->reOrderUpdateData($data);
                        $min_price=$this->getMinPrice($data);
                        $record->update(['attribute_data'=>$data['attribute_data'],"name"=>$data['name'],"min_price"=>$min_price]);
                        $toDetach=array_diff(array_map(fn($image)=>$image["id"],$record->images()->get()->toArray()),$data['product_picture_ids']);
                        $toAttach =array_diff($data['product_picture_ids'],array_map(fn($image)=>$image["id"],$record->images()->get()->toArray()));
                        $record->images()->attach($toAttach);
                        $record->images()->detach($toDetach);
                    })
                    ->steps([
                        Step::make('images')
                        ->description('images de la variante')
                        ->schema([
                            CuratorPicker::make('product_picture_ids')
                                ->multiple()
                                ->required()
                                ->size('sm')->default(fn(ProductVariant $record):array => array_map(fn($image)=>$image['id'] ,$record->images()->get()->toArray()))
                                ->orderColumn('order'),
                        ])->columns(3),
                        Step::make('Nom')
                            ->description('le nom de la variante')
                            ->schema([
                                TextInput::make('name')->required()->default(fn (ProductVariant $record):string => $record->name),
                            ]),
                        Step::make('Configuration')
                            ->description('configurez la disponibilité')
                        ->schema(function (ProductVariant $record):array{
                            $nam=$this->record->collection()->get()->first()->group()->get()->first()->productOption()->get()->first()->name;
                            return [Repeater::make('attribute_data')
                            ->label('configuration')
                            ->schema([
                                TextInput::make("$nam")->disabled(),
                                TextInput::make('Prix')->numeric()->placeholder($record->min_price)->minValue($this->record->old_price)->suffix('Franc'),
                                Toggle::make('disponible')
                            ])->default($record->attribute_data)
                            ->grid(4)
                            ->addable(false)
                            ->reorderable(false)
                            ->deletable(false)];
                            }),
                        ])
                    ,
                TablesAction::make('supprimer')
                    ->requiresConfirmation()
                    ->action(function (ProductVariant $record) {
                        $record->is_featured = false;
                        $record->save();
                    }),
            ])->headerActions($headerAction);

    }

    public function infoData()
    {
        $media = $this->getMediaUrls();
        $parents=$this->record->collection_id!==null?array_reverse($this->getAllParents($this->record->collection)):[];
        $parentsToSend=array_merge($parents,$this->record->collection?[$this->record->collection]:[]);
        $datas=['parents'=>$parentsToSend,'media'=>$media,'record'=>$this->record];
        return $datas;
    }

    public function getWarningHeader()
    {
        return [TablesAction::make('attention')
        ->label('variante')
        ->requiresConfirmation()
        ->modalHeading('Aucune collection')
        ->modalDescription('vous devez d\'abord ajouter une collection au produit avant de lui ajouter une variante')
        ];
    }

    public function getDataforRepeater():array
    {
        $productOption=$this->record->collection()->get()->first()->group()->get()->first()->productOption()->get()->first();
        $values=$productOption->values()->get();
        return [$values=array_map(fn($value)=>$value->name,$values->all()),$productOption->name];
    }

    public function makeRepeater(array $data)
    {
            [$values,$name]=$data;
            $num=$this->record->old_price;
            $arrayOfdefaults=[];
            foreach($values as $value)
            {
                $arrayOfdefaults[]=[
                    "$name"=>$value,
                    'Prix'=>$num,
                    'disponible'=>true
                ];
            }

        return[
            Repeater::make('attribute_data')
                ->label('configuration')
                ->schema([
                    TextInput::make("$name")->disabled(),
                    TextInput::make("Prix")->numeric()->placeholder($num)->default($num)->minValue($num)->suffix('Franc'),
                    Toggle::make('disponible')->default(true)
                ])->default($arrayOfdefaults)
                ->grid(4)
                ->addable(false)
                ->reorderable(false)
                ->deletable(false)
                ];
    }

    public function reOrderUpdateData(&$data){
        $productOption=$this->record->collection()->get()->first()->group()->get()->first()->productOption()->get()->first();
        $values=$productOption->values()->get()->toArray();
        $optionName=$productOption->name;
        $values = array_map(fn($value) => $value['name'], $values);
        $attributes=$data["attribute_data"];
        for($i = 0; $i<count($values) ; $i++){
            $arrayPointure=["$optionName"=>$values[$i]];
            $arrayWO=$attributes[$i];
            $attributes[$i]=array_merge($arrayPointure,$arrayWO);
        }
        $data["attribute_data"]=$attributes;
    }

    public function getMinPrice($data){
        $min_price=$data['attribute_data'][0]['Prix'];
        foreach($data['attribute_data'] as $rep){
            if($rep['Prix']<=$min_price)
            {
                $min_price=$rep['Prix'];
            }
        }
        return $min_price;
    }

    public function getAddingHeader()
    {
        $repeater=$this->makeRepeater($this->getDataforRepeater());

        return [
            CreateAction::make('variante')
                ->closeModalByClickingAway(false)
                ->action(function($data){
                    $min_price=$this->getMinPrice($data);
                    $var=ProductVariant::create(["name"=>$data['name'],"product_id"=>$this->record->id,"disponibility"=>true,"min_price"=>$min_price,"attribute_data"=>$data['attribute_data']]);
                    $var->images()->attach($data['product_picture_ids']);
                    /**
                     * n'oublie pas d'ajouter la partie pour les purchasable
                     */
                })
                ->steps([
                    Step::make('images')
                        ->description('images de la variante')
                        ->schema([
                            CuratorPicker::make('product_picture_ids')
                                ->multiple()
                                ->required()
                                ->orderColumn('order'),
                        ]),
                    Step::make('Nom')
                        ->description('le nom de la variante')
                        ->schema([
                            TextInput::make('name')->required(),
                        ]),
                    Step::make('Configuration')
                        ->description('configurez la disponibilité')
                        ->schema($repeater),
                        ])
                    ];
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

    public function manageCollectionAttachement($id)
    {
        //on recupere la collection à travers son id
        $collection=Collection::find($id);
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
        //on enregistre les modifications sur le record
        $this->record->save();
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
                ->options(Collection::query()->pluck('name', 'id'))
                ->searchable()
                ->required()
                    //->default('enPreparation'),
            ])->action(function (array $data): void {
                $this->manageCollectionAttachement($data['collections']);
            }),
            IAction::make('Detacher')
                ->requiresConfirmation()
                ->before(function () {
                     $this->record->collections()->detach();
                     $this->record->collection_id=null;
                     $this->record->save();
                })
        ]);
    }

    public function getTabs(): array
    {
        return [
            'variantes' =>  [Tab::make(),ProductVariant::query()->where('product_id',$this->record->id)],
            'produis_associés' => [Tab::make(),Collection::query()],
        ];
    }

    public function generateTabLabel(string $key): string
    {
        return (string) str($key)
            ->replace(['_', '-'], ' ')
            ->ucfirst();
    }

}
