<?php

namespace App\Filament\Resources\Menus\Pages;

use App\Filament\Resources\Menus\MenuResource;
use App\Models\MenuMaterial;
use Filament\Resources\Pages\CreateRecord;

class CreateMenu extends CreateRecord
{
    protected static string $resource = MenuResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // 提取物料数据
        $menuMaterials = $data['menu_materials'] ?? [];
        unset($data['menu_materials']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // 保存物料关联数据
        $menuMaterials = $this->form->getState()['menu_materials'] ?? [];

        foreach ($menuMaterials as $materialData) {
            if (!empty($materialData['material_id']) && !empty($materialData['unit_id'])) {
                MenuMaterial::create([
                    'menu_id' => $this->record->id,
                    'material_id' => $materialData['material_id'],
                    'unit_id' => $materialData['unit_id'],
                    'quantity' => $materialData['quantity'] ?? 1,
                ]);
            }
        }
    }
}
