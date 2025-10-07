<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\UsageTracking;
use App\Models\Plan;
use Carbon\Carbon;

class SubscriptionUsageService
{
    public static function initializeUsageTracking(Subscription $subscription)
    {
        $plan = Plan::where('external_plan_id', $subscription->external_plan_id)->first();
        
        if (!$plan) {
            \Log::error("Plan not found for subscription: " . $subscription->id);
            return false;
        }

        $metrics = [
            'users' => 'users',
            'monthly_sales' => 'max_monthly_sales',
            'products' => 'products',
            'stores' => 'stores',
            'agents' => 'agents',
            'shipping_companies' => 'shipping_companies',
            'deliverymen' => 'deliverymen',
            'sales_channels' => 'sales_channels'
        ];

        foreach ($metrics as $metric => $planField) {
            self::createUsageRecord($subscription, $metric, $plan->$planField);
        }

        \Log::info("Initialized usage tracking for subscription: " . $subscription->id);
        return true;
    }

    private static function createUsageRecord(Subscription $subscription, $metric, $limitValue)
    {
        $limit = self::parseLimitValue($limitValue);

        $existingRecord = UsageTracking::where('user_id', $subscription->user_id)
            ->where('subscription_id', $subscription->id)
            ->where('metric', $metric)
            ->first();

        if ($existingRecord) {
            $existingRecord->update([
                'limit' => $limit,
                'period_start' => $subscription->start_date,
                'period_end' => $subscription->end_date
            ]);
            return $existingRecord;
        }

        return UsageTracking::create([
            'user_id' => $subscription->user_id,
            'subscription_id' => $subscription->id,
            'metric' => $metric,
            'current_usage' => 0,
            'limit' => $limit,
            'period_start' => $subscription->start_date,
            'period_end' => $subscription->end_date
        ]);
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

    public static function updateUsageTrackingForSubscription(Subscription $subscription)
    {
        $plan = Plan::find($subscription->external_plan_id);
        
        if (!$plan) {
            return false;
        }

        $metrics = [
            'users' => 'users',
            'monthly_sales' => 'max_monthly_sales',
            'products' => 'products',
            'stores' => 'stores',
            'agents' => 'agents',
            'shipping_companies' => 'shipping_companies',
            'deliverymen' => 'deliverymen',
            'sales_channels' => 'sales_channels'
        ];

        foreach ($metrics as $metric => $planField) {
            $limit = self::parseLimitValue($plan->$planField);
            
            UsageTracking::updateOrCreate(
                [
                    'user_id' => $subscription->user_id,
                    'subscription_id' => $subscription->id,
                    'metric' => $metric
                ],
                [
                    'limit' => $limit,
                    'period_start' => $subscription->start_date,
                    'period_end' => $subscription->end_date
                ]
            );
        }

        return true;
    }

    public static function initializeAllSubscriptions()
    {
        $subscriptions = Subscription::where('is_active', true)->get();
        
        $count = 0;
        foreach ($subscriptions as $subscription) {
            if (self::initializeUsageTracking($subscription)) {
                $count++;
            }
        }

        return $count;
    }
}