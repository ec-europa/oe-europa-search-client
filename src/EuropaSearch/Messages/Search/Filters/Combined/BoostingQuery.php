<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\Search\Filters\Combined\BoostingQuery.
 */

namespace EC\EuropaSearch\Messages\Search\Filters\Combined;

use EC\EuropaSearch\Messages\DocumentMetadata\AbstractNumericMetadata;
use EC\EuropaSearch\Messages\DocumentMetadata\StringMetadata;
use EC\EuropaSearch\Messages\Search\Filters\BoostableFilter;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Term;
use EC\EuropaSearch\Messages\Search\Filters\Simple\Terms;
use EC\EuropaWS\Proxies\BasicProxyController;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class BoostingQuery.
 *
 * Represents a Boolean query compound of Europa Search; I.E:
 * "Allows you to bias some of the fields, positively or negatively.
 * Unlike the boolean query, even if the field does not match it will still
 * be returned.
 * This is just for the ranking of the results."
 *
 * It only supports "Value" and "Values" objects that involve
 * "StringMetadata" or "NumericMetadata".
 *
 * @package EC\EuropaSearch\Messages\Search\Filters\Combined
 */
class BoostingQuery extends BoostableFilter implements CombinedQueryInterface
{

    /**
     * List of filters contributing to bias fields positively.
     *
     * @var array
     */
    private $positiveFilters;

    /**
     * List of filters contributing to bias fields negatively.
     *
     * @var array
     */
    private $negativeFilters;

    /**
     * BoostingQuery constructor.
     */
    public function __construct()
    {
        $this->positiveFilters = new AggregatedFilters('positive');
        $this->negativeFilters = new AggregatedFilters('negative');
    }

    /**
     * Gets the list of filters contributing to bias fields positively.
     *
     * @return array
     *   The list of filters.
     */
    public function getPositiveFilters()
    {
        return $this->positiveFilters;
    }

    /**
     * Gets the list of filters contributing to bias fields negatively.
     *
     * @return array
     *   The list of filters.
     */
    public function getNegativeFilters()
    {
        return $this->negativeFilters;
    }

    /**
     * Add a Value in the list of positive.
     *
     * @param Term $filter
     *   The filter to add.
     */
    public function addValueInPositiveFilters(Term $filter)
    {
        $this->positiveFilters->addSimpleFilter($filter);
    }

    /**
     *Add a Values in the list of positive.
     *
     * @param Terms $filter
     *   The filter to add.
     */
    public function addValuesInPositiveFilters(Terms $filter)
    {
        $this->positiveFilters->addSimpleFilter($filter);
    }

    /**
     * Add a Value in the list of negative.
     *
     * @param Term $filter
     *   The filter to add.
     */
    public function addValueInNegativeFilters(Term $filter)
    {
        $this->negativeFilters->addSimpleFilter($filter);
    }

    /**
     * Add a Values in the list of negative.
     *
     * @param Terms $filter
     *   The filter to add.
     */
    public function addValuesInNegativeFilters(Terms $filter)
    {
        $this->negativeFilters->addSimpleFilter($filter);
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return BasicProxyController::COMPONENT_ID_PREFIX.'searching.filters.combined.boostingQuery';
    }

    /**
     * {@inheritDoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {

        $metadata->addPropertyConstraint('negativeFilters', new Assert\Valid());
        $metadata->addPropertyConstraint('positiveFilters', new Assert\Valid());

        $metadata->addConstraint(new Assert\Callback('validate'));
    }

    /**
     * Special validator callback for BoostingQuery.
     *
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {

        if (empty($this->getPositiveFilters()) && empty($this->getNegativeFilters())) {
            $context->buildViolation('At least one of the filter list must filled.')
                ->atPath('positiveFilters')
                ->addViolation();
        }
        // Test negative list
        $filterList = $this->negativeFilters->getFilterList();
        $this->validateFilter($filterList, 'negativeFilters', $context, $payload);

        // Test positive list
        $filterList = $this->positiveFilters->getFilterList();
        $this->validateFilter($filterList, 'positiveFilters', $context, $payload);
    }

    /**
     * Validates a filter list defined in the current BoostingQuery.
     *
     * @param array                     $filterList
     *   The filter list to validate.
     * @param string                    $checkProperty
     *   The checked property path
     * @param ExecutionContextInterface $context
     * @param mixed                     $payload
     */
    public function validateFilter(array $filterList, $checkProperty, ExecutionContextInterface $context, $payload)
    {
        foreach ($filterList as $key => $filter) {
            $filterMetadata = $filter->getImpliedMetadata();

            if ((!$filterMetadata instanceof StringMetadata) && (!$filterMetadata instanceof AbstractNumericMetadata)) {
                $context->buildViolation('The Metadata implied in the filter is not supported. Only text and numerical ones are valid.')
                    ->atPath($checkProperty.'['.$key.']')
                    ->addViolation();
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getChildComponents()
    {
        return [
            $this->positiveFilters,
            $this->negativeFilters,
        ];
    }
}