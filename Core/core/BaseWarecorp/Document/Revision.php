<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

class BaseWarecorp_Document_Revision extends Warecorp_Data_Entity
{
    private $revisionId;
    private $documentId;
    private $revisionCreatorId;
    private $revisionNumber;
    private $revisionDescription;
    private $revisionDate;
    
    private $filePath;
    private $document;
    private $revisionObjData;
    private $revisionCreator;
    
    /**
     * @return unknown
     */
    public function getDocument()
    {
        if ( null === $this->document ) {
            $this->document = new Warecorp_Document_Item($this->getDocumentId());
        }
        return $this->document;
    }

    /**
     * @return unknown
     */
    public function getFilePath()
    {
        if ( $this->filePath === null ) {
            $this->filePath = $this->setFilePath(md5( $this->getDocumentId().'_rev_'.$this->getRevisionNumber() ).'_revision.file');
        }
        return $this->filePath;
    }
    
    /**
     * @param unknown_type $filePath
     */
    public function setFilePath( $filePath )
    {
        $folder = str_replace('.file', '', $this->getDocument()->getFilePath());
        if ( !file_exists(DOC_ROOT.$folder) ) {
            mkdir(DOC_ROOT.$folder);
            chmod(DOC_ROOT.$folder, 0777);
        }       
        $this->filePath = $folder.'/'.$filePath;
    }

    
    /**
     * @return unknown
     */
    public function getDocumentId()
    {
        return $this->documentId;
    }
    
    /**
     * @param unknown_type $documentId
     */
    public function setDocumentId( $documentId )
    {
        $this->documentId = $documentId;
    }
    
    /**
     * @return unknown
     */
    public function getRevisionCreatorId()
    {
        return $this->revisionCreatorId;
    }
    
    /**
     * @param unknown_type $revisionCreatorId
     */
    public function setRevisionCreatorId( $revisionCreatorId )
    {
        $this->revisionCreatorId = $revisionCreatorId;
    }
    
    /**
     * @return Warecorp_User
     */
    public function getRevisionCreator()
    {
        if ( null === $this->revisionCreator ) {
            $this->revisionCreator = new Warecorp_User('id', $this->getRevisionCreatorId());
        }
        return $this->revisionCreator;
    }
    
    /**
     * @return unknown
     */
    public function getRevisionDate()
    {
        return $this->revisionDate;
    }
    
    public function getRevisionDateObj()
    {
        if ( null === $this->revisionObjData && null !== $this->getRevisionDate() ) {
            $this->revisionObjData = new Zend_Date($this->revisionDate, Zend_Date::ISO_8601);
        }
        return $this->revisionObjData;
    }
    
    /**
     * @param unknown_type $revisionDate
     */
    public function setRevisionDate( $revisionDate )
    {
        $this->revisionDate = $revisionDate;
    }
    
    /**
     * @return unknown
     */
    public function getRevisionDescription()
    {
        return $this->revisionDescription;
    }
    
    /**
     * @param unknown_type $revisionDescription
     */
    public function setRevisionDescription( $revisionDescription )
    {
        $this->revisionDescription = $revisionDescription;
    }
    
    /**
     * @return unknown
     */
    public function getRevisionId()
    {
        return $this->revisionId;
    }
    
    /**
     * @param unknown_type $revisionId
     */
    public function setRevisionId( $revisionId )
    {
        $this->revisionId = $revisionId;
    }
    
    /**
     * @return unknown
     */
    public function getRevisionNumber()
    {
        return $this->revisionNumber;
    }
    
    /**
     * @param unknown_type $revisionNumber
     */
    public function setRevisionNumber( $revisionNumber )
    {
        $this->revisionNumber = $revisionNumber;
    }
    /**
     * Constructor.
     * @author Artem Sukharev
     */
    public function __construct($id = null)
    {
        parent::__construct('zanby_documents__revisions');
        $this->pkColName = 'revision_id';

        $this->addField('revision_id',          'revisionId');
        $this->addField('document_id',          'documentId');
        $this->addField('revision_creator_id',  'revisionCreatorId');
        $this->addField('revision_number',      'revisionNumber');
        $this->addField('revision_description', 'revisionDescription');     
        $this->addField('revision_date',        'revisionDate');

        if ($id !== null){            
            $this->load($id);
            if ( null !== $this->getRevisionId() ) {
                $this->setFilePath(md5( $this->getDocumentId().'_rev_'.$this->getRevisionNumber() ).'_revision.file');
            }
        }
    }
    
    /**
     * Enter description here...
     *
     * @param string $revisionFileName
     * @return unknown
     */
    public function create( $revisionFileName, $isMainRevision = false )
    {        
        $RevisionNumber = $this->getNextRevisionNumber();
        if ( !$isMainRevision && $RevisionNumber == 0 ) {
            $objRevision = new Warecorp_Document_Revision();
            $objRevision->setDocumentId($this->getDocumentId());
            $objRevision->setRevisionDescription('Original file');
            $objRevision->setRevisionCreatorId($this->getDocument()->getCreatorId());
            $objRevision->create(DOC_ROOT.$this->getDocument()->getFilePath(), true);
            $RevisionNumber = 1;
        }
        $this->setRevisionNumber($RevisionNumber);
        $this->save();
        if ( file_exists($revisionFileName) ) {
            copy( $revisionFileName, DOC_ROOT.$this->getFilePath() );
            chmod(DOC_ROOT.$this->getFilePath(), 0777);            
            rename( $revisionFileName, DOC_ROOT.$this->getDocument()->getFilePath() );
        }        
        /*
        $this->save();
        if ( file_exists($revisionFileName) ) {
            copy( DOC_ROOT.$this->getDocument()->getFilePath(), DOC_ROOT.$this->getFilePath() );
            chmod(DOC_ROOT.$this->getFilePath(), 0777);            
            rename( $revisionFileName, DOC_ROOT.$this->getDocument()->getFilePath() );
        } 
        */       
        return true;
    }
    
    public function save()
    {
        if ( null === $this->getRevisionNumber() )  $this->setRevisionNumber($this->getNextRevisionNumber());
        if ( null === $this->getRevisionDate() ) {
            $tz = date_default_timezone_get();
            date_default_timezone_set('UTC');
            $date = new Zend_Date();
            $this->setRevisionDate($date->toString('YYYY-MM-dd hh:mm:ss'));
            date_default_timezone_set($tz);                        
        }
        parent::save();
        
        $this->setFilePath(md5( $this->getDocumentId().'_rev_'.$this->getRevisionNumber() ).'_revision.file');
    }
    
    public function revert()
    {
        $document = $this->getDocument();
        $document->setCreationDate($this->getRevisionDate());
        $document->setUpdateDate($this->getRevisionDate());
        $document->keepDates = true;
        $document->setRevisionId($this->getRevisionId());
        $document->save();
        copy( DOC_ROOT.$this->getFilePath(), DOC_ROOT.$this->getDocument()->getFilePath() );
    }
    
    private function getNextRevisionNumber()
    {
        $query = $this->_db->select()->from('zanby_documents__revisions', new Zend_Db_Expr('MAX(revision_number)'));
        $query->where('document_id = ?', $this->getDocumentId());
        $number = $this->_db->fetchOne($query);
        $number = ( $number ) ? ($number + 1) : 0;  
        return $number; 
    }
}
