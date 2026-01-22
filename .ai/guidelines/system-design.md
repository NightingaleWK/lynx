# **KitchenOS \- 家庭智能点餐系统 V1.1 架构设计书**

**版本**: 1.1 (适配 Filament Shield & Laravel Lang)

**技术栈**: Laravel 12 \+ Filament V5 \+ **Filament Shield** \+ **Laravel Lang**

**核心理念**: 前端极简体验（SPA感），后端严谨数据（双轨制），生活流逻辑（备餐计划）。

## **1\. 核心实体关系图 (ER Diagram)**

**变更说明**: 移除了 USERS 表的 role 字段，权限管理完全委托给 Filament Shield (基于 spatie/laravel-permission)。

erDiagram  
    %% 用户与权限 (由 Filament Shield 管理)  
    USERS {  
        bigint id PK  
        string name  
        string email  
        %% role 字段已移除，改为关联 model\_has\_roles 表  
    }  
      
    ROLES {  
        bigint id PK  
        string name "super\_admin, partner"  
    }

    %% 基础数据：食材与购买分区  
    INGREDIENT\_AISLES {  
        bigint id PK  
        string name "肉禽蛋, 果蔬, 调味"  
        int sort\_order "购买动线排序"  
    }  
      
    INGREDIENTS {  
        bigint id PK  
        string name "猪里脊, 盐"  
        string base\_unit "g, ml, pc (系统基准单位)"  
        bigint aisle\_id FK  
    }

    %% 菜谱 (核心资产)  
    CATEGORIES {  
        bigint id PK  
        string name "热菜, 凉菜, 汤羹"  
    }  
      
    DISHES {  
        bigint id PK  
        string name  
        bigint category\_id FK  
        text description  
        timestamp last\_eaten\_at "上次食用时间 (冗余字段)"  
        int frequency "点单次数 (冗余字段)"  
        %% Tags 使用 spatie/laravel-tags  
        %% Images 使用 spatie/laravel-medialibrary  
    }

    %% 核心关联：双轨制食材记录  
    DISH\_INGREDIENT {  
        bigint dish\_id FK  
        bigint ingredient\_id FK  
        decimal quantity "数值轨: 15.00 (用于计算)"  
        string unit "快照轨: g (防止单位变更)"  
        string remark "文本轨: 2勺, 少许 (用于展示)"  
    }

    %% 订单流  
    ORDERS {  
        bigint id PK  
        bigint user\_id FK  
        string status "draft, pending, processing, served, completed"  
        date meal\_date "用餐日期: 2025-10-01"  
        string meal\_period "lunch, dinner, snack"  
        text chef\_note "大厨备注"  
        text customer\_note "整单备注"  
    }

    ORDER\_ITEMS {  
        bigint id PK  
        bigint order\_id FK  
        bigint dish\_id FK  
        int quantity "份数"  
        string note "单品备注: 少辣"  
    }

    %% 评价系统  
    REVIEWS {  
        bigint id PK  
        bigint order\_id FK  
        int rating "整单评分 1-5"  
        text comment  
        %% Images 关联买家秀  
    }  
      
    REVIEW\_ITEMS {  
        bigint id PK  
        bigint review\_id FK  
        bigint dish\_id FK  
        int rating "单品评分"  
    }

    %% 关系连线  
    USERS }|--|{ ROLES : "has\_roles"  
    INGREDIENT\_AISLES ||--|{ INGREDIENTS : "contains"  
    CATEGORIES ||--|{ DISHES : "classifies"  
    DISHES ||--|{ DISH\_INGREDIENT : "requires"  
    INGREDIENTS ||--|{ DISH\_INGREDIENT : "used\_in"  
    USERS ||--o{ ORDERS : "places"  
    ORDERS ||--|{ ORDER\_ITEMS : "contains"  
    DISHES ||--|{ ORDER\_ITEMS : "ordered"  
    ORDERS ||--o| REVIEWS : "has"  
    REVIEWS ||--|{ REVIEW\_ITEMS : "details"

## **2\. 数据库迁移定义 (Migrations)**

### **2.0 权限表 (Shield)**

* **Action**: 运行 php artisan vendor:publish \--provider="Spatie\\Permission\\PermissionServiceProvider" 发布 migration。  
* **Action**: 运行 php artisan shield:install 会自动配置 Shield 所需表结构。

### **2.1 基础表 (Base Tables)**

// create\_ingredients\_table.php  
Schema::create('ingredients', function (Blueprint $table) {  
    $table-\>id();  
    $table-\>foreignId('aisle\_id')-\>constrained('ingredient\_aisles'); // 购买分区  
    $table-\>string('name');  
    $table-\>string('base\_unit'); // Enum: 'g', 'ml', 'pc'  
    $table-\>timestamps();  
});

// create\_dishes\_table.php  
Schema::create('dishes', function (Blueprint $table) {  
    $table-\>id();  
    $table-\>foreignId('category\_id')-\>constrained();  
    $table-\>string('name');  
    $table-\>text('description')-\>nullable();  
      
    // 辅助选菜的冗余字段  
    $table-\>timestamp('last\_eaten\_at')-\>nullable();  
    $table-\>integer('frequency')-\>default(0);  
      
    $table-\>timestamps();  
});

### **2.2 核心中间表 (The Pivot)**

// create\_dish\_ingredient\_table.php  
Schema::create('dish\_ingredient', function (Blueprint $table) {  
    $table-\>id();  
    $table-\>foreignId('dish\_id')-\>constrained()-\>cascadeOnDelete();  
    $table-\>foreignId('ingredient\_id')-\>constrained()-\>cascadeOnDelete();  
      
    // 双轨制核心  
    $table-\>decimal('quantity', 10, 2)-\>default(0); // 轨道1：计算用 (如 15.00)  
    $table-\>string('unit');                         // 快照：存当时的 base\_unit  
    $table-\>string('remark')-\>nullable();           // 轨道2：展示用 (如 "2勺", "适量")  
      
    $table-\>timestamps();  
});

### **2.3 订单表 (Orders)**

// create\_orders\_table.php  
Schema::create('orders', function (Blueprint $table) {  
    $table-\>id();  
    $table-\>foreignId('user\_id')-\>constrained();  
      
    // 状态机  
    $table-\>string('status')-\>default('pending'); // pending, processing, served, completed  
      
    // 用餐计划 (核心约束：一单一时)  
    $table-\>date('meal\_date');  
    $table-\>string('meal\_period'); // Enum: lunch, dinner, snack  
      
    $table-\>text('customer\_note')-\>nullable(); // 对象说：我想吃早点  
    $table-\>text('chef\_note')-\>nullable();     // 你说：没肉了换成了豆腐  
      
    $table-\>timestamps();  
});

## **3\. Filament & Shield 权限规划**

引入 Shield 后，我们不再手写 Policy 文件，而是通过 **UI 配置** 或 **Seeder** 来管理角色。

### **3.1 角色定义 (Roles)**

需要在 Shield 界面（/admin/shield/roles）创建以下两个角色：

|

| **角色名称** | **标识 (Name)** | **描述** | **权限配置逻辑 (Shield UI)** |

| **大厨** | super\_admin | 系统拥有者 | **默认拥有所有权限**（Shield 默认行为）。无需额外配置。 |

| **点餐员** | partner | 顾客对象 | **按需勾选**： 1\. Order: view\_any, view, create 2\. Page: view\_order\_now (点餐台) 3\. Dish, Ingredient: **不勾选** (不可见) |

### **3.2 资源与权限映射表**

| **资源/页面** | **Shield 权限名称 (自动生成)** | **Partner 角色配置** | **备注** |

| **Page: OrderNow** | page\_OrderNow | ✅ 允许 | 核心点餐页面 |

| **Resource: Order** | view\_any\_order | ✅ 允许 | 仅能查看自己的订单 (需配合 Scope) |

| **Resource: Dish** | view\_any\_dish | ❌ 禁止 | 只有大厨能管理菜谱 |

| **Resource: Ingredient** | view\_any\_ingredient | ❌ 禁止 | 只有大厨能管理库存 |

| **Widget: Stats** | widget\_StatsOverview | ❌ 禁止 | 不给看复杂的报表 |

### **3.3 本地化配置 (Laravel Lang)**

为了让界面显示中文（包括 Shield 的权限名称）：

1. **Config**: 修改 config/app.php: 'locale' \=\> 'zh\_CN', 'timezone' \=\> 'Asia/Shanghai'.  
2. **Publish**: 运行 php artisan lang:publish。  
3. **Filament Panel**: 在 AdminPanelProvider.php 中无需额外配置，它会自动跟随 app.locale。  
4. **Shield 汉化**: Shield 的权限名称默认是英文（如 view\_any\_order），可以通过发布 Shield 的语言包进行翻译，或者直接看习惯就好。

## **4\. 核心业务逻辑代码**

### **4.1 单位枚举 (Unit Enum)**

namespace App\\Enums;

use Filament\\Support\\Contracts\\HasLabel;

enum IngredientUnit: string implements HasLabel  
{  
    case Gram \= 'g';  
    case Milliliter \= 'ml';  
    case Piece \= 'pc';

    public function getLabel(): ?string  
    {  
        return match($this) {  
            self::Gram \=\> '克 (g)',  
            self::Milliliter \=\> '毫升 (ml)',  
            self::Piece \=\> '个/只/瓣',  
        };  
    }  
}

### **4.2 采购清单聚合逻辑 (Shopping List Aggregation)**

实现位置建议：OrderResource \-\> BulkAction。

// In OrderResource / Actions / GenerateShoppingList  
public function handle(Collection $orders)  
{  
    // 1\. 初始化清单结构，按【购买分区】分组  
    // 结果结构: \['肉禽蛋' \=\> \[ '猪肉' \=\> \[...\], '鸡蛋' \=\> \[...\] \]\]  
    $shoppingList \= \[\];

    foreach ($orders as $order) {  
        foreach ($order-\>items as $item) {  
            // 加载菜谱对应的食材  
            $ingredients \= $item-\>dish-\>ingredients;   
              
            foreach ($ingredients as $ing) {  
                $aisle \= $ing-\>aisle-\>name; // 分区名  
                $key \= $ing-\>id; // 以食材ID为键，自动去重  
                  
                if (\!isset($shoppingList\[$aisle\]\[$key\])) {  
                    $shoppingList\[$aisle\]\[$key\] \= \[  
                        'name' \=\> $ing-\>name,  
                        'total\_qty' \=\> 0,  
                        'unit' \=\> $ing-\>pivot-\>unit,  
                        'remarks' \=\> \[\], // 收集所有备注  
                    \];  
                }  
                  
                // \=== 双轨逻辑 \===  
                  
                // 轨道 1: 绝对数值累加 (用于做大数计算)  
                // 比如: 150g \+ 200g \= 350g  
                $neededQty \= $ing-\>pivot-\>quantity \* $item-\>quantity;  
                $shoppingList\[$aisle\]\[$key\]\['total\_qty'\] \+= $neededQty;  
                  
                // 轨道 2: 备注收集 (用于辅助决策)  
                // 比如: \["少许", "2勺"\]  
                if (\!empty($ing-\>pivot-\>remark)) {  
                     // 简单的去重逻辑，防止出现 10个 "适量"  
                    if (\!in\_array($ing-\>pivot-\>remark, $shoppingList\[$aisle\]\[$key\]\['remarks'\])) {  
                        $shoppingList\[$aisle\]\[$key\]\['remarks'\]\[\] \= $ing-\>pivot-\>remark;  
                    }  
                }  
            }  
        }  
    }  
      
    // 2\. 将 $shoppingList 传递给 Blade View 渲染模态窗  
    // 前端展示逻辑：  
    // \- 如果 total\_qty \> 0，显示 "350g"  
    // \- 如果 remarks 有值，显示 "(备注: 2勺, 少许)"  
      
    return $shoppingList;  
}

### **4.3 定制点餐页 (OrderNow with Shield)**

确保 Shield 生成了 Page 权限，并在 OrderNow 页面类中添加权限检查。

// App/Filament/Pages/OrderNow.php

use BezhanSalleh\\FilamentShield\\Traits\\HasPageShield; // 引入 Shield Trait

class OrderNow extends Page  
{  
    use HasPageShield; // 自动启用权限控制

    protected static string $view \= 'filament.pages.order-now';  
      
    // ... Livewire 逻辑 (addToCart, openCartAction) ...  
}

## **5\. 实施 Checklist (更新版)**

1. **环境配置**:  
   * 配置 .env 数据库。  
   * 配置 config/app.php 为 zh\_CN。  
   * 运行 php artisan shield:install (选择 Yes 覆盖默认 Policy)。  
2. **数据结构**:  
   * 创建所有 Migration (ingredients, dishes, orders...)。  
   * 运行 php artisan migrate。  
3. **权限初始化 (Shield)**:  
   * 运行 php artisan shield:generate \--all (为所有 Resource/Page 生成权限)。  
   * 创建第一个超级用户: php artisan shield:super-admin (这就是**大厨**账号)。  
   * 登录后台，在 "Shield \> Roles" 中新建角色 partner。  
   * 在 partner 角色中，**仅勾选** OrderNow 页面权限和 Order 的基础查看权限。  
   * 创建对象账号，并分配 partner 角色。  
4. **业务开发**:  
   * 开发 IngredientResource & DishResource (大厨专用)。  
   * 开发 OrderNow 自定义页面 (核心难点)。  
5. **验证**:  
   * 使用 partner 账号登录，确保看不到 "Users", "Roles", "Dishes" 等菜单，只能看到 "点餐台" 和 "我的订单"。

