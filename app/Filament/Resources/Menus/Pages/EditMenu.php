<?php

namespace App\Filament\Resources\Menus\Pages;

use App\Filament\Resources\Menus\MenuResource;
use App\Models\MenuMaterial;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditMenu extends EditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // 加载现有的物料数据
        $menuMaterials = $this->record->menuMaterials()
            ->with(['material', 'unit'])
            ->get()
            ->map(function ($menuMaterial) {
                return [
                    'material_id' => $menuMaterial->material_id,
                    'unit_id' => $menuMaterial->unit_id,
                    'quantity' => $menuMaterial->quantity,
                ];
            })
            ->toArray();

        $data['menu_materials'] = $menuMaterials;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // 提取物料数据
        $menuMaterials = $data['menu_materials'] ?? [];
        unset($data['menu_materials']);

        return $data;
    }

    protected function afterSave(): void
    {
        // 删除现有的物料关联
        $this->record->menuMaterials()->delete();

        // 保存新的物料关联数据
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
