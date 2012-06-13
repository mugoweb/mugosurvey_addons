<?php
/**
 * Is used for validating alphanumeric strings
 *
 * @author mugodev
 */
class MugoAddonAlphanumericValidationType extends MugoAddonValidationType {

    public function validate( $text )
    {

        $acceptedExpression = "/^[a-zA-Z0-9\-\s]+$/";
        $errorMessage       = "This field can only contain numbers, letters, dashes or spaces";

        if( preg_match( $acceptedExpression , $text ) )
        {
            //if the input is matched to the accepted expression and return true
            return true;
        }
        else
        {
            //otherwise, set the class errormessage and return false
            $this->errorMessage = $errorMessage ;
            return false;
        }
    }
}

?>
