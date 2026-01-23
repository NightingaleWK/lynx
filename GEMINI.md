<laravel-boost-guidelines>
=== .ai/system-design rules ===

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

* **Action**: 运行 php artisan vendor:publish \--provider="Spatie\Permission\PermissionServiceProvider" 发布 migration。  
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

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

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

use BezhanSalleh\FilamentShield\Traits\HasPageShield; // 引入 Shield Trait

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

=== .ai/system-description rules ===

# **Lynx (铃可)：为爱烹饪的家庭私厨数字化系统**

**项目代号**: Lynx (铃可)

**核心愿景**: 让柴米油盐拥有数字化仪式感，解决“今天吃什么”的世纪难题。

## **1\. 项目背景与初衷**

在快节奏的现代生活中，家庭烹饪往往面临两个极端的挑战：

1. **决策疲劳**：每天面对“今天吃什么”、“家里还有什么菜”、“去超市买什么”的重复决策。  
2. **数据断层**：即使记住了几个拿手菜，但具体的配方（多少克盐、多少勺酱油）往往依赖模糊的记忆，导致出品不稳定；同时，去超市采购时常因遗漏食材而往返奔波。

**Lynx (铃可)** 应运而生。它不是一个简单的记事本，而是一套基于 **Laravel 12** 和 **Filament V5** 打造的、工业级标准的家庭餐饮管理系统。它专为二人世界设计，将“大厨”的严谨后勤与“顾客”的沉浸式点餐体验完美融合，为琐碎的家务注入科技的秩序与生活的温情。

## **2\. 角色定位与互动模式**

系统设计了两个核心角色，分别对应家庭中的不同分工，创造出一种类似“私房菜馆”的情趣互动：

### **👨‍🍳 大厨 (The Chef) \- 系统管理员**

* **定位**：后勤总管、烹饪执行者、系统上帝。  
* **权限与职责**：  
  * **菜谱研发**：录入并维护独家菜谱，精确把控食材配比。  
  * **库存调度**：拥有“霸道”的改单权。如果当天食材不足，可以直接修改订单内容，并通知顾客。  
  * **采购管理**：一键生成采购清单，统筹未来几顿饭的物资需求。

### **👩‍💼 顾客 (The Partner) \- 首席体验官**

* **定位**：享受服务者、口味决策者、美食品鉴家。  
* **权限与体验**：  
  * **沉浸点餐**：享受类似外卖 App 的点餐体验，无需关心库存和复杂的后台逻辑。  
  * **用餐规划**：决定是“今晚吃”还是“明天中午带饭”。  
  * **餐后评价**：对菜品进行打分和点评，上传“买家秀”，激励大厨精进厨艺。

## **3\. 核心功能场景描述**

### **3.1 沉浸式点餐台 (The Order Experience)**

告别枯燥的表格录入，我们为“顾客”打造了一个**全屏沉浸式的点餐页面**。

* **视觉盛宴**：所有菜品以瀑布流卡片形式呈现，高清大图直击食欲。  
* **灵感标签**：通过“\#酸甜口”、“\#肉食动物”、“\#快手菜”等标签快速筛选，解决选择困难症。  
* **无缝加购**：点击加号，菜品飞入右下角的悬浮购物车。  
* **抽屉式结算**：点击购物车，侧滑抽屉展开。在这里，顾客可以备注“少辣”、“多葱”，并使用**智能时间胶囊**选择用餐时间（如“今晚”或“明午”），一键下单，流程如丝般顺滑。

### **3.2 双轨制食材管理 (Dual-Track Ingredient System)**

这是本系统最核心的“黑科技”，解决了现实世界的模糊性与计算机精确性之间的矛盾。

* **痛点**：做菜时我们说“少许”、“两勺”，但买菜时我们需要知道“500克”、“1瓶”。  
* **解决方案**：  
  * **计算轨**：系统底层记录精确数值（如 15ml, 20g），用于后台自动合并计算采购总量。  
  * **展示轨**：前端界面展示“人话”备注（如 2勺, 半个），符合烹饪时的直觉。  
  * **效果**：大厨在录入时虽然辛苦一点（需定义标准），但在生成采购清单时，系统能精准告诉你：“你需要买 500g 猪肉”，而在做菜时，菜谱上显示的又是亲切的“猪肉一块”。

### **3.3 智能采购助手 (Smart Shopping List)**

当确定了未来几天的菜单后，大厨无需手写购物清单。

* **一键合并**：选中“周六午餐”和“周六晚餐”的订单，系统自动合并同类项。  
* **动线分区**：生成的清单不是乱序的，而是根据超市的货架逻辑，自动分为“肉禽蛋区”、“果蔬区”、“调味干货区”。  
* **查漏补缺**：清单生成后，大厨可拿着手机核对冰箱库存，勾选已有的，只买缺少的，彻底告别“买多了”或“忘买了”。

### **3.4 闭环评价体系 (Feedback Loop)**

每一顿饭的结束，都是下一次优化的开始。

