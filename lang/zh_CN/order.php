<?php

declare(strict_types=1);

return [
    'label' => '订单',
    'plural_label' => '订单管理',

    // 字段
    'id' => '订单ID',
    'order_number' => '订单号',
    'dining_time' => '用餐时间',
    'remarks' => '备注信息',
    'status' => '订单状态',
    'created_at' => '下单时间',
    'updated_at' => '更新时间',

    // 状态
    'status_options' => [
        'pending' => '待确认',
        'confirmed' => '已确认',
        'cooking' => '制作中',
        'completed' => '已完成',
        'cancelled' => '已取消',
    ],

    // 统计相关
    'total_quantity' => '总数量',
    'menu_details' => '菜品详情',
    'items_count' => '菜品数量',
    'total_items' => '菜品种类',

    // 操作相关
    'create' => '创建订单',
    'edit' => '编辑订单',
    'delete' => '删除订单',
    'view' => '查看订单',
    'confirm' => '确认订单',
    'start_cooking' => '开始制作',
    'complete' => '完成订单',
    'cancel' => '取消订单',

    // 筛选相关
    'filter_today' => '今日订单',
    'filter_pending' => '待处理',
    'filter_in_progress' => '进行中',
    'all_status' => '全部状态',
    'dining_date' => '用餐日期',
    'select_date' => '选择日期',

    // 表单相关
    'form' => [
        'basic_info' => '订单基本信息',
        'order_items' => '点餐内容',
        'menu_list' => '菜品列表',
        'dining_time_help' => '请选择用餐时间',
        'remarks_placeholder' => '请输入特殊要求，如不吃辣等...',
        'auto_generated' => '系统自动生成',
    ],

    // 验证消息
    'validation' => [
        'order_number_required' => '订单号不能为空',
        'dining_time_required' => '请选择用餐时间',
        'status_required' => '请选择订单状态',
        'items_required' => '请至少添加一个菜品',
    ],

    // 通知消息
    'notifications' => [
        'created' => '订单创建成功',
        'updated' => '订单更新成功',
        'deleted' => '订单删除成功',
        'confirmed' => '订单已确认',
        'cooking' => '开始制作',
        'completed' => '订单已完成',
        'cancelled' => '订单已取消',
    ],
];
