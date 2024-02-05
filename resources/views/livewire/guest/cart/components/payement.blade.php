<div class="bg-white border border-gray-100 shadow-lg rounded-xl">
    <div class="flex items-center h-16 px-6 border-b border-gray-100">
        <h3 class="text-lg font-medium">
            Méthode de Payement
        </h3>
    </div>

    @if ($currentStep >= $step)
        <div class="p-6 space-y-4">
            <div class="flex gap-4">
                <button disabled @class([
                    'px-5 py-2 text-sm border font-medium rounded-lg',
                    'text-green-700 border-green-600 bg-green-50' => $paymentType === 'card',
                    'text-gray-500 hover:text-gray-700' => $paymentType !== 'card',
                ])
                        type="button"
                        {{-- wire:click.prevent="$set('paymentType', 'card')" --}}>
                    Mobile Pay
                </button>

                <button @class([
                    'px-5 py-2 text-sm border font-medium rounded-lg',
                    'text-green-700 border-green-600 bg-green-50' => $paymentType === 'cash-in-hand',
                    'text-gray-500 hover:text-gray-700' => $paymentType !== 'cash-in-hand',
                ])
                        type="button"
                        wire:click.prevent="$set('paymentType', 'cash-in-hand')">
                    A la livraison
                </button>
            </div>

            {{-- @if ($paymentType == 'card')
                <livewire:stripe.payment :cart="$cart"
                                         :returnUrl="route('checkout.view')" />
            @endif --}}

            @if ($paymentType == 'cash-in-hand')
                <div {{-- wire:submit.prevent="checkout" --}}>
                    <div class="p-4 text-sm text-center text-black rounded-lg bg-white">
                        Après avoir recu le coli
                    </div>

                    <button
                        x-on:click="
                        cart={{json_encode($cart)}};
                        content = cart.map(produit => ({
                            id: produit.slug, // Utilisez une propriété unique comme identifiant du produit (slug, référence, etc.)
                            quantity: produit.quantity,
                            item_price: produit.price,
                            description: produit.name, // Vous pouvez utiliser d'autres propriétés pour enrichir la description
                            brand: 'Votre Marque',
                            category: 'Produit',
                            image_url: produit.url,
                            attributes: {
                                taille: produit.option,

                            }
                        }));
                        ids=cart.map(produit => produit.slug);
                        console.log();
                        fbq('track', 'Purchase', {
                            currency: 'EUR',
                            content_name: 'Commande Finalisée - {{$sessionId}}',
                            content_category: 'commande',
                            content_ids: ids,
                            content_type: 'product_group',
                            contents: content,
                            value: {{$sommeWithBreak}},
                            num_items: cart.length,
                            order_id: 'Commande_'+ Date.now(),

                        });
                        "
                    class="px-5 py-3 mt-4 text-sm font-medium text-white bg-black rounded-lg hover:bg-gray-600"
                            {{-- type="submit" --}}
                            wire:key="payment_submit_btn">
                        <span wire:loading.remove.delay
                              wire:target="checkout">
                            Valider la commande
                        </span>
                        <span wire:loading.delay
                              wire:target="checkout">
                            <svg class="w-5 h-5 text-white animate-spin"
                                 xmlns="http://www.w3.org/2000/svg"
                                 fill="none"
                                 viewBox="0 0 24 24">
                                <circle class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4">
                                </circle>
                                <path class="opacity-75"
                                      fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </span>
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
