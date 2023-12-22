<?php

namespace App\Filament\Resources\OrderResource\Pages;

use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Notifications\CancelledOrder;
use App\Notifications\EndOrder;
use App\Notifications\Livraison;
use App\Notifications\OrderConfirmation;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Notification as FacadesNotification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Exists;

use function Laravel\Prompts\text;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;



    protected function getHeaderActions(): array
    {
        return [
            Action::make('annuler')//annule
            ->hidden($this->record->status==="annule"||$this->record->status==="livre"||$this->record->status==="enLivraison")
            ->color('danger')
            ->requiresConfirmation()
            ->form([
                TextInput::make('raison')->required()
            ])
            ->action(function (array $data):void{
                $attribute=json_decode($this->record->attribute_data);
                if($attribute===[])
                {
                    $attribute['raison']=$data['raison'];
                }else{
                    $attribute->raison=$data['raison'];
                }
               
                $this->record->attribute_data=json_encode($attribute);
                $this->record->status="annule";
                $this->record->date_annulation=Date::now();
                $this->record->save();
                FacadesNotification::route('mail',$this->record->Address()->get()->first()->contact_email)->notify(new CancelledOrder($data['raison']));
                Notification::make()
                ->title('commande annulé')
                ->danger()
                ->seconds(8)
                ->body('le Client recevra une notification par mail')
                ->send();
            }),
            Action::make('validation')//confirme
            ->label('Frais de Livraison')
            ->visible($this->record->shipping_total===null||$this->record->status==="enAttente")
            ->hidden($this->record->status==="annule")
            ->color('info')
            ->form([TextInput::make('shipping')->label('Frais de livraison')->numeric()->placeholder("25.000")->suffix('Francs cfa')->required()])
            ->action(function (array $data):void{
                $this->record->shipping_total=$data['shipping'];
                $this->record->total=$this->record->sub_total-$this->record->discount_total+$this->record->shipping_total;
                $this->record->save();
                Notification::make()
                ->title('Les Frais ont été appliqués avec succés')
                ->info()
                ->seconds(8)
                ->body('verifiez les informations avant de valider')
                ->send();
            }),
            Action::make('attente')//confirme
            ->label('Re-mettre en attente ')
            ->visible($this->record->status==="annule")
            ->color('success')
            ->requiresConfirmation()
            ->action(function ():void{
                $this->record->status='enAttente';
                $this->record->save();
                Notification::make()
                ->title('Le produit a été mis en attente')
                ->info()
                ->seconds(8)
                ->body('verifiez les informations avant de valider')
                ->send();
            }),
            Action::make('confirmer')//confirme
            ->visible($this->record->status==="enAttente")
            ->hidden($this->record->shipping_total===null)
            ->color('success')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="confirme";
                $this->record->date_confirmation=Date::now();
                $this->record->save();
                $file='facture_'.$this->record->reference.'.pdf';
                $route='facture/';             
                {
                    $order=Order::query()->where('id',$this->record->id)->with(['Address','orderables','orderables.orderable'])->get()->first();
                $client = new Party([
                    'name'          => "{$order->Address->first_name} {$order->Address->last_name}",
                    'custom_fields' => [
                        'e-mail'        => "{$order->Address->contact_email}",
                        'numéro de téléphone' => "{$order->Address->contact_phone}",
                    ],
                ]);

                $Adresse = new Party([
                    'name'          => "{$order->Address->pays} ,{$order->Address->region}",
                    'custom_fields' => [
                        'Département' => "{$order->Address->departement}",
                        'Commune' => "{$order->Address->commune}",
                        'details 1'=>"{$order->Address->line_one}",
                        'details 2'=>"{$order->Address->line_two}",
                        'details 3'=>"{$order->Address->line_three}",
                    ],
                ]);
                
                $items=[];
                foreach ($order->orderables as $line) {
                    $option=json_decode($line->option);
                    $objet=InvoiceItem::make($line->orderable->name);
                    $objet->description(" $option->name  : $option->value");
                    $objet->quantity($line->quantity);
                    $objet->pricePerUnit($line->unit_price);
                    $objet->discount($line->discount_total);
                    $objet->subTotalPrice($line->total);
                    $items[]=$objet;
                }
                

                $invoice = Invoice::make('Facture')
                    ->serialNumberFormat('{SEQUENCE}/{SERIES}')
                    ->seller($Adresse)
                    ->buyer($client)
                    ->date(now())
                    ->dateFormat('d/m/Y')
                    ->currencySymbol('F')
                    ->currencyCode('francs cfa')
                    ->currencyFormat('{VALUE}{SYMBOL}')
                    ->currencyThousandsSeparator('.')
                    ->currencyDecimalPoint(',')
                    ->filename('facture_'.$order->reference)
                    ->shipping($order->shipping_total)
                    ->addItems($items)
                    // You can additionally save generated invoice to configured disk
                    ->save('facture');
                    $link = $invoice->url();
                
                }
                //FacadesNotification::route('mail',$this->record->Address()->get()->first()->contact_email)->notify(new OrderConfirmation($link));
                Notification::make()
                ->title('La commande à été validé avec succes')
                ->success()
                ->seconds(8)
                ->body('le Client recevra une notification par mail avec facture')
                ->send();
                Cache::flush();
            }),
            Action::make('Demarrer livraison')//enLivraison
            ->visible($this->record->status==="confirme")
            ->color('warning')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="enLivraison";
                $this->record->save();
                FacadesNotification::route('mail',$this->record->Address()->get()->first()->contact_email)->notify(new Livraison());
                Notification::make()
                ->title('La livraison a commencée ')
                ->warning()
                ->seconds(8)
                ->body('le Client recevra une notification par mail')
                ->send();
            }),
            Action::make('Valider livraison et Terminer commande')//livre
            ->visible($this->record->status==="enLivraison")
            ->color('info')
            ->requiresConfirmation()
            ->action(function (){
                $this->record->status="livre";
                $this->record->date_livraison=Date::now();
                $this->record->save();
                FacadesNotification::route('mail',$this->record->Address()->get()->first()->contact_email)->notify(new EndOrder());
                Notification::make()
                ->title('commande terminée avec succées')
                ->info()
                ->seconds(8)
                ->body('le Client recevra une notification par mail')
                ->send();
            })

        ];
    }
}
