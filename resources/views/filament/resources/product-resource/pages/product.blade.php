<x-filament-panels::page>

    {{ $this->infolist }}

@if (count($tabs = $this->getTabs()))
<x-filament::tabs>
    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::resource.pages.list-records.tabs.start', scopes: $this->getRenderHookScopes()) }}

    @foreach ($tabs as $tabKey => $tab)
        @php
            $activeTab = strval($activeTab);
            $tabKey = strval($tabKey);
        @endphp

        <x-filament::tabs.item
            :active="$activeTab === $tabKey"
            :badge="$tab[0]->getBadge()"
            :icon="$tab[0]->getIcon()"
            :icon-position="$tab[0]->getIconPosition()"
            :wire:click="'$set(\'activeTab\', ' . (filled($tabKey) ? ('\'' . $tabKey . '\'') : 'null') . ')'"
        >
            {{ $tab[0]->getLabel() ?? $this->generateTabLabel($tabKey) }}
        </x-filament::tabs.item>
    @endforeach

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::resource.pages.list-records.tabs.end', scopes: $this->getRenderHookScopes()) }}
</x-filament::tabs>
@endif



    {{ $this->table }}

</x-filament-panels::page>
