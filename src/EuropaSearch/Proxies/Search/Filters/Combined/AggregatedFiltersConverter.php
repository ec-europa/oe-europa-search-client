<?php

/**
 * @file
 * Contains EC\EuropaSearch\Proxies\Search\Filters\Combined\AggregatedFiltersConverter.
 */

namespace EC\EuropaSearch\Proxies\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Combined\CombinedQueryInterface;
use EC\EuropaWS\Messages\Components\ComponentInterface;

/**
 * Class AggregatedFiltersConverter.
 *
 * Converter for AggregatedFilters object.
 *
 * @package EC\EuropaSearch\Proxies\Search\Filters\Combined
 */
class AggregatedFiltersConverter implements CombinedQueryConverterInterface
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
    public function convertComponentWithChildren(CombinedQueryInterface $query, array $convertedComponents)
    {

        $label = $query->getAggregationLabel();
        $convertedComponent = [$label => []];

        // Add only the filled components.
        if (!empty($convertedComponents)) {
            foreach ($convertedComponents as $child) {
                $convertedComponent[$label][] = $child;
            }
        }

        return $convertedComponent;
    }
}