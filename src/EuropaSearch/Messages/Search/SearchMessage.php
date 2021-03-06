<?php

namespace OpenEuropa\EuropaSearch\Messages\Search;

use OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata;
use OpenEuropa\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery;
use OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class SearchMessage.
 *
 * It defines a search query that is sent to the Europa Search services.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Search
 */
class SearchMessage implements ValidatableMessageInterface
{
    const SEARCH_SORT_ASC = 'ASC';

    const SEARCH_SORT_DESC = 'DESC';

    /**
     * Keywords to search in full-text mode.
     *
     * @var string
     */
    protected $searchedText;

    /**
     * Languages used for filtering the search.
     *
     * @var array
     */
    protected $searchedLanguages;

    /**
     * Filter query applied to the search.
     *
     * @var BooleanQuery
     */
    protected $searchQuery;

    /**
     * Metadata on which results will be sorted.
     *
     * @var AbstractMetadata
     */
    protected $sortMetadata;

    /**
     * Sort direction.
     *
     * @var string
     */
    protected $sortDirection;

    /**
     * Number of search results to send per request.
     *
     * It will be used in the pagination mechanism.
     *
     * @var integer
     */
    protected $paginationSize;

    /**
     * The number of the page to retrieve with the request.
     *
     * It will be used in the pagination mechanism.
     *
     * @var integer
     */
    protected $paginationLocation;

    /**
     * Regex expression used to insert in the highlighting mechanism.
     *
     * The expression sets the HTML to use in the results text for
     * highlighting elements.
     *
     * @var string
     */
    protected $highlightRegex;

    /**
     * The maximum length of the text that can be highlighted.
     *
     * @var integer
     */
    protected $highLightLimit;

    /**
     * The session token required only for secured index.
     *
     * @var
     */
    protected $sessionToken;

    /**
     * Gets the keywords to use for the full-text search.
     *
     * @return string
     *   The keywords to use.
     */
    public function getSearchedText()
    {
        return $this->searchedText;
    }

    /**
     * Sets the keywords to use for the full-text search.
     *
     * @param string $searchedText
     *   The keywords to use.
     */
    public function setSearchedText($searchedText)
    {
        $this->searchedText = $searchedText;
    }

    /**
     * Gets the languages to take into account with the search.
     *
     * @return  array
     *   The list of languages code (ISO-639-1 format)
     */
    public function getSearchedLanguages()
    {
        return $this->searchedLanguages;
    }

    /**
     * Sets the languages to take into account with the search.
     *
     * @param array $searchedLanguages
     *   The list of languages code (ISO-639-1 format)
     */
    public function setSearchedLanguages(array $searchedLanguages)
    {
        $this->searchedLanguages = $searchedLanguages;
    }

    /**
     * Gets the filter query to apply on the search.
     *
     * @return \OpenEuropa\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery $searchQuery
     * The object defining the query.
     */
    public function getQuery()
    {
        return $this->searchQuery;
    }

    /**
     * Sets the filter query to apply on the search.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\Filters\Queries\BooleanQuery $searchQuery
     * The object defining the query.
     */
    public function setQuery(BooleanQuery $searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

    /**
     * Gets the metadata on which basing the sorting.
     *
     * @return \OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata
     *   The raw name on which basing the sorting.
     */
    public function getSortMetadata()
    {
        return $this->sortMetadata;
    }

    /**
     * Gets the sort direction to apply on search results.
     *
     * @return string
     *   The sort direction to use.
     */
    public function getSortDirection()
    {
        return $this->sortDirection;
    }

    /**
     * Sets the search sort criteria.
     *
     * If the search does not contain any sort criteria, search results will be
     * sorted by relevancy.
     *
     * @param \OpenEuropa\EuropaSearch\Messages\Components\DocumentMetadata\AbstractMetadata $sortMetadata
     *   The metadata object defining the raw name of the field on which
     *   basing the sorting.
     *   Only the name attribute of the object is mandatory.
     * @param string                                                                         $sortDirection
     *   The sort direction to use.
     */
    public function setSortCriteria(AbstractMetadata $sortMetadata, $sortDirection = self::SEARCH_SORT_ASC)
    {
        $this->sortMetadata = $sortMetadata;
        $this->sortDirection = $sortDirection;
    }

    /**
     * Gets the number of results to receive per request.
     *
     * @return int
     *   The number of results to receive per request.
     */
    public function getPaginationSize()
    {
        return $this->paginationSize;
    }

    /**
     * Gest the page number set for the search request.
     *
     * @return integer
     *   The page number of the result list to receive.
     */
    public function getPaginationLocation()
    {
        return $this->paginationLocation;
    }

    /**
     * Sets the pagination parameters to send.
     *
     * @param integer $paginationSize
     *   The number of results to receive per request.
     * @param integer $paginationLocation
     *   The page number of the result list to receive.
     */
    public function setPagination($paginationSize, $paginationLocation)
    {
        $this->paginationSize = $paginationSize;
        $this->paginationLocation = $paginationLocation;
    }

    /**
     * Gets regex expression used to insert in the highlighting mechanism.
     *
     * @return string
     *   The regex expression to use.
     */
    public function getHighlightRegex()
    {
        return $this->highlightRegex;
    }

    /**
     * Gets the maximum length of the text that can be highlighted.
     *
     * @return int
     *   The maximum length of the text that can be highlighted.
     */
    public function getHighLightLimit()
    {
        return $this->highLightLimit;
    }

    /**
     * Sets the text highlighting parameters.
     *
     * @param string $highlightRegex
     *   The regex expression to use.
     * @param int    $highLightLimit
     *   The maximum length of the text that can be highlighted.
     */
    public function setHighLightParameters($highlightRegex, $highLightLimit)
    {
        $this->highlightRegex = $highlightRegex;
        $this->highLightLimit = $highLightLimit;
    }

    /**
     * Gets the security session token.
     *
     * @return string
     *   The token.
     */
    public function getSessionToken()
    {
        return $this->sessionToken;
    }

    /**
     * Sets the security session token.
     *
     * @param string $sessionToken
     *   The token.
     */
    public function setSessionToken($sessionToken)
    {
        $this->sessionToken = $sessionToken;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return 'europaSearch.messageProxy.searching.search';
    }

    /**
     * {@inheritDoc}
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('searchedText', new Assert\NotBlank());
        $metadata->addPropertyConstraint('searchedLanguages', new Assert\All(['constraints' => [new Assert\Language()]]));
        $metadata->addPropertyConstraint('searchQuery', new Assert\Valid(['traverse' => true]));
        $metadata->addPropertyConstraint('highLightLimit', new Assert\Type('integer'));
        $metadata->addPropertyConstraint('paginationLocation', new Assert\Type('integer'));
        $metadata->addPropertyConstraint('paginationSize', new Assert\Type('integer'));
        $metadata->addPropertyConstraint('sessionToken', new Assert\Type('string'));
        $metadata->addPropertyConstraint('highlightRegex', new Assert\Type('string'));
        $metadata->addPropertyConstraints('searchedText', [
            new Assert\NotBlank(),
            new Assert\Type('string'),
        ]);
        $metadata->addPropertyConstraint('sortDirection', new Assert\Choice([self::SEARCH_SORT_DESC, self::SEARCH_SORT_ASC]));
    }

    /**
     * {@inheritDoc}
     */
    public function getComponents()
    {
        return [
            'sort_metadata' => $this->sortMetadata,
            'search_query' => $this->searchQuery,
        ];
    }
}
