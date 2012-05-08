<?php
/**
 * Admin class to query the ZCS api for alias related requests.
 *
 * @author Chris Ramakers <chris.ramakers@gmail.com>
 */
namespace Zimbra\ZCS\Admin;

class Alias
    extends \Zimbra\ZCS\Admin
{

    /**
     * Fetches a single alias from the webservice and returns it
     * as a \Zimbra\ZCS\Entity\Alias object
     * @param string $alias_id
     * @return \Zimbra\ZCS\Entity\Alias
     */
    public function getAlias($alias_id)
    {
        $attributes = array(
            'types' => 'aliases'
        );
        $params = array(
            'query' => sprintf('(zimbraId=%s)', $alias_id)
        );

        $response = $this->soapClient->request('SearchDirectoryRequest', $attributes, $params);
        $aliasList = $response->children()->SearchDirectoryResponse->children();

        return \Zimbra\ZCS\Entity\Alias::createFromXml($aliasList[0]);
    }

    /**
     * Fetches all aliasses for an account from the soap webservice and returns them as an array
     * containing \Zimbra\ZCS\Entity\Alias objects
     * @param string $account_id The id of the account you are looking things up for
     * @return array
     */
    public function getAliasListByAccount($account_id)
    {
        $attributes = array(
            'types' => 'aliases'
        );
        $params = array(
            'query' => sprintf('(zimbraAliasTargetId=%s)', $account_id)
        );

        $response = $this->soapClient->request('SearchDirectoryRequest', $attributes, $params);
        $aliasList = $response->children()->SearchDirectoryResponse->children();

        $results = array();
        foreach ($aliasList as $alias) {
            $results[] = \Zimbra\ZCS\Entity\Alias::createFromXml($alias);
        }

        return $results;
    }

    /**
     * Creates a new alias in the ZCS soap webservice
     *
     * NOTE: Due to the limitation of the webservice in ZCS we can't return the newly
     * created alias or even the ID of the new alias, there is no way to identify the
     * newly created alias unfortunately
     *
     * @param \Zimbra\ZCS\Entity\Alias $alias
     * @throws \Zimbra\ZCS\Exception\Webservice
     * @return boolean
     */
    public function createAlias(\Zimbra\ZCS\Entity\Alias $alias)
    {
        $properties = array(
            'id'    => $alias->getTargetid(),
            'alias' => $alias->getName()
        );

        $response = $this->soapClient->request('AddAccountAliasRequest', array(), $properties);
        return true;
    }


}
