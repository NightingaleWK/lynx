<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;

class OrderNow extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = '点餐台';

    protected static ?string $title = '开始点餐';

    protected string $view = 'filament.pages.order-now';

    public static function canAccess(array $parameters = []): bool
    {
        // Revert to strict check for production similar environment,
        // or keep true if user still having issues, but user asked to be like shopping-list.
        // I will use permission check but make sure to direct user to fix permissions if needed.
        // Actually user said "还是不行", so I'll keep it simple for now or fix the route.
        // Standalone page doesn't have route nesting issues.
        return auth()->check() && auth()->user()->can('OrderNow');
    }

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return static::canAccess($parameters);
    }

    public $categoryId = null;
    public $search = '';

    // Cart: ['dish_id' => ['qty' => 1, 'notes' => 'less spicy']]
    public $cart = [];

    // Order Settings
    public $mealDate;
    public $mealPeriod = 'dinner';
    public $chefNote = '';
    public $customerNote = '';

    public function mount(): void
    {
        $this->mealDate = now()->format('Y-m-d');
    }

    public function getCategoriesProperty()
    {
        return \App\Models\Category::withCount('dishes')->get();
    }

    public function getDishesProperty()
    {
        return \App\Models\Dish::query()
            ->when($this->categoryId, fn($q) => $q->where('category_id', $this->categoryId))
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->get();
    }

    public function addToCart($dishId)
    {
        if (isset($this->cart[$dishId])) {
            $this->cart[$dishId]['qty']++;
        } else {
            $this->cart[$dishId] = [
                'qty' => 1,
                'note' => '',
                'name' => \App\Models\Dish::find($dishId)->name, // Cache name for UI
            ];
        }
    }

    public function removeFromCart($dishId)
    {
        unset($this->cart[$dishId]);
    }

    public function updateQuantity($dishId, $delta)
    {
        if (! isset($this->cart[$dishId])) return;

        $this->cart[$dishId]['qty'] += $delta;

        if ($this->cart[$dishId]['qty'] <= 0) {
            $this->removeFromCart($dishId);
        }
    }

    public function submitOrder()
    {
        if (empty($this->cart)) {
            \Filament\Notifications\Notification::make()
                ->title('Cart is empty')
                ->warning()
                ->send();
            return;
        }

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'meal_date' => $this->mealDate,
            'meal_period' => $this->mealPeriod,
            'customer_note' => $this->customerNote,
        ]);

        foreach ($this->cart as $dishId => $item) {
            $order->items()->create([
                'dish_id' => $dishId,
                'quantity' => $item['qty'],
                'note' => $item['note'],
            ]);
        }

        \Filament\Notifications\Notification::make()
            ->title('Order placed successfully!')
            ->success()
            ->send();

        $this->reset(['cart', 'customerNote']);
        // Redirect or stay? Let's stay for now.
    }
}
