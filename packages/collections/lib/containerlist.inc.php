<?php
abstract class Collections_ContainerList
{
	/**
    * Deletes ContainerList from the database
    *
    * @return boolean
    */
    public function dbDelete()
    {
        global $_ARCHON;

        if(!$_ARCHON->deleteObject($this, MODULE_COLLECTIONS, 'tblCollections_ContainerLists'))
        {
          return false;
        }

        return true;
    }


	/**
    * Loads ContainerList from the database
    *
    * @return boolean
    */
    public function dbLoad()
    {
        global $_ARCHON;

        if(!$_ARCHON->loadObject($this, 'tblCollections_ContainerLists'))
        {
            return false;
        }
        return true;
    }

	/**
    * Stores ContainerList to the database
    *
    * @return boolean
    */
    public function dbStore()
    {
        global $_ARCHON;

        $checkquery = "SELECT ID FROM tblCollections_ContainerLists WHERE CollectionID = ? AND Contents = ? AND ID != ?";
        $checktypes = array('integer', 'text', 'integer');
        $checkvars = array($this->CollectionID, $this->Contents, $this->ID);
        $checkqueryerror = "A ContainerList with the same Contents already exists in the database";
        $problemfields = array('CollectionID', 'Contents');
        $requiredfields = array('CollectionID', 'Contents');
        
        if(!$_ARCHON->storeObject($this, MODULE_COLLECTIONS, 'tblCollections_ContainerLists', $checkquery, $checktypes, $checkvars, $checkqueryerror, $problemfields, $requiredfields))
        {
            return false;
        }
        return true;
    }

    public function verifyDeletePermissions()
    {
        global $_ARCHON;

        if(!$_ARCHON->Security->verifyPermissions(MODULE_COLLECTIONS, UPDATE))
        {
            return false;
        }
        
        // If CollectionID is not present, try to load.
        // If the content has been somehow orphaned, we still want to go
        // ahead with the deletion as long as the user is not limited to a specific
        // repository, which is checked later.
        if(!$this->CollectionID)
        {
            $this->dbLoad();
        }

        if(!$this->Collection)
        {
            $this->Collection = New Collection($this->CollectionID);
            $this->Collection->dbLoad();
        }

        // Make sure user isn't dealing with a content from another repository if they're limited
        if(array_key_exists($this->Collection->RepositoryID, $_ARCHON->Security->Session->User->Repositories) == false && $_ARCHON->Security->Session->User->RepositoryLimit)
        {
            $_ARCHON->declareError("Could not delete LocationEntry: LocationEntries may only be altered for the primary repository.");
            return false;
        }
        
        return true;
    }

    public function verifyStorePermissions()
    {
    	global $_ARCHON;

        if(!$_ARCHON->Security->verifyPermissions(MODULE_COLLECTIONS, UPDATE))
        {
            return false;
        }
        
        // If CollectionID is not present, try to load.
        // If the content has been somehow orphaned, we still want to go
        // ahead with the deletion as long as the user is not limited to a specific
        // repository, which is checked later.
        if(!$this->CollectionID)
        {
            $this->dbLoad();
        }

        if(!$this->Collection)
        {
            $this->Collection = New Collection($this->CollectionID);
            $this->Collection->dbLoad();
        }

        // Make sure user isn't dealing with a content from another repository if they're limited
        if(array_key_exists($this->Collection->RepositoryID, $_ARCHON->Security->Session->User->Repositories) == false && $_ARCHON->Security->Session->User->RepositoryLimit)
        {
            $_ARCHON->declareError("Could not delete LocationEntry: LocationEntries may only be altered for the primary repository.");
            return false;
        }
        
        return true;
    }

  /**
   * Returns a link to the container list.
   *
   * @param int $linkType
   * @return string
   */
  public function toString($linkType = LINK_NONE) {

    $collection = new Collection();
    $collection->ID = $this->CollectionID;

    $collection->dbLoad();
    return $collection->toString($linkType);
  }

	/**
	 * @var integer
	 */
    public $ID = 0;

    /**
     * @var integer
     */
    public $CollectionID = 0;

    /**
     * @var string
     */
    public $Contents = '';

    /**
     * @var string
     */
    public $URL = '';

    /**
     * @var string
     */
    public $LinkLabel = '';
}

$_ARCHON->mixClasses('ContainerList', 'Collections_ContainerList');
