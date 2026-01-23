<?php

return [
    'model_label' => '失败任务表',
    'fields' => [
        'uuid' => 'UUID',
        'connection' => '连接',
        'queue' => '队列',
        'payload' => '载荷',
        'exception' => '异常信息',
        'failed_at' => '失败时间',
    ],
];
