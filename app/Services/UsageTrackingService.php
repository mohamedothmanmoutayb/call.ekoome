<?php

namespace App\Services;

use App\Models\UsageTracking;
use App\Models\User;
use App\Models\Subscription;
use Carbon\Carbon;

class UsageTrackingService
{
    public static function incrementUsage(User $user, $metric, $amount = 1)
    {
        $subscription = $user->activeSubscription();
        
        if (!$subscription) {
            return false;
        }

        $usage = UsageTracking::firstOrCreate(
            [
                'user_id' => $user->id,
                'subscription_id' => $subscription->id,
                'metric' => $metric,
                'period_start' => $subscription->start_date,
                'period_end' => $subscription->end_date
            ],
            [
                'current_usage' => 0,
                'limit' => self::getPlanLimit($subscription, $metric)
            ]
        );

        $usage->increment('current_usage', $amount);

        return $usage;
    }

    public static function getPlanLimit(Subscription $subscription, $metric)
    {

        $metricMap = [
            'users' => 'users',
            'sales' => 'max_monthly_sales',
            'products' => 'products',
            'stores' => 'stores',
            'agents' => 'agents',
            'shipping_companies' => 'shipping_companies',
            'deliverymen' => 'deliverymen',
            'sales_channels' => 'sales_channels'
        ];

        if (!isset($metricMap[$metric])) {
            return 0;
        }

        $field = $metricMap[$metric];
        

        $limit = $subscription->plan->$field;
        if ($limit === 'Unlimited' || $limit === 'unlimited') {
            return PHP_INT_MAX; 
        }

        return (int) $limit;
    }

    public static function getClientUsage(User $user, $metric = null)
    {
        $subscription = $user->activeSubscription();
        if (!$subscription) {
            return collect();
        }

        $query = UsageTracking::where('user_id', $user->id)
            ->where('subscription_id', $subscription->id)
            ->where('period_start', '<=', now())
            ->where('period_end', '>=', now());

        if ($metric) {
            $query->where('metric', $metric);
        }

        return $query->get();
    }

    public static function checkLimit(User $user, $metric)
    {
        $usage = self::getClientUsage($user, $metric)->first();
        
        if (!$usage) {
            return [
                'has_limit' => false,
                'is_over_limit' => false,
                'is_near_limit' => false
            ];
        }

        return [
            'has_limit' => $usage->limit > 0,
            'is_over_limit' => $usage->isOverLimit(),
            'is_near_limit' => $usage->isNearLimit(),
            'current_usage' => $usage->current_usage,
            'limit' => $usage->limit,
            'remaining' => $usage->getRemaining(),
            'percentage' => $usage->getUsagePercentage()
        ];
    }

    private static function parseLimitValue($value)
    {
        if ($value === 'Unlimited' || $value === 'unlimited' || $value === null) {
            return 1000000; 
        }

        if (is_numeric($value)) {
            return (int) $value;
        }

        return (int) $value;
    }

    public static function isUnlimited($limit)
    {
        return $limit >= 1000000;
    }
}