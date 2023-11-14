<?php

/* ublic function getActionVariants()
    {
        return$this->record->collection_id===null?$this->getWarningHeader():$this->getAddingHeader();
    }

public function getColumnsVariants()
    {
        return[
            CuratorColumn::make('images')
            ->size(40)
            ->ring(2) // options 0,1,2,4
            ->overlap(4) // options 0,2,3,4
            ->limit(3),
            TextColumn::make('name')->searchable(),
            ToggleColumn::make('disponibility'),
            TextColumn::make('min_price')->sortable()->suffix(' franc')->numeric(thousandsSeparator:" ,"),
        ];
    }

public function getRowActionVariants()
    {
        return[
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
                            TextInput::make('name')->required()->default(fn (ProductVariant $record):string => $record->name)->live(debounce: 1000)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')->default(fn (ProductVariant $record):string => $record->slug)
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
                    ]),
            TablesAction::make('supprimer')
                ->hidden($this->record->status==="Publie")
                ->requiresConfirmation()
                ->action(function (ProductVariant $record) {
                    $record->images()->detach();
                    $record->delete();
                }),
            ];
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

public function reOrderUpdateData(&$data)
{
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

public function getMinPrice($data)
{
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
                    $var=ProductVariant::create(["name"=>$data['name'],"slug"=>$data['slug'],"product_id"=>$this->record->id,"disponibility"=>true,"min_price"=>$min_price,"attribute_data"=>$data['attribute_data']]);
                    $var->images()->attach($data['product_picture_ids']);

                      n'oublie pas d'ajouter la partie pour les purchasable

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
                            TextInput::make('name')->required()->live(debounce: 1000)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')->required(),
                        ]),
                    Step::make('Configuration')
                        ->description('configurez la disponibilité')
                        ->schema($repeater),
                        ])
                    ];
}

    if($this->activeTab==='variantes')
    {
        $Action=$this->getActionVariants();
        $columns=$this->getColumnsVariants();
        $rowAction=$this->getRowActionVariants();
    }else


return[
    [
        'variantes' =>  [Tab::make(),ProductVariant::query()->where('product_id',$this->record->id)],
    ],
];

 */

