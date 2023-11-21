<form wire:submit.prevent="saveAddress()"

      class="bg-white border border-gray-100 rounded-xl shadow-lg">
    <div class="flex items-center justify-between h-16 px-6 border-b border-gray-100">
        <h3 class="text-lg font-medium">
            Adresse
        </h3>

        @if ($currentStep > $step)
            <button class="px-5 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-100 hover:text-gray-700"
                    type="button"
                    wire:click.prevent="$set('currentStep', {{ $step }})">
                Modifier
            </button>
        @endif
    </div>

    @if ($currentStep >= $step)
        <div class="p-6">
            @if ($step == $currentStep)
                <div class="grid grid-cols-6 gap-4">
                    <x-input.group class="col-span-3"
                                   label="Prénoms"
                                   :errors="$errors->get('first_name')"
                                   required>
                        <x-input.text wire:model.defer="first_name"
                                      required />
                    </x-input.group>

                    <x-input.group class="col-span-3"
                                   label="Nom"
                                   :errors="$errors->get('last_name')"
                                   required>
                        <x-input.text wire:model.defer="last_name"
                                      required />
                    </x-input.group>


                    <x-input.group class="col-span-6 sm:col-span-3"
                                   label="Numero de téléphone"
                                   :errors="$errors->get('contact_phone')">
                        <x-input.text wire:model.defer="contact_phone" />
                    </x-input.group>

                    <x-input.group class="col-span-6 sm:col-span-3"
                                   label="adresse email"
                                   :errors="$errors->get('contact_email')"
                                   required>
                        <x-input.text wire:model.defer="contact_email"
                                      type="email"
                                      required />
                    </x-input.group>

                    <div class="col-span-6">
                        <hr class="h-px my-4 bg-gray-100 border-none">
                    </div>
                    <x-input.group class="col-span-3 sm:col-span-2"
                                   label="Pays"
                                   :errors="$errors->get('country')"
                                   required>
                        <x-input.text wire:model.defer="country" value="Sénégal"
                                      required />
                    </x-input.group>

                    <x-input.group class="col-span-3 sm:col-span-2"
                                   label="Région"
                                   :errors="$errors->get('region')">
                        <x-input.text wire:model.defer="region" value="Dakar"/>
                    </x-input.group>

                    <x-input.group class="col-span-3 sm:col-span-2"
                                   label="departement"
                                   :errors="$errors->get('departement')"
                                   required>
                        <x-input.text wire:model.defer="departement"
                                      required />
                    </x-input.group>

                    <x-input.group class="col-span-3 sm:col-span-2"
                                   label="Commune"
                                   :errors="$errors->get('commune')"
                                   required>
                        <x-input.text wire:model.defer="commune"
                                      required />
                    </x-input.group>

                    <x-input.group class="col-span-3 sm:col-span-2"
                                   label="adresse details 1"
                                   :errors="$errors->get('line_one')"
                                   required>
                        <x-input.text wire:model.defer="line_one"
                                      required />
                    </x-input.group>

                    <x-input.group class="col-span-3 sm:col-span-2"
                                   label="adresse details 2"
                                   :errors="$errors->get('line_two')">
                        <x-input.text wire:model.defer="line_two" />
                    </x-input.group>

                    <x-input.group class="col-span-3 sm:col-span-2"
                                   label="adresse details 3"
                                   :errors="$errors->get('line_three')">
                        <x-input.text wire:model.defer="line_three" />
                    </x-input.group>





{{--                     <x-input.group class="col-span-3 sm:col-span-2"
                                   label="Postcode"
                                   :errors="$errors->get('postcode')"
                                   required>
                        <x-input.text wire:model.defer="postcode"
                                      required />
                    </x-input.group> --}}

                </div>
            @elseif($currentStep > $step)
                <dl class="grid grid-cols-1 gap-8 text-sm sm:grid-cols-2">
                    <div>
                        <div class="space-y-4">
                            <div>
                                <dt class="font-medium">
                                    Nom complet
                                </dt>

                                <dd class="mt-0.5">
                                    {{ $this->first_name }} {{ $this->last_name }}
                                </dd>
                            </div>

                            @if ($this->contact_phone)
                                <div>
                                    <dt class="font-medium">
                                        numero de téléphone
                                    </dt>

                                    <dd class="mt-0.5">
                                        {{ $this->contact_phone }}
                                    </dd>
                                </div>
                            @endif

                            <div>
                                <dt class="font-medium">
                                    Adressse Email
                                </dt>

                                <dd class="mt-0.5">
                                    {{ $this->contact_email }}
                                </dd>
                            </div>
                        </div>
                    </div>

                    <div>
                        <dt class="font-medium">
                            Addresse
                        </dt>{{-- 'pays'=>$this->country,
                        'region'=>$this->region,
                        'departement'=>$this->departement,
                        'commune'=>$this->commune, --}}

                        <dd class="mt-0.5">
                            @if ($this->country)
                                {{ $this->country }}<br>
                            @endif
                            @if ($this->region)
                                {{ $this->region }}<br>
                            @endif
                            @if ($this->departement)
                                {{ $this->departement }}<br>
                            @endif
                            @if ($this->commune)
                                {{ $this->commune }}<br>
                            @endif
                            @if ($this->line_one)
                                {{ $this->line_one }}<br>
                            @endif
                            @if ($this->line_two)
                                {{ $this->line_two }}<br>
                            @endif
                            @if ($this->line_three)
                                {{ $this->line_three }}<br>
                            @endif

                        </dd>
                    </div>
                </dl>
            @endif

            @if ($step == $currentStep)
                <div class="mt-6 text-right">
                    <button class="px-5 py-3 text-sm font-medium text-white bg-black rounded-lg hover:bg-gray-600"
                            type="submit"
                            wire:key="submit_btn"
                            wire:loading.attr="disabled"
                            wire:target="saveAddress">
                        <span wire:loading.remove
                              wire:target="saveAddress">
                            Enregistrer
                        </span>

                        <span wire:loading
                              wire:target="saveAddress">
                            <span class="inline-flex items-center">
                                Enregistrement ...

                               {{--  <x-icon.loading /> --}}
                            </span>
                        </span>
                    </button>
                </div>
            @endif
        </div>

    @endif
</form>
