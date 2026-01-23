<?php

return [

    'single' => [

        'label' => '删除',

        'modal' => [

            'heading' => '删除 :label',

            'actions' => [

                'delete' => [
                    'label' => '删除',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => '已删除',
            ],

        ],

    ],

    'multiple' => [

        'label' => '删除已选项目',

        'modal' => [

            'heading' => '删除已选 :label',

            'actions' => [

                'delete' => [
                    'label' => '删除已选项目',
                ],

            ],

        ],

        'notifications' => [

            'deleted' => [
                'title' => '已删除',
            ],

            'deleted_partial' => [
                'title' => '部分记录已删除',
                'missing_processing_failure_message' => '有 :count 条记录无法被删除。',
            ],

            'deleted_none' => [
                'title' => '删除失败',
                'missing_processing_failure_message' => '有 :count 条记录无法被删除。',
            ],

        ],

    ],

];
