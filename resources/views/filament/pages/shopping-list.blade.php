<x-filament-panels::page>
    @if (empty($shoppingList))
        <div class="p-6 text-center text-gray-500">
            {{ __('No orders selected. Please select orders from the "My Orders" table and click "Generate Shopping List".') }}
        </div>
    @else
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($shoppingList as $aisle => $items)
                <x-filament::section :heading="$aisle">
                    <div class="space-y-4">
                        @foreach ($items as $item)
                            <div
                                class="flex justify-between items-start border-b border-gray-100 last:border-0 pb-2 last:pb-0">
                                <div>
                                    <div class="font-medium text-lg">{{ $item['name'] }}</div>
                                    @if (!empty($item['remarks']))
                                        <div class="text-sm text-gray-500 mt-1">
                                            @foreach ($item['remarks'] as $remark)
                                                <span
                                                    class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10">{{ $remark }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <div class="text-lg font-bold tabular-nums text-primary-600">
                                    {{ (float) $item['total_qty'] }} <span
                                        class="text-sm font-normal text-gray-500">{{ $item['unit'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-filament::section>
            @endforeach
        </div>
    @endif
</x-filament-panels::page>
