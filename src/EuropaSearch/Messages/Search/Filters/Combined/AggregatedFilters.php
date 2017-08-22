<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Combined\AggregatedFilters.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Combined;

use EC\EuropaSearch\Messages\Search\Filters\Simple\AbstractSimple;
use EC\EuropaWS\Proxies\BasicProxyController;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class AggregatedFilters.
 *
 * Object representing a aggregation of filters.
 *
 * It can be made of objects of:
 * - The EC\EuropaSearch\Messages\Search\Filters\Simple package;
 * - The EC\EuropaSearch\Messages\Search\Filters\Combined\BooleanQuery type;
 * - The EC\EuropaSearch\Messages\Search\Filters\Combined\BoostingQuery type;
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Combined
 */
class AggregatedFilters implements CombinedQueryInterface
{

    /**
     * Label to identify the aggregation.
     *
     * @var string
     */
    private $aggregationLabel;

    /**
     * The list of aggregated filters.
     *
     * @var array
     */
    private $filterList;

    /**
     * AggregatedFilters constructor.
     *
     * @param string $aggregationLabel
     *   The label to identify the aggregation.
     */
    public function __construct($aggregationLabel)
    {
        $this->aggregationLabel = $aggregationLabel;
        $this->filterList = [];
    }

    /**
     * Gets the label to identify the aggregation..
     *
     * @return string
     *   The label to identify the aggregation.
     */
    public function getAggregationLabel()
    {
        return $this->aggregationLabel;
    }

    /**
     * Gets the list of aggregate filters.
     *
     * @return array
     *   Array made of AbstractSimple filters or CombinedQueryInterface
     *   implementation (BooleanQuery or BoostingQuery objects).
     */
    public function getFilterList()
    {
        return $this->filterList;
    }

    /**
     * Adds an AbstractSimple filter to the aggregated filters;
     *
     * @param AbstractSimple $filter
     *   The AbstractSimple $filter to add
     */
    public function addSimpleFilter(AbstractSimple $filter)
    {
        $this->filterList[] = $filter;
    }

    /**
     * Adds an CombinedQueryInterface query to the aggregated filters;
     *
     * @param CombinedQueryInterface $query
     *   The CombinedQueryInterface query to add
     */
    public function addCombinedQuery(CombinedQueryInterface $query)
    {
        $this->filterList[] = $query;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return BasicProxyController::COMPONENT_ID_PREFIX.'searching.filters.combined.aggregate';
    }

    /**
     * {@inheritDoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('filterList', new Assert\Valid(['traverse' => true]));
    }

    /**
     * {@inheritDoc}
     */
    public function getChildComponents()
    {
        return $this->filterList;
    }
}