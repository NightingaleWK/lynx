<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wishing>
 */
class WishingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([
            \App\Models\Wishing::STATUS_PENDING,
            \App\Models\Wishing::STATUS_ACCEPTED,
            \App\Models\Wishing::STATUS_FULFILLED,
            \App\Models\Wishing::STATUS_REJECTED,
        ]);

        // 获取随机用户ID，如果用户表为空则创建用户
        $userId = \App\Models\User::inRandomOrder()->first()?->id ?? \App\Models\User::factory()->create()->id;

        $wishingContents = [
            '希望今天能吃到红烧肉',
            '想要一份糖醋排骨',
            '希望能做一道清蒸鲈鱼',
            '想要一份宫保鸡丁',
            '希望能吃到麻婆豆腐',
            '想要一份回锅肉',
            '希望能做一道鱼香肉丝',
            '想要一份白切鸡',
            '希望能吃到蒸蛋羹',
            '想要一份蒜蓉西兰花',
            '希望能做一道番茄炒蛋',
            '想要一份青椒土豆丝',
            '希望能吃到凉拌黄瓜',
            '想要一份酸辣汤',
            '希望能做一道红烧茄子',
        ];

        $baseData = [
            'user_id' => $userId,
            'content' => $this->faker->randomElement($wishingContents),
            'status' => $status,
        ];

        // 根据状态添加相应的时间戳
        switch ($status) {
            case \App\Models\Wishing::STATUS_ACCEPTED:
                $baseData['response'] = $this->faker->randomElement([
                    '好的，我来安排！',
                    '这个愿望可以实现，等我准备一下',
                    '没问题，今天就能做',
                    '好的，我记下了',
                    '这个想法不错，我来试试',
                ]);
                $baseData['accepted_at'] = $this->faker->dateTimeBetween('-7 days', 'now');
                break;

            case \App\Models\Wishing::STATUS_FULFILLED:
                $baseData['response'] = $this->faker->randomElement([
                    '已经做好了，快来品尝吧！',
                    '愿望实现了，希望你喜欢',
                    '完成了，味道还不错',
                    '做好了，快来试试',
                    '愿望达成！',
                ]);
                $baseData['accepted_at'] = $this->faker->dateTimeBetween('-10 days', '-3 days');
                $baseData['fulfilled_at'] = $this->faker->dateTimeBetween($baseData['accepted_at'], 'now');
                break;

            case \App\Models\Wishing::STATUS_REJECTED:
                $baseData['response'] = $this->faker->randomElement([
                    '抱歉，这个暂时做不了',
                    '材料不够，下次再做吧',
                    '这个有点难度，我再研究研究',
                    '暂时无法实现，不好意思',
                    '这个愿望有点难，让我想想',
                ]);
                $baseData['rejected_at'] = $this->faker->dateTimeBetween('-5 days', 'now');
                break;

            default: // STATUS_PENDING
                $baseData['response'] = null;
                break;
        }

        return $baseData;
    }

    /**
     * 待回应状态
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => \App\Models\Wishing::STATUS_PENDING,
            'response' => null,
            'accepted_at' => null,
            'fulfilled_at' => null,
            'rejected_at' => null,
        ]);
    }

    /**
     * 已受理状态
     */
    public function accepted(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => \App\Models\Wishing::STATUS_ACCEPTED,
            'response' => $this->faker->randomElement([
                '好的，我来安排！',
                '这个愿望可以实现，等我准备一下',
                '没问题，今天就能做',
                '好的，我记下了',
                '这个想法不错，我来试试',
            ]),
            'accepted_at' => $this->faker->dateTimeBetween('-7 days', 'now'),
            'fulfilled_at' => null,
            'rejected_at' => null,
        ]);
    }

    /**
     * 已实现状态
     */
    public function fulfilled(): static
    {
        $acceptedAt = $this->faker->dateTimeBetween('-10 days', '-3 days');

        return $this->state(fn(array $attributes) => [
            'status' => \App\Models\Wishing::STATUS_FULFILLED,
            'response' => $this->faker->randomElement([
                '已经做好了，快来品尝吧！',
                '愿望实现了，希望你喜欢',
                '完成了，味道还不错',
                '做好了，快来试试',
                '愿望达成！',
            ]),
            'accepted_at' => $acceptedAt,
            'fulfilled_at' => $this->faker->dateTimeBetween($acceptedAt, 'now'),
            'rejected_at' => null,
        ]);
    }

    /**
     * 已抛弃状态
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => \App\Models\Wishing::STATUS_REJECTED,
            'response' => $this->faker->randomElement([
                '抱歉，这个暂时做不了',
                '材料不够，下次再做吧',
                '这个有点难度，我再研究研究',
                '暂时无法实现，不好意思',
                '这个愿望有点难，让我想想',
            ]),
            'accepted_at' => null,
            'fulfilled_at' => null,
            'rejected_at' => $this->faker->dateTimeBetween('-5 days', 'now'),
        ]);
    }
}
