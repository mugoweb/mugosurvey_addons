<?php
/**
 * Is used for validating letter-based strings with only letters and spaces
 *
 * @author mugodev
 */
class MugoAddonAlphabeticalValidationType extends MugoAddonValidationType {

    public function validate( $text )
    {

        $acceptedExpression = "/^[a-zA-Z\s]+$/";
        $errorMessage       = "This field can only contain letters and spaces";

        if( preg_match( $acceptedExpression, $text ) )
        {
            //if the input is matched to the accepted expression and return true
            return true;
        }
        else
        {
            //otherwise, set the class errormessage and return false
            $this->errorMessage = ezpI18n::tr( 'mugovalidatedstring', $errorMessage );
            return false;
        }
    }
}

?>
