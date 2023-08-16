<x-filament::page>


        <div class="filament-tables-container rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="filament-tables-header-container" x-show="hasHeader = true || selectedRecords.length">
                    <div class="px-2 pt-2">
                        <div class="filament-tables-header px-4 py-2 mb-2">
                            <div class="flex flex-col gap-4 md:-mr-2 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <h2 class="filament-tables-header-heading text-xl font-bold tracking-tight"> {{ __('Produção por Produto') }}</h2>
                                    <p class="filament-tables-header-description"></p>
                                </div>
                                <div class="filament-tables-actions-container flex items-center gap-4 flex-wrap justify-end shrink-0">
                                </div>
                            </div>
                        </div>
                        <div aria-hidden="true" class="filament-hr border-t dark:border-gray-700" x-show="false || selectedRecords.length" style="display: none;"></div>
                        </div>
                        <div x-show="false || selectedRecords.length" class="filament-tables-header-toolbar flex h-14 items-center justify-between p-2" x-bind:class="{
                                            'gap-2': false || selectedRecords.length,
                                        }" style="display: none;">
                            <div class="flex items-center gap-2">
                                <div class="filament-dropdown filament-tables-bulk-actions" x-show="selectedRecords.length" x-data="{
                                    toggle: function (event) {
                                        $refs.panel.toggle(event)
                                    },
                                    open: function (event) {
                                        $refs.panel.open(event)
                                    },
                                    close: function (event) {
                                        $refs.panel.close(event)
                                    },
                                }" style="display: none;">
                            <div x-on:click="toggle" class="filament-dropdown-trigger cursor-pointer">
                                <button title="Ações abertas" type="button" class="filament-icon-button relative flex items-center justify-center rounded-full outline-none hover:bg-gray-500/5 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-70 text-primary-500 focus:bg-primary-500/10 dark:hover:bg-gray-300/5 h-10 w-10 filament-tables-bulk-actions-trigger">
                                    <span class="sr-only">
                                        Ações abertas
                                    </span>
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-icon-button-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                </button>
                            </div>
                            <div x-ref="panel" x-float.placement.bottom-start.flip.offset="{ offset: 8 }" x-transition:enter-start="scale-95 opacity-0" x-transition:leave-end="scale-95 opacity-0" class="filament-dropdown-panel absolute z-10 w-full divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-black/5 transition dark:divide-gray-700 dark:bg-gray-800 dark:ring-white/10 max-w-[14rem]" style="display: none;">
                                <div class="filament-dropdown-list p-1" dark-mode="dark-mode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filament-tables-table-container relative overflow-x-auto dark:border-gray-700 border-t" x-bind:class="{
                            'rounded-t-xl': ! hasHeader,
                            'border-t': hasHeader,
                        }">
                <table class="filament-tables-table w-full table-auto divide-y text-start dark:divide-gray-700">
                    <thead class="divide-y whitespace-nowrap dark:divide-gray-700">
                        <tr class="filament-tables-row transition">
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Nome
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Descartados
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Defeitos
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Aprovado
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center justify-end gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Produzidos
                                    </span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">
                        @foreach ( $this->getProductionDayByProduct() as $productDay )
                            <tr class="filament-tables-row transition">
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4 ">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productDay->product}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productDay->discard}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productDay->defect}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productDay->approved}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-end text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productDay->amount}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                    
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>  

        {{-- -------- --}}
        <div class="filament-tables-container rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="filament-tables-header-container" x-show="hasHeader = true || selectedRecords.length">
                    <div class="px-2 pt-2">
                        <div class="filament-tables-header px-4 py-2 mb-2">
                            <div class="flex flex-col gap-4 md:-mr-2 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <h2 class="filament-tables-header-heading text-xl font-bold tracking-tight">{{ __('Produção por Usuário') }}</h2>
                                    <p class="filament-tables-header-description"></p>
                                </div>
                                <div class="filament-tables-actions-container flex items-center gap-4 flex-wrap justify-end shrink-0">
                                </div>
                            </div>
                        </div>
                        <div aria-hidden="true" class="filament-hr border-t dark:border-gray-700" x-show="false || selectedRecords.length" style="display: none;"></div>
                        </div>
                        <div x-show="false || selectedRecords.length" class="filament-tables-header-toolbar flex h-14 items-center justify-between p-2" x-bind:class="{
                                            'gap-2': false || selectedRecords.length,
                                        }" style="display: none;">
                            <div class="flex items-center gap-2">
                                <div class="filament-dropdown filament-tables-bulk-actions" x-show="selectedRecords.length" x-data="{
                                    toggle: function (event) {
                                        $refs.panel.toggle(event)
                                    },
                                    open: function (event) {
                                        $refs.panel.open(event)
                                    },
                                    close: function (event) {
                                        $refs.panel.close(event)
                                    },
                                }" style="display: none;">
                            <div x-on:click="toggle" class="filament-dropdown-trigger cursor-pointer">
                                <button title="Ações abertas" type="button" class="filament-icon-button relative flex items-center justify-center rounded-full outline-none hover:bg-gray-500/5 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-70 text-primary-500 focus:bg-primary-500/10 dark:hover:bg-gray-300/5 h-10 w-10 filament-tables-bulk-actions-trigger">
                                    <span class="sr-only">
                                        Ações abertas
                                    </span>
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-icon-button-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                </button>
                            </div>
                            <div x-ref="panel" x-float.placement.bottom-start.flip.offset="{ offset: 8 }" x-transition:enter-start="scale-95 opacity-0" x-transition:leave-end="scale-95 opacity-0" class="filament-dropdown-panel absolute z-10 w-full divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-black/5 transition dark:divide-gray-700 dark:bg-gray-800 dark:ring-white/10 max-w-[14rem]" style="display: none;">
                                <div class="filament-dropdown-list p-1" dark-mode="dark-mode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filament-tables-table-container relative overflow-x-auto dark:border-gray-700 border-t" x-bind:class="{
                            'rounded-t-xl': ! hasHeader,
                            'border-t': hasHeader,
                        }">
                <table class="filament-tables-table w-full table-auto divide-y text-start dark:divide-gray-700">
                    <thead class="divide-y whitespace-nowrap dark:divide-gray-700">
                        <tr class="filament-tables-row transition">
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Produtor
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Descartados
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Defeitos
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Aprovado
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center justify-end gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Produzidos
                                    </span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">
                        @foreach ( $this->getProductionDayByProducer() as $productionDay )
                            <tr class="filament-tables-row transition">
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4 ">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productionDay->producer}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productionDay->discard}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productionDay->defect}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productionDay->approved}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-end text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$productionDay->amount}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                      
                        @endforeach
                        
                    </tbody>
                </table>
            </div>
        </div>    

        {{-- -------- --}}
        <div class="filament-tables-container rounded-xl border border-gray-300 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="filament-tables-header-container" x-show="hasHeader = true || selectedRecords.length">
                    <div class="px-2 pt-2">
                        <div class="filament-tables-header px-4 py-2 mb-2">
                            <div class="flex flex-col gap-4 md:-mr-2 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <h2 class="filament-tables-header-heading text-xl font-bold tracking-tight">{{ __('Produção por Usuário e Produto') }}</h2>
                                    <p class="filament-tables-header-description"></p>
                                </div>
                                <div class="filament-tables-actions-container flex items-center gap-4 flex-wrap justify-end shrink-0">
                                </div>
                            </div>
                        </div>
                        <div aria-hidden="true" class="filament-hr border-t dark:border-gray-700" x-show="false || selectedRecords.length" style="display: none;"></div>
                        </div>
                        <div x-show="false || selectedRecords.length" class="filament-tables-header-toolbar flex h-14 items-center justify-between p-2" x-bind:class="{
                                            'gap-2': false || selectedRecords.length,
                                        }" style="display: none;">
                            <div class="flex items-center gap-2">
                                <div class="filament-dropdown filament-tables-bulk-actions" x-show="selectedRecords.length" x-data="{
                                    toggle: function (event) {
                                        $refs.panel.toggle(event)
                                    },
                                    open: function (event) {
                                        $refs.panel.open(event)
                                    },
                                    close: function (event) {
                                        $refs.panel.close(event)
                                    },
                                }" style="display: none;">
                            <div x-on:click="toggle" class="filament-dropdown-trigger cursor-pointer">
                                <button title="Ações abertas" type="button" class="filament-icon-button relative flex items-center justify-center rounded-full outline-none hover:bg-gray-500/5 disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-70 text-primary-500 focus:bg-primary-500/10 dark:hover:bg-gray-300/5 h-10 w-10 filament-tables-bulk-actions-trigger">
                                    <span class="sr-only">
                                        Ações abertas
                                    </span>
                                        <svg wire:loading.remove.delay="" wire:target="" class="filament-icon-button-icon w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                </button>
                            </div>
                            <div x-ref="panel" x-float.placement.bottom-start.flip.offset="{ offset: 8 }" x-transition:enter-start="scale-95 opacity-0" x-transition:leave-end="scale-95 opacity-0" class="filament-dropdown-panel absolute z-10 w-full divide-y divide-gray-100 rounded-lg bg-white shadow-lg ring-1 ring-black/5 transition dark:divide-gray-700 dark:bg-gray-800 dark:ring-white/10 max-w-[14rem]" style="display: none;">
                                <div class="filament-dropdown-list p-1" dark-mode="dark-mode">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="filament-tables-table-container relative overflow-x-auto dark:border-gray-700 border-t" x-bind:class="{
                            'rounded-t-xl': ! hasHeader,
                            'border-t': hasHeader,
                        }">
                <table class="filament-tables-table w-full table-auto divide-y text-start dark:divide-gray-700">
                    <thead class="divide-y whitespace-nowrap dark:divide-gray-700">
                        <tr class="filament-tables-row transition">
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Produtor
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Produto
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Descartados
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Defeitos
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Aprovado
                                    </span>
                                </button>
                            </th>
                            <th class="filament-tables-header-cell p-0 filament-table-header-cell-id">
                                <button type="button" class="flex w-full items-center justify-end gap-x-1 whitespace-nowrap px-4 py-2 text-sm font-medium text-gray-600 dark:text-gray-300 cursor-default ">
                                    <span>
                                        Produzidos
                                    </span>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y whitespace-nowrap dark:divide-gray-700">      
                        @foreach ( $this->getProductionDayByProducerAndProduct() as $producerAndProduct )                            
                            <tr class="filament-tables-row transition">
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4 ">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$producerAndProduct->producer}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4 ">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$producerAndProduct->product}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$producerAndProduct->discard}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$producerAndProduct->defect}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-start text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$producerAndProduct->approved}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="filament-tables-reorder-cell w-4 whitespace-nowrap px-4">
                                    <div class="filament-tables-column-wrapper">
                                        <div class="flex w-full justify-end text-start">
                                            <div class="filament-tables-text-column px-4 py-3">
                                                <div class="inline-flex items-center space-x-1 rtl:space-x-reverse">
                                                    <span class="">
                                                        {{$producerAndProduct->amount}}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach                     
                    </tbody>
                </table>
            </div>
        </div>      
        

</x-filament::page>
