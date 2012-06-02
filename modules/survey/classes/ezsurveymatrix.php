<?php
class eZSurveyMatrix extends eZSurveyQuestion
{
    /*
     * constructor
     * initializes the default number of rows / columns of a matrix
     */
    function __construct( $row = false )
    {
        if( !isset( $row[ 'num' ] ) )
        {
            $row[ 'num' ] = 5;
        }
        if( !isset( $row[ 'num2' ] ) )
        {
            $row['num2'] = 3;
        }
        if( !isset( $row[ 'mandatory' ] ) )
        {
            $row[ 'mandatory' ] = 1;
        }
        $row[ 'type' ] = 'Matrix';
        $this->eZSurveyQuestion( $row );
    }

    /*
     * called when a question is created / edited in the admin
     */
    function processEditActions( &$validation, $params )
    {
        $http = eZHTTPTool::instance();
        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $attributeID = $params['contentobjectattribute_id'];

        //num stores the number of columns in the matrix
        $postQuestionNum = $prefix . '_ezsurvey_question_' . $this->ID . '_num_' . $attributeID;
        if( $http->hasPostVariable( $postQuestionNum ) and $http->postVariable( $postQuestionNum ) != $this->Num )
        {
            $this->setAttribute( 'num', $http->postVariable( $postQuestionNum ) );
        }

        //num2 stores the number of rows in the matrix
        $postQuestionNum2 = $prefix . '_ezsurvey_question_' . $this->ID . '_num2_' . $attributeID;
        if( $http->hasPostVariable( $postQuestionNum2 ) and $http->postVariable( $postQuestionNum2 ) != $this->Num2 )
        {
            $this->setAttribute( 'num2', $http->postVariable( $postQuestionNum2 ) );
        }

        //title of the question
        $postQuestionText = $prefix . '_ezsurvey_question_' . $this->ID . '_text_' . $attributeID;
        if( $http->hasPostVariable( $postQuestionText ) and serialize( $http->postVariable( $postQuestionText ) ) != $this->Text )
        {
            $this->setAttribute( 'text', $http->postVariable( $postQuestionText ) );
        }

        //text2 stores the serialized array of all the rows/column headers
        $postQuestionText2 = $prefix . '_ezsurvey_question_' . $this->ID . '_text2_' . $attributeID;
        if( $http->hasPostVariable( $postQuestionText2 ) and serialize( $http->postVariable( $postQuestionText2 ) ) != $this->Text2 )
        {
            $this->setAttribute( 'text2', serialize( $http->postVariable( $postQuestionText2 ) ) );
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

        $postQuestionDefault = $prefix . '_ezsurvey_question_' . $this->ID . '_default_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionDefault ) and
             $http->postVariable( $postQuestionDefault ) != $this->Default )
            $this->setAttribute( 'default_value', $http->postVariable( $postQuestionDefault ) );
    }

    /*
     * Checks the mandatory attributes upon submission of an answer
     * For the matrix type, we want each row to have an answer if the matrix has been marked as mandatory
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
            $question = unserialize( $this->Text2 );
            //analyse each row and make sure we have an answer for it
            foreach( $question[ 1 ] as $key => $val )
            {
                if( !isset( $answer[ $key ] ) )
                {
                    $validation['error'] = true;
                    $validation['errors'][] = array( 'message' => ezpI18n::tr( 'survey', 'Please mark all the possible choices in question %number', null,
                                                     array( '%number' => $this->questionNumber() ) ),
                                                     'question_number' => $this->questionNumber(),
                                                     'code' => 'general_answer_number_as_well',
                                                     'question' => $this );
                    return false;
                }
            }
        }
        $this->setAnswer( $http->postVariable( $postSurveyAnswer, '' ) );
        $variableArray['answer'] = $http->postVariable( $postSurveyAnswer, '' );

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
            $surveyAnswer = serialize( $http->postVariable( $postSurveyAnswer ) );
            return $surveyAnswer;
        }

        return false;
    }
}
eZSurveyQuestion::registerQuestionType( ezpI18n::tr( 'survey', 'Matrix' ), 'Matrix' );
?>