* 订单完成后，顾客可以对整顿饭进行 1-5 星打分，并撰写评语。  
* 支持上传餐桌照片，形成家庭的美食回忆录。  
* 系统会在菜谱中记录“上次食用时间”和“点单频率”，久而久之，系统将生成一份“家庭最爱菜品排行榜”。

## **4\. 为什么选择 Lynx (铃可)？**

与市面上的菜谱 App 或通用的待办事项软件相比，Lynx (铃可) 的优势在于**定制化**与**流程化**。

1. **私有化部署**：数据完全掌握在自己手中，记录的是属于这个小家庭独一无二的口味偏好。  
2. **生活流逻辑**：它不仅仅是存菜谱，而是管理“从点菜 \-\> 备货 \-\> 烹饪 \-\> 评价”的完整闭环。  
3. **极致体验**：前端采用单页应用（SPA）设计逻辑，操作流畅，不仅实用，更让做饭这件事变得“酷”起来。  
4. **权限分明**：利用 Filament Shield 实现严谨的权限隔离，大厨只能看后台，顾客只能看菜单，各司其职，界面清爽。

## **5\. 结语**

Lynx (铃可) 不仅仅是一行行代码构建的系统，它是技术宅对生活热爱的极致表达。它将琐碎的家务通过数字化手段重构，减少了沟通成本，增加了生活的确定性与仪式感。

在这个系统里，每一次点击“提交订单”，都是对美好生活的期待；每一次点击“接单”，都是对爱与责任的承诺。

=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context
This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5.2
- filament/filament (FILAMENT) - v5
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- livewire/livewire (LIVEWIRE) - v4
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- phpunit/phpunit (PHPUNIT) - v11

## Conventions
- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts
- Do not create verification scripts or tinker when tests cover that functionality and prove it works. Unit and feature tests are more important.

## Application Structure & Architecture
- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling
- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `vendor/bin/sail npm run build`, `vendor/bin/sail npm run dev`, or `vendor/bin/sail composer run dev`. Ask them.

## Replies
- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files
- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost
- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan
- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs
- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging
- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.

## Reading Browser Logs With the `browser-logs` Tool
- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)
- Boost comes with a powerful `search-docs` tool you should use before any other approaches when dealing with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The `search-docs` tool is perfect for all Laravel-related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax
- You can and should pass multiple queries at once. The most relevant results will be returned first.

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors
- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

### Type Declarations
- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<code-snippet name="Explicit Return Types and Method Params" lang="php">
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
</code-snippet>

## Comments
- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless there is something very complex going on.

## PHPDoc Blocks
- Add useful array shape type definitions for arrays when appropriate.

## Enums
- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

=== sail rules ===

## Laravel Sail

- This project runs inside Laravel Sail's Docker containers. You MUST execute all commands through Sail.
- Start services using `vendor/bin/sail up -d` and stop them with `vendor/bin/sail stop`.
- Open the application in the browser by running `vendor/bin/sail open`.
- Always prefix PHP, Artisan, Composer, and Node commands with `vendor/bin/sail`. Examples:
    - Run Artisan Commands: `vendor/bin/sail artisan migrate`
    - Install Composer packages: `vendor/bin/sail composer install`
    - Execute Node commands: `vendor/bin/sail npm run dev`
    - Execute PHP scripts: `vendor/bin/sail php [script]`
- View all available Sail commands by running `vendor/bin/sail` without arguments.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use `vendor/bin/sail artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `vendor/bin/sail artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database
- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation
- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `vendor/bin/sail artisan make:model`.

### APIs & Eloquent Resources
- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation
- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues
- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization
- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation
- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration
- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing
- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `vendor/bin/sail artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

### Vite Error
- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `vendor/bin/sail npm run build` or ask the user to run `vendor/bin/sail npm run dev` or `vendor/bin/sail composer run dev`.

=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version-specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure
- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database
- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models
- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== livewire/core rules ===

## Livewire

- Use the `search-docs` tool to find exact version-specific documentation for how to write Livewire and Livewire tests.
- Use the `vendor/bin/sail artisan make:livewire [Posts\CreatePost]` Artisan command to create new components.
- State should live on the server, with the UI reflecting it.
- All Livewire requests hit the Laravel backend; they're like regular HTTP requests. Always validate form data and run authorization checks in Livewire actions.

## Livewire Best Practices
- Livewire components require a single root element.
- Use `wire:loading` and `wire:dirty` for delightful loading states.
- Add `wire:key` in loops:

    ```blade
    @foreach ($items as $item)
        <div wire:key="item-{{ $item->id }}">
            {{ $item->name }}
        </div>
    @endforeach
    ```

- Prefer lifecycle hooks like `mount()`, `updatedFoo()` for initialization and reactive side effects:

<code-snippet name="Lifecycle Hook Examples" lang="php">
    public function mount(User $user) { $this->user = $user; }
    public function updatedSearch() { $this->resetPage(); }
</code-snippet>

## Testing Livewire

<code-snippet name="Example Livewire Component Test" lang="php">
    Livewire::test(Counter::class)
        ->assertSet('count', 0)
        ->call('increment')
        ->assertSet('count', 1)
        ->assertSee(1)
        ->assertStatus(200);
</code-snippet>

<code-snippet name="Testing Livewire Component Exists on Page" lang="php">
    $this->get('/posts/create')
    ->assertSeeLivewire(CreatePost::class);
</code-snippet>

=== pint/core rules ===

## Laravel Pint Code Formatter

- You must run `vendor/bin/sail bin pint --dirty` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/sail bin pint --test`, simply run `vendor/bin/sail bin pint` to fix any formatting issues.

