<x-filament-panels::page>
    @if ($this->getHeaderWidgets())
        <x-filament-widgets::widgets
            :columns="$this->getHeaderWidgetsColumns()"
            :widgets="$this->getHeaderWidgets()"
            class="mb-6"
        />
    @endif
</x-filament-panels::page>