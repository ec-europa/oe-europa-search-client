<?php

namespace OpenEuropa\EuropaSearch\Messages\Index;

/**
 * Class DeleteIndexItemMessage.
 *
 * It defines an item to delete in the Europa Search index.
 *
 * @package OpenEuropa\EuropaSearch\Messages\Index
 */
class DeleteIndexItemMessage extends AbstractIndexingMessage
{
    /**
     * {@inheritdoc}
     */
    public function getConverterIdentifier()
    {
        return self::CONVERTER_NAME_PREFIX.'delete.item';
    }
}
