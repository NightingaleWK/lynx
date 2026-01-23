<?php

return [
    'model_label' => '任务批次表',
    'fields' => [
        'id' => 'ID',
        'name' => '批次名称',
        'total_jobs' => '总任务数',
        'pending_jobs' => '待处理任务数',
        'failed_jobs' => '失败任务数',
        'failed_job_ids' => '失败任务ID集合',
        'options' => '选项',
        'cancelled_at' => '取消时间',
        'created_at' => '创建时间',
        'finished_at' => '完成时间',
    ],
];
