<?php
/**
 * Is used to execute the validation on the mugovalidatedstring eZ Publish
 * datatype. It uses the custom MugoValidationType php class to run
 * the validation.
 *
 * @author mugodev
 */
class MugoAddonDatatypeValidator
{
    /**
     * @var MugoValidationType
     */
    protected $validationType;

    protected $errorMessage;

    function __construct( $validationType = null )
    {
        $this->setValidationType( $validationType );
    }

    function setValidationType( $validationType )
    {
        if($validationType != null)
        {
            $iniSettings                = ezINI::instance( "mugoaddondatatypes.ini" );
            $validationTypeClassArray   = $iniSettings->variable( "Validation", "ValidationTypesClasses" );
            $validationTypeClass        = $validationTypeClassArray[$validationType];
            $this->validationType   = new $validationTypeClass();
        }
    }

    function validate( $text )
    {
        if( $this->validationType == null )
        {
            $this->errorMessage = "Unknown error occurred. Please contact your site administrator.";
            return false;
        }
        if( !$this->validationType->validate( $text ) )
        {
            $this->errorMessage = $this->validationType->getErrorMessage();
            return false;
        }

        $this->errorMessage = null;
        return true;
    }

    function getErrorMessage()
    {
        return $this->errorMessage;
    }

}

?>