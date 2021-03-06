<?php

namespace OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Queries;

use OpenEuropa\EuropaSearch\Messages\Components\NestedComponentInterface;
use OpenEuropa\EuropaSearch\Messages\Components\ComponentInterface;

/**
 * Class FilterQueryComponentConverter.
 *
 * Converter for AggregatedFilters object.
 *
 * @package OpenEuropa\EuropaSearch\Proxies\Converters\Components\Filters\Queries
 */
class FilterQueryComponentConverter implements FilterQueryConverterInterface
{
    /**
     * {@inheritDoc}
     */
    public function convertComponent(ComponentInterface $component)
    {
        return $this->convertComponentWithChildren($component, []);
    }

    /**
     * {@inheritDoc}
     */
    public function convertComponentWithChildren(NestedComponentInterface $query, array $convertedComponents)
    {
        if (empty($convertedComponents)) {
            return;
        }

        $label = $query->getAggregationLabel();
        $convertedComponent = [$label => []];

        // Add only the filled components.
        foreach ($convertedComponents as $child) {
            $convertedComponent[$label][] = $child;
        }

        return $convertedComponent;
    }
}
