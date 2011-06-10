<?
class BaseWarecorp_Api_Xmlrpc_Zanby_Group 
{
    /**
    * @param string $url
    * @return int|null 
    */
    public function getGroupIdByUrl($url)
    {
        preg_match_all('/^http:\/\/(.*?)\.groups\.(.*?)$/i', $url, $matches);
        if ( isset($matches[1][0]) && $matches[1][0] ) {
            $objGroup = Warecorp_Group_Factory::loadByPath($matches[1][0]);
            return $objGroup->getId();
        }
        return $matches;
    }    
    /**
    * @param int $groupID
    * @return string|null 
    */
    public function getGroupName($groupID)
    {
        $objGroup = Warecorp_Group_Factory::loadById($groupID);
        if ( null !== $objGroup->getId() ) {
            return $objGroup->getName();
        }
        return null;
    }    
    /**
    * @param int $groupID
    * @return string|null 
    */
    public function getMembersCount($groupID)
    {
        $objGroup = Warecorp_Group_Factory::loadById($groupID);
        if ( null !== $objGroup->getId() ) {
            return $objGroup->getMembers()->getCount();
        }
        return null;
    }
    
    
}
