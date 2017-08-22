<?php

/**
 * @file
 * Contains EC\EuropaSearch\Messages\DocumentMetadata\FullTextMetadata.
 */

namespace EC\EuropaSearch\Messages\DocumentMetadata;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Object for a full-text searchable metadata of an indexed document.
 *
 * @package EC\EuropaSearch\Common\DocumentMetadata
 */
class FullTextMetadata extends AbstractMetadata implements IndexableMetadataInterface
{

    /**
     * StringMetadata constructor.
     *
     * @param string $name
     *   The raw name of the metadata.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Loads constraints declarations for the validator process.
     *
     * @param ClassMetadata $metadata
     */
    public static function getConstraints(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('values', new Assert\All(['constraints' => [new Assert\Type('string')]]));
    }

    /**
     * {@inheritDoc}
     */
    public function getEuropaSearchName()
    {
        return 'esIN_'.$this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'string';
    }
}