<?php
/**
 * Zimbra SOAP API calls for the regular user account.
 * Mostly a copy of the Admin class.
 *
 * @author Reinier Pelayo
 */
namespace Zimbra\ZCS;

abstract class User
{

    /**
     * The soapclient
     * @var SoapClient
     */
    protected $soapClient;

    /**
     * Constructor
     * @param \Zimbra\ZCS\SoapClient $client
     */
    public function __construct(\Zimbra\ZCS\SoapClient $client)
    {
        $this->setSoapClient($client);
    }

    /**
     * The setter for the Soap Client class
     * @param \Zimbra\ZCS\SoapClient $soapClient
     * @return \Zimbra\ZCS\Admin
     */
    public function setSoapClient($soapClient)
    {
        $this->soapClient = $soapClient;
        return $this;
    }

    /**
     * @return \Zimbra\ZCS\SoapClient
     */
    public function getSoapClient()
    {
        return $this->soapClient;
    }

}
