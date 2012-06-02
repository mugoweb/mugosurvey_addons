<?php
class eZSurveyCountry extends eZSurveyQuestion
{
    /*
     * constructor
     */
    function __construct( $row = false )
    {
        $row[ 'type' ] = 'Country';
        $this->eZSurveyQuestion( $row );
    }

    /*
     * called when a question is created / edited in the admin
     * In this case we only have to save the question text and the mandatory checkbox value
     */
    function processEditActions( &$validation, $params )
    {
        $http = eZHTTPTool::instance();
        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $attributeID = $params[ 'contentobjectattribute_id' ];

        //title of the question
        $postQuestionText = $prefix . '_ezsurvey_question_' . $this->ID . '_text_' . $attributeID;
        if( $http->hasPostVariable( $postQuestionText ) and $http->postVariable( $postQuestionText ) != $this->Text )
        {
            $this->setAttribute( 'text', $http->postVariable( $postQuestionText ) );
        }

        $postQuestionMandatoryHidden = $prefix . '_ezsurvey_question_' . $this->ID . '_mandatory_hidden_' . $attributeID;
        if( $http->hasPostVariable( $postQuestionMandatoryHidden ) )
        {
            $postQuestionMandatory = $prefix . '_ezsurvey_question_' . $this->ID . '_mandatory_' . $attributeID;
            if( $http->hasPostVariable( $postQuestionMandatory ) )
                $newMandatory = 1;
            else
                $newMandatory = 0;

            if( $newMandatory != $this->Mandatory )
                $this->setAttribute( 'mandatory', $newMandatory );
        }
    }

    /*
     * Checks if a country has been selected in the case the question is mandatory
     */
    function processViewActions( &$validation, $params )
    {
        $http = eZHTTPTool::instance();
        $variableArray = array();

        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $attributeID = $params[ 'contentobjectattribute_id' ];

        $postSurveyAnswer = $prefix . '_ezsurvey_answer_' . $this->ID . '_' . $attributeID;
        if ( $this->attribute( 'mandatory' ) == 1 )
        {
            $answer   = $http->postVariable( $postSurveyAnswer, '' );
            if( !$answer )
            {
                $validation['error'] = true;
                $validation['errors'][] = array( 'message' => ezpI18n::tr( 'survey', 'Please select a country in question %number', null,
                                                 array( '%number' => $this->questionNumber() ) ),
                                                 'question_number' => $this->questionNumber(),
                                                 'code' => 'general_answer_number_as_well',
                                                 'question' => $this );
                return false;
            }
        }
        $this->setAnswer( $http->postVariable( $postSurveyAnswer, '' ) );
        $variableArray[ 'answer' ] = $http->postVariable( $postSurveyAnswer, '' );

        return $variableArray;
    }

    /*
     * called when a user answers a question on the public side
     */
    function answer()
    {
        if( $this->Answer !== false )
            return $this->Answer;

        $http = eZHTTPTool::instance();
        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $postSurveyAnswer = $prefix . '_ezsurvey_answer_' . $this->ID . '_' . $this->contentObjectAttributeID();
        if( $http->hasPostVariable( $postSurveyAnswer ) )
        {
            $surveyAnswer = $http->postVariable( $postSurveyAnswer );
            return $surveyAnswer;
        }

        return false;
    }
}
eZSurveyQuestion::registerQuestionType( ezpI18n::tr( 'survey', 'Country' ), 'Country' );
?>
