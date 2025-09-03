# Dashboard 布局结构图

## 小组件布局示意图

```mermaid
graph TD
    A[欢迎信息组件<br/>WelcomeMessage] --> B[订单统计概览<br/>OrdersOverview]
    A --> C[热门菜品统计<br/>PopularMenusOverview]
    A --> D[系统概览<br/>SystemOverview]
    
    B --> E[最近订单表格<br/>LatestOrders]
    C --> E
    D --> E
    
    E --> F[订单状态分布图表<br/>OrderStatusChart]
    E --> G[今日订单趋势图表<br/>TodayOrdersChart]
    
    style A fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
    style B fill:#dbeafe,stroke:#3b82f6,stroke-width:2px
    style C fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
    style D fill:#d1fae5,stroke:#10b981,stroke-width:2px
    style E fill:#f3e8ff,stroke:#8b5cf6,stroke-width:2px
    style F fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
    style G fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
```

## 数据流向图

```mermaid
flowchart LR
    A[Order模型] --> B[订单统计]
    A --> C[订单表格]
    A --> D[状态分布图表]
    A --> E[趋势图表]
    
    F[Menu模型] --> G[热门菜品统计]
    F --> H[系统概览]
    
    I[Material模型] --> H
    
    B --> J[Dashboard页面]
    C --> J
    D --> J
    E --> J
    G --> J
    H --> J
    
    style A fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
    style F fill:#dbeafe,stroke:#3b82f6,stroke-width:2px
    style I fill:#d1fae5,stroke:#10b981,stroke-width:2px
    style J fill:#f3e8ff,stroke:#8b5cf6,stroke-width:2px
```

## 组件类型分类

```mermaid
pie title 小组件类型分布
    "统计卡片" : 3
    "数据表格" : 1
    "图表组件" : 2
    "欢迎信息" : 1
```

## 响应式布局说明

```mermaid
graph LR
    A[桌面端<br/>4列布局] --> B[平板端<br/>2列布局]
    B --> C[手机端<br/>1列布局]
    
    D[全宽组件<br/>WelcomeMessage<br/>LatestOrders] --> E[标准组件<br/>统计卡片<br/>图表组件]
    
    style A fill:#dbeafe,stroke:#3b82f6,stroke-width:2px
    style B fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
    style C fill:#d1fae5,stroke:#10b981,stroke-width:2px
    style D fill:#f3e8ff,stroke:#8b5cf6,stroke-width:2px
    style E fill:#fef3c7,stroke:#f59e0b,stroke-width:2px
```

## 功能特性

- ✅ **实时数据更新**: 所有小组件都会实时反映数据库变化
- ✅ **响应式设计**: 适配不同屏幕尺寸
- ✅ **个性化欢迎**: 根据时间显示不同问候语
- ✅ **数据可视化**: 使用图表直观显示数据分布
- ✅ **状态管理**: 订单状态用颜色区分
- ✅ **空数据处理**: 优雅处理无数据情况
- ✅ **性能优化**: 使用数据库查询优化
