<?php

namespace App\Services\Product;

class PropertiesFormatter
{
    public function get($product)
    {
        if (!$product->properties) {
            return false;
        }

        $properties = $this->getProperties($product->properties);

        return $properties;
    }

    public function getProperties($properties)
    {
        if (!$properties) {
            return false;
        }

        foreach ($properties as $property) {
            $displayValue = false;

            if (is_array($property->value) && (!count($property->value) || empty($property->value[0]))) {
                continue;
            }

            if ($property->attribute->inputtype == 'select' && !$property->value) {
                continue;
            }

            if ($property->attribute->inputtype == 'boolean') {
                $displayValue = ($property->value == 1 ? '&#x2713; '.'Yes' : '&#x2717; '.'No');
            } elseif (is_array($property->value)) {
                if ($property->value) {
                    $displayValue = implode(', ', $property->value);
                }
            } else {
                if ($property->value) {
                    $displayValue = $property->value;
                }
            }

            if ($displayValue && $property->attribute->unit) {
                $displayValue = $displayValue.' '.$property->attribute->unit;
            }

            $property->displayValue = $displayValue;
        }

        return $properties;
    }

}
