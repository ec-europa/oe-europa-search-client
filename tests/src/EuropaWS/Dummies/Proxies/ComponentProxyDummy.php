<?php
/**
 * @file
 * Contains EC\EuropaWS\Tests\Dummies\Proxies\ComponentProxyDummy.
 */

namespace EC\EuropaWS\Tests\Dummies\Proxies;

use EC\EuropaWS\Messages\Components\ComponentInterface;
use EC\EuropaWS\Proxies\ComponentProxyInterface;

/**
 * Class ComponentProxyDummy
 *
 * AbstractProxy extension for unit tests around the .
 *
 * @package EC\EuropaWS\Tests\Dummies\Proxies
 */
class ComponentProxyDummy implements ComponentProxyInterface
{

    /**
     * {@inheritdoc}
     *
     * @internal Not implemented; used only for some unit tests.
     */
    public function convertComponent(ComponentInterface $component)
    {
        throw new \Exception('Not implemented, it is just for testing the class itself.');
    }
}