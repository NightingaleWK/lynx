<x-filament-panels::page class="h-full">
    <div class="h-full" x-data="{ cartOpen: {{ count($cart) > 0 ? 'true' : 'false' }} }" x-on:open-cart.window="cartOpen = true">
        <div class="flex h-[calc(100vh-8rem)] gap-6">
            <!-- Sidebar: Categories -->
            <div class="w-64 flex-shrink-0 hidden md:flex flex-col gap-2 overflow-y-auto pr-2">
                <button wire:click="$set('categoryId', null)"
                    class="text-left px-4 py-3 rounded-xl transition-all duration-200 @if (is_null($categoryId)) bg-primary-50 text-primary-600 font-bold ring-1 ring-primary-500/20 shadow-sm @else hover:bg-gray-50 text-gray-600 @endif">
                    🔥 {{ __('All Dishes') }}
                </button>
                @foreach ($this->categories as $category)
                    <button wire:click="$set('categoryId', {{ $category->id }})"
                        class="text-left px-4 py-3 rounded-xl transition-all duration-200 flex justify-between items-center group @if ($categoryId === $category->id) bg-primary-50 text-primary-600 font-bold ring-1 ring-primary-500/20 shadow-sm @else hover:bg-gray-50 text-gray-600 @endif">
                        <span>{{ $category->name }}</span>
                        <span
                            class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full group-hover:bg-white transition-colors">
                            {{ $category->dishes_count }}
                        </span>
                    </button>
                @endforeach
            </div>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col h-full">
                <!-- Search Bar -->
                <div class="mb-6 sticky top-0 z-10 bg-gray-50/95 backdrop-blur pt-1 pb-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <x-heroicon-o-magnifying-glass class="h-5 w-5 text-gray-400" />
                        </div>
                        <input wire:model.live.debounce.300ms="search" type="search"
                            class="block w-full pl-10 pr-3 py-2.5 border-none ring-1 ring-gray-200 rounded-xl bg-white focus:ring-2 focus:ring-primary-500 transition-shadow shadow-sm placeholder-gray-400"
                            placeholder="{{ __('Search for your cravings...') }}">
                    </div>
                </div>

                <!-- Dishes Grid -->
                <div class="flex-1 overflow-y-auto pb-20">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach ($this->dishes as $dish)
                            <div wire:key="dish-{{ $dish->id }}"
                                class="group bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 transform hover:-translate-y-1">
                                {{-- Dish Image --}}
                                <div
                                    class="h-40 bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center relative group-hover:from-indigo-100 group-hover:to-purple-100 transition-colors overflow-hidden">
                                    @if (!empty($dish->images) && isset($dish->images[0]))
                                        <img src="{{ Storage::url($dish->images[0]) }}" alt="{{ $dish->name }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span class="text-3xl">🍲</span>
                                    @endif
                                    <button wire:click="addToCart({{ $dish->id }})"
                                        class="absolute bottom-3 right-3 bg-white/90 backdrop-blur text-primary-600 p-2 rounded-full shadow-sm hover:bg-primary-500 hover:text-white transition-all transform hover:scale-110 active:scale-95">
                                        <x-heroicon-m-plus class="w-5 h-5" />
                                    </button>
                                </div>

                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3
                                            class="font-bold text-gray-800 line-clamp-1 group-hover:text-primary-600 transition-colors">
                                            {{ $dish->name }}</h3>
                                    </div>

                                    <p class="text-xs text-gray-500 line-clamp-2 mb-3 h-8">
                                        {{ $dish->description ?? __('Delicious homemade goodness.') }}
                                    </p>

                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        <span class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-md">
                                            <x-heroicon-m-calendar class="w-3 h-3" />
                                            {{ $dish->last_eaten_at ? \Carbon\Carbon::parse($dish->last_eaten_at)->diffForHumans() : __('Never') }}
                                        </span>
                                        <span class="flex items-center gap-1 bg-gray-50 px-2 py-1 rounded-md">
                                            <x-heroicon-m-arrow-path class="w-3 h-3" />
                                            {{ $dish->frequency }}x
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Cart Button (Always Visible) -->
        <div class="fixed bottom-6 right-6 z-30">
            <button @click="cartOpen = true"
                class="bg-primary-600 text-white p-4 rounded-full shadow-lg hover:bg-primary-700 transition-colors relative">
                <x-heroicon-m-shopping-bag class="w-6 h-6" />
                @if (count($cart) > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">
                        {{ array_sum(array_column($cart, 'qty')) }}
                    </span>
                @endif
            </button>
        </div>

        <!-- Cart Drawer -->
        <!-- Cart Drawer -->
        <div x-show="cartOpen" x-transition:enter="transform transition ease-in-out duration-300"
            x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transform transition ease-in-out duration-300" x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 w-full md:w-96 bg-white shadow-2xl z-40 border-l border-gray-100 flex flex-col"
            style="display: none;">
            <!-- Drawer Header -->
            <div class="p-6 border-b border-gray-100 bg-white z-10 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-black text-gray-800">{{ __('Your Tray') }}</h2>
                    <p class="text-sm text-gray-500">{{ __('Ready for service?') }}</p>
                </div>
                <!-- Modify Close Button to support desktop -->
                <button @click="cartOpen = false" class="text-gray-400 hover:text-gray-600">
                    <x-heroicon-m-x-mark class="w-6 h-6" />
                </button>
            </div>

            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                @if (empty($cart))
                    <div class="h-full flex flex-col items-center justify-center text-center space-y-4 opacity-50">
                        <x-heroicon-o-shopping-bag class="w-16 h-16 text-gray-300" />
                        <p class="text-gray-500">{{ __('Your tray is empty.') }}</p>
                    </div>
                @else
                    @foreach ($cart as $dishId => $item)
                        <div wire:key="cart-item-{{ $dishId }}" class="flex gap-4 group">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0 overflow-hidden">
                                @php
                                    $dish = \App\Models\Dish::find($dishId);
                                @endphp
                                @if ($dish && !empty($dish->images) && isset($dish->images[0]))
                                    <img src="{{ Storage::url($dish->images[0]) }}" alt="{{ $item['name'] }}"
                                        class="w-full h-full object-cover">
                                @else
                                    🍲
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-gray-800">{{ $item['name'] }}</h4>
                                    <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-1">
                                        <button wire:click="updateQuantity({{ $dishId }}, -1)"
                                            class="w-6 h-6 flex items-center justify-center text-gray-500 hover:bg-white hover:shadow-sm rounded transition-all">-</button>
                                        <span class="text-sm font-medium w-4 text-center">{{ $item['qty'] }}</span>
                                        <button wire:click="updateQuantity({{ $dishId }}, 1)"
                                            class="w-6 h-6 flex items-center justify-center text-primary-600 hover:bg-white hover:shadow-sm rounded transition-all">+</button>
                                    </div>
                                </div>
                                <input wire:model.live.debounce.500ms="cart.{{ $dishId }}.note" type="text"
                                    class="w-full text-xs border-0 bg-gray-50 rounded-md focus:ring-1 focus:ring-primary-500 placeholder-gray-400 py-1.5 px-3"
                                    placeholder="{{ __('Note: e.g. Less spicy') }}">
                            </div>
                        </div>
                    @endforeach

                    <!-- Meal Settings -->
                    <div class="border-t border-gray-100 pt-6 mt-6 space-y-4">
                        <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wider">{{ __('Order Settings') }}
                        </h3>

                        {{ $this->form }}
                    </div>
                @endif
            </div>

            <!-- Checkout Footer -->
            @if (!empty($cart))
                <div class="p-6 border-t border-gray-100 bg-gray-50">
                    <button wire:click="submitOrder" wire:loading.attr="disabled"
                        class="w-full bg-primary-600 text-white py-4 rounded-xl font-bold text-lg shadow-lg shadow-primary-500/30 hover:bg-primary-700 hover:shadow-primary-500/50 transition-all transform active:scale-95 flex justify-center items-center gap-2">
                        <span wire:loading.remove>{{ __('Place Order') }}</span>
                        <span wire:loading class="animate-spin">🌀</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
