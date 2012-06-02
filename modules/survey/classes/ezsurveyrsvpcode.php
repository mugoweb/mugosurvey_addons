<?php

class eZSurveyRSVPCode extends eZSurveyEntry
{
    function __construct( $row = false )
    {
        if ( !isset( $row['num'] ) )
            $row['num'] = 70;
        if ( !isset( $row['num2'] ) )
            $row['num2'] = 1;
        $row['type'] = 'RSVPCode';
        $this->eZSurveyEntry( $row );
    }
    
    function processViewActions( &$validation, $params )
    {
        $http = eZHTTPTool::instance();

        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $attributeID = $params['contentobjectattribute_id'];

        $postAnswer = $prefix . '_ezsurvey_answer_' . $this->ID . '_' . $attributeID;
        $answer = trim ( $http->postVariable( $postAnswer ) );
        // This answer is always mandatory
        if ( strlen( $answer ) == 0 )
        {
            $validation['error'] = true;
            $validation['errors'][] = array( 'message' => ezpI18n::tr( 'survey', 'Please answer the question %number as well!', null,
                                                                  array( '%number' => $this->questionNumber() ) ),
                                             'question_number' => $this->questionNumber(),
                                             'code' => 'rsvpcode_answer_question',
                                             'question' => $this );
        }
        // Force the answer to match an entered RSVP code
        else
        {
            $codeMatched = false;
            $validAnswers = explode( ',', $this->attribute( 'text2' ) );
            foreach( $validAnswers as $validAnswer )
            {
                if ( strcasecmp( $answer, trim( $validAnswer ) ) == 0 )
                {
                    $codeMatched = true;
                    break;
                }
            }
            
            if( !$codeMatched )
            {
                $validation['error'] = true;
                $validation['errors'][] = array( 'message' => ezpI18n::tr( 'survey', 'The RSVP code is not correct', null,
                                                                    array( '%number' => $this->questionNumber() ) ),
                                                 'question_number' => $this->questionNumber(),
                                                 'code' => 'rsvpcode_code_not_valid',
                                                 'question' => $this );
            }
        }

        $this->setAnswer( $answer );
    }


    function answer()
    {
        if ( $this->Answer !== false )
            return $this->Answer;

        $http = eZHTTPTool::instance();
        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $postSurveyAnswer = $prefix . '_ezsurvey_answer_' . $this->ID . '_' . $this->contentObjectAttributeID();
        if ( $http->hasPostVariable( $postSurveyAnswer ) )
        {
            $surveyAnswer = $http->postVariable( $postSurveyAnswer );
            return $surveyAnswer;
        }
        return '';
    }
}



eZSurveyQuestion::registerQuestionType( ezpI18n::tr( 'survey', 'RSVP Code' ), 'RSVPCode' );

?>