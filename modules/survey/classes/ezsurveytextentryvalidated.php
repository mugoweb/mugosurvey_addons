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
        $this->DataTypeValidator    = new MugoAddonDatatypeValidator();

        if ( !isset( $row['num'] ) )
            $row['num'] = 70;
        if ( !isset( $row['num2'] ) )
            $row['num2'] = 10;
        if ( !isset( $row['mandatory'] ) )
            $row['mandatory'] = 1;
        $row['type'] = 'TextEntryValidated';
        $this->eZSurveyEntry( $row );
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

        if ( $this->Text3 == 'user_email' )
        {
            $user = eZUser::currentUser();
            if ( get_class( $user ) == 'eZUser' and
                 $user->isLoggedIn() === true )
            {
                return $user->attribute( 'email' );
            }
        }
        else if ( $this->Text3 == 'user_name' )
        {
            $user = eZUser::currentUser();
            if ( get_class( $user ) == 'eZUser' and
                 $user->isLoggedIn() === true )
            {
                $contentObject = $user->attribute( 'contentobject' );
                if ( get_class( $contentObject ) == 'eZContentObject' )
                {
                    return $contentObject->attribute( 'name' );
                }
            }
        }

        return $this->Default;
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
                                                'code' => 'general_answer_number_as_well',
                                                'question' => $this );
            }
        }

        $this->setAnswer( trim ( $http->postVariable( $postSurveyAnswer ) ) );
        $variableArray['answer'] = trim ( $http->postVariable( $postSurveyAnswer ) );

        return $variableArray;
    }

    function processEditActions( &$validation, $params )
    {
        $http = eZHTTPTool::instance();
        $prefix = eZSurveyType::PREFIX_ATTRIBUTE;
        $attributeID = $params['contentobjectattribute_id'];

        $postQuestionText = $prefix . '_ezsurvey_question_' . $this->ID . '_text_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionText ) and
             $http->postVariable( $postQuestionText ) != $this->Text )
            $this->setAttribute( 'text', $http->postVariable( $postQuestionText ) );

        $postQuestionText2 = $prefix . '_ezsurvey_question_' . $this->ID . '_text2_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionText2 ) and
             $http->postVariable( $postQuestionText2 ) != $this->Text2 )
            $this->setAttribute( 'text2', $http->postVariable( $postQuestionText2 ) );

        $postQuestionText3 = $prefix . '_ezsurvey_question_' . $this->ID . '_text3_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionText3 ) and
             $http->postVariable( $postQuestionText3 ) != $this->Text3 )
            $this->setAttribute( 'text3', $http->postVariable( $postQuestionText3 ) );

        $postQuestionNum = $prefix . '_ezsurvey_question_' . $this->ID . '_num_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionNum ) and
             $http->postVariable( $postQuestionNum ) != $this->Num )
            $this->setAttribute( 'num', $http->postVariable( $postQuestionNum ) );

        $postQuestionNum2 = $prefix . '_ezsurvey_question_' . $this->ID . '_num2_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionNum2 ) and
             $http->postVariable( $postQuestionNum2 ) != $this->Num2 )
            $this->setAttribute( 'num2', $http->postVariable( $postQuestionNum2 ) );

        $postQuestionMandatoryHidden = $prefix . '_ezsurvey_question_' . $this->ID . '_mandatory_hidden_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionMandatoryHidden ) )
        {
            $postQuestionMandatory = $prefix . '_ezsurvey_question_' . $this->ID . '_mandatory_' . $attributeID;
            if ( $http->hasPostVariable( $postQuestionMandatory ) )
                $newMandatory = 1;
            else
                $newMandatory = 0;

            if ( $newMandatory != $this->Mandatory )
                $this->setAttribute( 'mandatory', $newMandatory );
        }

        $postQuestionDefault = $prefix . '_ezsurvey_question_' . $this->ID . '_default_' . $attributeID;
        if ( $http->hasPostVariable( $postQuestionDefault ) and
             $http->postVariable( $postQuestionDefault ) != $this->Default )
            $this->setAttribute( 'default_value', $http->postVariable( $postQuestionDefault ) );
    }
}

eZSurveyQuestion::registerQuestionType( 'Text Entry (Validated)', 'TextEntryValidated' );
?>
