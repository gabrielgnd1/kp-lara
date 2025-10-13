<x-filament-panels::page>
    @if ($this->getWidgets())
        <x-filament-widgets::widgets
            :columns="$this->getColumns()"
            :widgets="$this->getWidgets()"
        />
    @endif
</x-filament-panels::page>