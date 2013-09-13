<?php
/**
 * User class to query the ZCS api for Account related requests.
 * Mostly a copy of the Admin class.
 *
 * @author Reinier Pelayo
 */
namespace Zimbra\ZCS\User;

class Account
    extends \Zimbra\ZCS\User
{
    /**
     * Fetches a single account from the webservice and returns it
     * as a \Zimbra\ZCS\Entity\Account object
     * @param string $account
     * @return \Zimbra\ZCS\Entity\Account
     */
    public function getAccount($account)
    {
        $params = array(
            'account' => array(
                '_'  => $account,
                'by' => 'id',
            )
        );
        $response = $this->soapClient->request('GetAccountRequest', array(), $params);
        $accounts = $response->children()->GetAccountResponse->children();
        return \Zimbra\ZCS\Entity\Account::createFromXml($accounts[0]);
    }

    public function getAccountIDByUsername($username){
        $attributes = array(
        );
        $params = array(
            'owner' => array(
                'attributes'=>array(
                    'by'=>'name'
                ),
                $username
            )

        );

        $response = $this->soapClient->request('GetShareInfoRequest', $attributes, $params);
        $xml = $response->children()->GetShareInfoResponse->children()->share;


        $attr = $xml->attributes();
        return $attr['ownerId'].":".$attr['folderId'];

    }

    /**
     * Searches the GAL for a matching account name.
     *
     * @param string $query The search query
     * @param string $domain Limit the search to this domain
     * @param boolean $ldapFilter Use the $query param as a full LDAP filter, when this is false (default)
     *                            the $query is used as the matching part for a filter on the mail attribute
     * @return \Zimbra\ZCS\Entity\Account
     */
    public function searchGAL($query = '')
    {
        $attributes = array(
            '/searchFilter/conds/cond@attr' => "name",
            '/searchFilter/conds/cond@op' => 'startswith',
            '/searchFilter/conds/cond@value' => $query
        );

        $params = array(
            'query' => htmlspecialchars($query, ENT_QUOTES)
        );

        $response = $this->soapClient->request('SearchGalRequest', $attributes, $params);
        $accountList = $response->children()->SearchDirectoryResponse->children();

        $results = array();
        foreach ($accountList as $account) {
            $results[] = \Zimbra\ZCS\Entity\Account::createFromXml($account);
        }

        return $results;
    }


    /**
     * Searches the GAL for a matching account name.
     *
     * @param string $query The search query
     * @param string $domain Limit the search to this domain
     * @param boolean $ldapFilter Use the $query param as a full LDAP filter, when this is false (default)
     *                            the $query is used as the matching part for a filter on the mail attribute
     * @return \Zimbra\ZCS\Entity\Account
     */
    public function getCal($query = '')
    {
        $attributes = array();

        $params = array(
            'query' => htmlspecialchars($query, ENT_QUOTES)
        );

        $response = $this->soapClient->request('GetICalRequest', $attributes, $params);
        var_dump($response);
        die();
        $calList = $response->children()->SearchDirectoryResponse->children();

        return $calList;
    }

}
