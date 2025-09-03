<?php

declare(strict_types=1);

return [
    'label' => '订单项',
    'plural_label' => '订单项',

    // 字段
    'id' => '项目ID',
    'order_id' => '订单ID',
    'menu_id' => '菜品ID',
    'menu' => '菜品',
    'quantity' => '数量',
    'item_remarks' => '单品备注',
    'created_at' => '添加时间',
    'updated_at' => '更新时间',

    // 关联字段
    'menu_name' => '菜品名称',
    'menu_category' => '分类',

    // 操作相关
    'add_menu' => '添加菜品',
    'edit_item' => '编辑项目',
    'delete_item' => '删除项目',
    'select_menu' => '请选择菜品',

    // 表单相关
    'form' => [
        'quantity_help' => '请输入数量',
        'remarks_placeholder' => '如：不要香菜',
        'menu_required' => '请选择菜品',
        'quantity_min' => '数量至少为1',
    ],

    // 单位
    'unit_piece' => '份',
    'unit_item' => '道菜',

    // 备注相关
    'no_remarks' => '无备注',
    'with_remarks' => '有备注',

    // 验证消息
    'validation' => [
        'menu_required' => '请选择菜品',
        'quantity_required' => '请输入数量',
        'quantity_min' => '数量不能少于1份',
        'quantity_numeric' => '数量必须是数字',
    ],
];
