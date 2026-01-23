<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrderNow extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = '点餐台';

    protected static ?string $title = '开始点餐';

    protected string $view = 'filament.pages.order-now';

    public static function canAccess(array $parameters = []): bool
    {
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
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'mealDate' => now()->format('Y-m-d'),
            'mealPeriod' => 'dinner',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('mealDate')
                    ->label(__('When?'))
                    ->displayFormat('Y-m-d')
                    ->native(false)
                    ->required(),
                Select::make('mealPeriod')
                    ->label(__('Kind?'))
                    ->native(false)
                    ->options([
                        'lunch' => __('Lunch'),
                        'dinner' => __('Dinner'),
                        'snack' => __('Snack'),
                    ])
                    ->required(),
                Textarea::make('customerNote')
                    ->label(__('Special Requests?'))
                    ->placeholder(__('e.g. Can we eat earlier?'))
                    ->autosize()
                    ->columnSpanFull(),
            ])
            ->statePath('data')
            ->columns(2);
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
            $dish = \App\Models\Dish::find($dishId);
            if (! $dish) {
                return;
            }
            $this->cart[$dishId] = [
                'qty' => 1,
                'note' => '',
                'name' => $dish->name, // Cache name for UI
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

        $data = $this->form->getState();

        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'status' => 'pending',
            'meal_date' => $data['mealDate'],
            'meal_period' => $data['mealPeriod'],
            'customer_note' => $data['customerNote'] ?? null,
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

        $this->reset(['cart']);
        $this->form->fill([
            'mealDate' => now()->format('Y-m-d'),
            'mealPeriod' => 'dinner',
            'customerNote' => '',
        ]);
    }
}
