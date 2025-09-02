<?php

declare(strict_types=1);

return [
    'label' => 'Menu',

    'id'                => 'Menu ID',
    'title'             => 'Title',
    'subtitle'          => 'Subtitle',
    'content'           => 'Content',
    'order_count'       => 'Order Count',
    'view_count'        => 'View Count',
    'menu_level'        => 'Category',
    'select_menu_level' => 'Please select category',
    'is_visible'        => 'Is Visible',
    'sort_order'        => 'Sort Order',
    'created_at'        => 'Created At',
    'updated_at'        => 'Updated At',

    // Status related
    'visible'           => 'Visible',
    'hidden'            => 'Hidden',
    'yes'               => 'Yes',
    'no'                => 'No',

    // Actions related
    'create'            => 'Create Menu',
    'edit'              => 'Edit Menu',
    'delete'            => 'Delete Menu',
    'view'              => 'View Menu',
    'order'             => 'Order',
    'view_detail'       => 'View Details',

    // Statistics related
    'total_orders'      => 'Total Menus',
    'total_views'       => 'Total Views',
    'popular_orders'    => 'Popular Menus',
    'recent_orders'     => 'Recent Menus',
    'most_viewed'       => 'Most Viewed',

    // Form related
    'form' => [
        'title_placeholder'    => 'Please enter menu title',
        'subtitle_placeholder' => 'Please enter subtitle (optional)',
        'content_placeholder'  => 'Please enter menu content',
        'sort_order_help'      => 'Higher values appear first',
    ],

    // Validation messages
    'validation' => [
        'title_required'       => 'Title is required',
        'title_max'            => 'Title cannot exceed 255 characters',
        'subtitle_max'         => 'Subtitle cannot exceed 255 characters',
        'content_required'     => 'Content is required',
        'menu_level_required'  => 'Please select menu category',
        'sort_order_numeric'   => 'Sort order must be numeric',
    ],
];