=== phpunit/core rules ===

## PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `vendor/bin/sail artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should test all of the happy paths, failure paths, and weird paths.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

### Running Tests
- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `vendor/bin/sail artisan test --compact`.
- To run all tests in a file: `vendor/bin/sail artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `vendor/bin/sail artisan test --compact --filter=testName` (recommended after making a change to a related file).

=== filament/filament rules ===

## Filament

- Filament is used by this application. Follow existing conventions for how and where it's implemented.
- Filament is a Server-Driven UI (SDUI) framework for Laravel that lets you define user interfaces in PHP using structured configuration objects. Built on Livewire, Alpine.js, and Tailwind CSS.
- Use the `search-docs` tool for official documentation on Artisan commands, code examples, testing, relationships, and idiomatic practices.

### Artisan

- Use Filament-specific Artisan commands to create files. Find them with `list-artisan-commands` or `php artisan --help`.
- Inspect required options and always pass `--no-interaction`.

### Patterns

Use static `make()` methods to initialize components. Most configuration methods accept a `Closure` for dynamic values.

Use `Get $get` to read other form field values for conditional logic:

<code-snippet name="Conditional form field" lang="php">
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;

Select::make('type')
    ->options(CompanyType::class)
    ->required()
    ->live(),

TextInput::make('company_name')
    ->required()
    ->visible(fn (Get $get): bool => $get('type') === 'business'),
</code-snippet>

Use `state()` with a `Closure` to compute derived column values:

<code-snippet name="Computed table column" lang="php">
use Filament\Tables\Columns\TextColumn;

TextColumn::make('full_name')
    ->state(fn (User $record): string => "{$record->first_name} {$record->last_name}"),
</code-snippet>

Actions encapsulate a button with optional modal form and logic:

<code-snippet name="Action with modal form" lang="php">
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;

Action::make('updateEmail')
    ->form([
        TextInput::make('email')->email()->required(),
    ])
    ->action(fn (array $data, User $record): void => $record->update($data)),
</code-snippet>

### Testing

Authenticate before testing panel functionality. Filament uses Livewire, so use `livewire()` or `Livewire::test()`:

<code-snippet name="Filament Table Test" lang="php">
    livewire(ListUsers::class)
        ->assertCanSeeTableRecords($users)
        ->searchTable($users->first()->name)
        ->assertCanSeeTableRecords($users->take(1))
        ->assertCanNotSeeTableRecords($users->skip(1));
</code-snippet>

<code-snippet name="Filament Create Resource Test" lang="php">
    livewire(CreateUser::class)
        ->fillForm([
            'name' => 'Test',
            'email' => 'test@example.com',
        ])
        ->call('create')
        ->assertNotified()
        ->assertRedirect();

    assertDatabaseHas(User::class, [
        'name' => 'Test',
        'email' => 'test@example.com',
    ]);
</code-snippet>

<code-snippet name="Testing Validation" lang="php">
    livewire(CreateUser::class)
        ->fillForm([
            'name' => null,
            'email' => 'invalid-email',
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'email',
        ])
        ->assertNotNotified();
</code-snippet>

<code-snippet name="Calling Actions" lang="php">
    use Filament\Actions\DeleteAction;
    use Filament\Actions\Testing\TestAction;

    livewire(EditUser::class, ['record' => $user->id])
        ->callAction(DeleteAction::class)
        ->assertNotified()
        ->assertRedirect();

    livewire(ListUsers::class)
        ->callAction(TestAction::make('promote')->table($user), [
            'role' => 'admin',
        ])
        ->assertNotified();
</code-snippet>

### Common Mistakes

**Commonly Incorrect Namespaces:**
- Form fields (TextInput, Select, etc.): `Filament\Forms\Components\`
- Infolist entries (for read-only views) (TextEntry, IconEntry, etc.): `Filament\Forms\Components\`
- Layout components (Grid, Section, Fieldset, Tabs, Wizard, etc.): `Filament\Schemas\Components\`
- Schema utilities (Get, Set, etc.): `Filament\Schemas\Components\Utilities\`
- Actions: `Filament\Actions\` (no `Filament\Tables\Actions\` etc.)
- Icons: `Filament\Support\Icons\Heroicon` enum (e.g., `Heroicon::PencilSquare`)

**Recent breaking changes to Filament:**
- File visibility is `private` by default. Use `->visibility('public')` for public access.
- `Grid`, `Section`, and `Fieldset` no longer span all columns by default.
</laravel-boost-guidelines>
