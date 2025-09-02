<?php

declare(strict_types=1);

return [
    'label' => '菜谱',

    'id'                => '菜谱ID',
    'title'             => '标题',
    'subtitle'          => '副标题',
    'content'           => '正文',
    'order_count'       => '点菜次数',
    'view_count'        => '浏览次数',
    'menu_level'        => '分类',
    'select_menu_level' => '请选择分类',
    'is_visible'        => '是否显示',
    'sort_order'        => '排序',
    'created_at'        => '创建时间',
    'updated_at'        => '更新时间',

    // 状态相关
    'visible'           => '显示',
    'hidden'            => '隐藏',
    'yes'               => '是',
    'no'                => '否',

    // 操作相关
    'create'            => '创建菜谱',
    'edit'              => '编辑菜谱',
    'delete'            => '删除菜谱',
    'view'              => '查看菜谱',
    'order'             => '点菜',
    'view_detail'       => '查看详情',

    // 统计相关
    'total_orders'      => '总菜谱数',
    'total_views'       => '总浏览数',
    'popular_orders'    => '热门菜谱',
    'recent_orders'     => '最新菜谱',
    'most_viewed'       => '最多浏览',

    // 表单相关
    'form' => [
        'title_placeholder'    => '请输入菜谱标题',
        'subtitle_placeholder' => '请输入副标题（可选）',
        'content_placeholder'  => '请输入菜谱详细内容',
        'sort_order_help'      => '数值越大排序越靠前',
    ],

    // 验证消息
    'validation' => [
        'title_required'       => '标题不能为空',
        'title_max'            => '标题不能超过255个字符',
        'subtitle_max'         => '副标题不能超过255个字符',
        'content_required'     => '正文内容不能为空',
        'menu_level_required'  => '请选择菜谱分类',
        'sort_order_numeric'   => '排序权重必须是数字',
    ],
];
