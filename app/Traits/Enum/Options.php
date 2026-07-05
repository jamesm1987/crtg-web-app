<?php

namespace App\Traits\Enum;

trait Options {
    
    /**
     * Get array of enum options
     * @return array<string, mixed>
     */
    public static function options(): array {
        return array_column(self::cases(), 'value', 'name');
    }

    public static function fromName(string $name): self
    {
        return constant(self::class . "::{$name}");
    }

    public static function labelOptions($invert = false): array
    {
        $options = [];
        if($invert === false) {
            foreach (self::cases() as $case) {
                $options[$case->value] = method_exists($case, 'label')
                    ? $case->label()
                    : $case->name;
            }
        } else {
            foreach (self::cases() as $case) {
                $options[
                    method_exists($case, 'label')
                        ? $case->label()
                        : $case->name
                ] = $case->name;
            }
        }

        return $options;
    }
    
}