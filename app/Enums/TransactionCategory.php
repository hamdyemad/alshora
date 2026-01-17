<?php

namespace App\Enums;

enum TransactionCategory: string
{
    case CONSULTATION = 'consultation';
    case SUBSCRIPTION = 'subscription';
    case OFFICE_RENT = 'office_rent';
    case UTILITIES = 'utilities';
    case MARKETING = 'marketing';
    case SALARY = 'salary';
    case OTHER = 'other';

    /**
     * Get all category values
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get all categories with translations
     */
    public static function toArray(): array
    {
        return [
            self::CONSULTATION->value => __('accounting.consultation'),
            self::SUBSCRIPTION->value => __('accounting.subscription'),
            self::OFFICE_RENT->value => __('accounting.office_rent'),
            self::UTILITIES->value => __('accounting.utilities'),
            self::MARKETING->value => __('accounting.marketing'),
            self::SALARY->value => __('accounting.salary'),
            self::OTHER->value => __('accounting.other'),
        ];
    }

    /**
     * Get translated label
     */
    public function label(): string
    {
        return match($this) {
            self::CONSULTATION => __('accounting.consultation'),
            self::SUBSCRIPTION => __('accounting.subscription'),
            self::OFFICE_RENT => __('accounting.office_rent'),
            self::UTILITIES => __('accounting.utilities'),
            self::MARKETING => __('accounting.marketing'),
            self::SALARY => __('accounting.salary'),
            self::OTHER => __('accounting.other'),
        };
    }
}
