<?php

namespace OpenEuropa\EuropaSearch\Applications;

use OpenEuropa\EuropaSearch\EuropaSearchConfig;
use OpenEuropa\EuropaSearch\Exceptions\ValidationException;
use OpenEuropa\EuropaSearch\Messages\ValidatableMessageInterface;
use OpenEuropa\EuropaSearch\Proxies\ProxyControllerInterface;
use OpenEuropa\EuropaSearch\Services\LogsManager;
use OpenEuropa\EuropaSearch\Transporters\TransporterInterface;
use Symfony\Component\Validator\ValidatorBuilder;

/**
 * Class Application.
 *
 * Default implementation of the ApplicationInterface.
 *
 * @package OpenEuropa\EuropaSearch\Applications
 */
class Application implements ApplicationInterface
{
    /**
     * The validator to use with messages
     *
     * @var \Symfony\Component\Validator\Validator\ValidatorInterface
     */
    protected $validator;

    /**
     * The proxy to use for convert the sent message.
     *
     * @var \OpenEuropa\EuropaSearch\Proxies\ProxyControllerInterface
     */
    protected $proxy;

    /**
     * The transporter to eventually send the request with the message.
     *
     * @var \OpenEuropa\EuropaSearch\Transporters\TransporterInterface
     */
    protected $transporter;

    /**
     * The web service configuration.
     *
     * @var \OpenEuropa\EuropaSearch\EuropaSearchConfig
     */
    protected $webServiceSettings;

    /**
     * The logs manager that will manage logs record.
     *
     * @var \OpenEuropa\EuropaSearch\Services\LogsManager
     */
    protected $logsManager;

    /**
     * DefaultClient constructor.
     *
     * @param \Symfony\Component\Validator\ValidatorBuilder              $validator
     * @param \OpenEuropa\EuropaSearch\Proxies\ProxyControllerInterface  $proxy
     * @param \OpenEuropa\EuropaSearch\Transporters\TransporterInterface $transporter
     * @param \OpenEuropa\EuropaSearch\Services\LogsManager              $logsManager
     */
    public function __construct(ValidatorBuilder $validator, ProxyControllerInterface $proxy, TransporterInterface $transporter, LogsManager $logsManager)
    {
        $this->validator = $validator->getValidator();
        $this->proxy = $proxy;
        $this->transporter = $transporter;
        $this->logsManager = $logsManager;
    }

    /**
     * {@inheritDoc}
     */
    public function setApplicationConfiguration(EuropaSearchConfig $configuration)
    {
        $this->webServiceSettings = $configuration;
        $this->proxy->initProxy($configuration);
        $this->transporter->initTransporter($configuration);
    }

    /**
     * {@inheritDoc}
     */
    public function getApplicationConfiguration()
    {
        return $this->webServiceSettings;
    }

    /**
     * {@inheritDoc}
     */
    public function sendMessage(ValidatableMessageInterface $message)
    {
        $this->validateMessage($message);

        return $this->proxy->sendRequest($message, $this->transporter);
    }

    /**
     * {@inheritDoc}
     */
    public function validateMessage(ValidatableMessageInterface $message)
    {
        $violations = $this->validator->validate($message);
        if (!empty($violations) && ($violations->count() != 0)) {
            $errorMessages = [];
            foreach ($violations as $violation) {
                $errorMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }
            $validException =  new ValidationException('The message submitted is invalid');
            $validException->setValidationErrors($errorMessages);

            throw $validException;
        }
    }
}
