<?php

/**
 * Specifies the functions needed by any class used as a ValidationType in the
 * MugoDatatypeValidator class in order to validate the value of a datatype.
 *
 * @author mugodev
 */
abstract class MugoAddonValidationType {

    /**
     * This message should always be set by the validate() function
     * @var string
     */
    protected $errorMessage;

    public function __construct(){}

    /**
     * Retrieves the class error message, if set
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Returns true if the validation was passed and sets the error message to null.
     * Returns false otherwise and sets the errorMessage accordingly.
     *
     * @param $text - the text to validate
     * @return bool
     */
    public abstract function validate( $text );
}

?>
