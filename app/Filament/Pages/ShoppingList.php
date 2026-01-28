<?php

namespace App\Filament\Pages;

use App\Enums\IngredientUnit;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ShoppingList extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = '采购单';

    protected static ?string $title = '采购单';

    protected string $view = 'filament.pages.shopping-list';

    public $shoppingList = [];

    public function mount()
    {
        $orderIds = explode(',', request()->query('orders', ''));
        $orders = \App\Models\Order::whereIn('id', $orderIds)->with('items.dish.ingredients.aisle')->get();

        $list = [];

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                foreach ($item->dish->ingredients as $ing) {
                    $aisle = $ing->aisle->name ?? __('Uncategorized');
                    $key = $ing->id;

                    if (! isset($list[$aisle][$key])) {
                        $list[$aisle][$key] = [
                            'name' => $ing->name,
                            'unit' => IngredientUnit::tryFrom($ing->pivot->unit)?->getLabel() ?? $ing->pivot->unit,
                            'total_qty' => 0,
                            'remarks' => [],
                        ];
                    }

                    // Track 1: Quantity
                    $neededQty = $ing->pivot->quantity * $item->quantity;
                    $list[$aisle][$key]['total_qty'] += $neededQty;

                    // Track 2: Remarks
                    if (! empty($ing->pivot->remark)) {
                        if (! in_array($ing->pivot->remark, $list[$aisle][$key]['remarks'])) {
                            $list[$aisle][$key]['remarks'][] = $ing->pivot->remark;
                        }
                    }
                }
            }
        }

        $this->shoppingList = $list;
    }
}
