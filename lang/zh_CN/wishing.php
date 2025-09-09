<?php

declare(strict_types=1);

return [
    'label' => '许愿',
    'plural_label' => '许愿池',

    // 字段
    'id' => '许愿ID',
    'wisher_name' => '许愿人',
    'user_id' => '许愿人',
    'content' => '许愿内容',
    'status' => '许愿状态',
    'response' => '主理人回应',
    'accepted_at' => '受理时间',
    'fulfilled_at' => '实现时间',
    'rejected_at' => '抛弃时间',
    'created_at' => '许愿时间',
    'updated_at' => '更新时间',

    // 状态
    'status_options' => [
        'pending' => '待回应',
        'accepted' => '已受理',
        'fulfilled' => '已实现',
        'rejected' => '已抛弃',
    ],

    // 状态标签（带图标）
    'status_labels' => [
        'pending' => '⏳ 待回应',
        'accepted' => '✅ 已受理',
        'fulfilled' => '🎉 已实现',
        'rejected' => '❌ 已抛弃',
    ],

    // 操作相关
    'create' => '创建许愿',
    'edit' => '编辑许愿',
    'delete' => '删除许愿',
    'view' => '查看许愿',
    'accept' => '受理许愿',
    'fulfill' => '实现许愿',
    'reject' => '抛弃许愿',
    'respond' => '回应许愿',

    // 筛选相关
    'filter_pending' => '待回应',
    'filter_accepted' => '已受理',
    'filter_fulfilled' => '已实现',
    'filter_rejected' => '已抛弃',
    'all_status' => '全部状态',
    'by_wisher' => '按许愿人筛选',
    'select_wisher' => '选择许愿人',

    // 表单相关
    'form' => [
        'basic_info' => '许愿基本信息',
        'wisher_info' => '许愿人信息',
        'wishing_content' => '许愿内容',
        'response_content' => '回应内容',
        'wisher_name_placeholder' => '请选择许愿人',
        'content_placeholder' => '请描述您的愿望，比如希望吃到什么菜...',
        'response_placeholder' => '请输入对许愿的回应...',
        'status_help' => '选择许愿的处理状态',
    ],

    // 验证消息
    'validation' => [
        'user_id_required' => '请选择许愿人',
        'content_required' => '许愿内容不能为空',
        'content_min' => '许愿内容至少需要10个字符',
        'content_max' => '许愿内容不能超过500个字符',
        'status_required' => '请选择许愿状态',
        'status_invalid' => '无效的许愿状态',
        'response_max' => '回应内容不能超过500个字符',
    ],

    // 通知消息
    'notifications' => [
        'created' => '许愿创建成功',
        'updated' => '许愿更新成功',
        'deleted' => '许愿删除成功',
        'accepted' => '许愿已受理',
        'fulfilled' => '许愿已实现',
        'rejected' => '许愿已抛弃',
        'responded' => '回应已添加',
    ],

    // 统计相关
    'statistics' => [
        'total_wishings' => '总许愿数',
        'pending_count' => '待回应数量',
        'accepted_count' => '已受理数量',
        'fulfilled_count' => '已实现数量',
        'rejected_count' => '已抛弃数量',
        'fulfillment_rate' => '实现率',
        'response_rate' => '回应率',
    ],

    // 描述文本
    'descriptions' => [
        'wishing_pool' => '许愿池是土豆食堂的特色功能，让食客们可以许下美食愿望',
        'wisher_role' => '作为食客，您可以在这里许下您的美食愿望',
        'manager_role' => '作为主理人，您可以受理、实现或抛弃许愿',
        'status_flow' => '许愿流程：待回应 → 已受理 → 已实现/已抛弃',
    ],

    // 帮助文本
    'help' => [
        'how_to_wish' => '如何许愿：填写您的姓名和愿望内容，提交后等待主理人回应',
        'how_to_manage' => '如何管理：查看许愿列表，选择受理、实现或抛弃',
        'status_meanings' => '状态说明：待回应-等待处理，已受理-正在准备，已实现-愿望达成，已抛弃-无法实现',
    ],
];
