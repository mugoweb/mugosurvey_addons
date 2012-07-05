<?php

/**
 * @author mugodev
 */
class eZSurveyTextEntryValidated extends eZSurveyEntry
{
    public $DataTypeValidator;
    public $ValidationType;

    function __construct( $row = false )
    {
        $this->DataTypeValidator    = new MugoSurveyDatatypeValidator();

        if ( !isset( $row['num'] ) )
            $row['num'] = 70;
        if ( !isset( $row['num2'] ) )
            $row['num2'] = 10;
        if ( !isset( $row['mandatory'] ) )
            $row['mandatory'] = 1;
        $row['type'] = 'TextEntryValidated';
        $this->eZSurveyEntry( $row );
    }

    function processViewActions( &$validation, $params )
    {
        $http = eZHTTPTool::instance();
        $variableArray = array();

        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $attributeID = $params['contentobjectattribute_id'];

        $postSurveyAnswer = $prefix . '_ezsurvey_answer_' . $this->ID . '_' . $attributeID;
        if ( $this->attribute( 'mandatory' ) == 1 and strlen( trim ( $http->postVariable( $postSurveyAnswer ) ) ) == 0 )
        {
            $validation['error'] = true;
            $validation['errors'][] = array( 'message' => ezpI18n::tr( 'survey', 'Please answer the question %number as well!', null,
                                              array( '%number' => $this->questionNumber() ) ),
                                                     'question_number' => $this->questionNumber(),
                                             'code' => 'general_answer_number_as_well',
                                             'question' => $this );
        }

        $postSurveyAnswer = $prefix . '_ezsurvey_answer_' . $this->ID . '_' . $attributeID;
        if ( strlen( trim ( $http->postVariable( $postSurveyAnswer ) ) ) > 0 )
        {
            $this->DataTypeValidator->setValidationType( $this->attribute( 'text2' ) );

            if( !$this->DataTypeValidator->validate( trim ( $http->postVariable( $postSurveyAnswer ) ) ) )
            {
                $validation['error'] = true;
                $validation['errors'][] = array( 'message' => ezpI18n::tr( 'survey', $this->DataTypeValidator->getErrorMessage(), null,
                                                array( '%number' => $this->questionNumber() ) ),
                                                        'question_number' => $this->questionNumber(),
                                                'code' => 'does_not_match_validation_rule',
                                                'question' => $this );
            }
        }

        $this->setAnswer( trim ( $http->postVariable( $postSurveyAnswer ) ) );
        $variableArray['answer'] = trim ( $http->postVariable( $postSurveyAnswer ) );

        return $variableArray;
    }
}

eZSurveyQuestion::registerQuestionType( ezPI18n::tr( 'survey','Text Entry (Validated)' ), 'TextEntryValidated' );
?>

