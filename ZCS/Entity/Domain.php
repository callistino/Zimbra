<?php

/**
 * A Domain.
 *
 * @author LiberSoft <info@libersoft.it>
 * @author Chris Ramakers <chris.ramakers@gmail.com>
 * @license http://www.gnu.org/licenses/gpl.txt
 */

namespace Zimbra\ZCS\Entity;

class Domain extends \Zimbra\ZCS\Entity
{
    /**
     * Extra field mapping
     * @var array
     */
    protected $_datamap = array(
        'zimbraDomainDefaultCOSId' => 'default_cos_id',
        'zimbraDomainName' => 'name'
    );
}
