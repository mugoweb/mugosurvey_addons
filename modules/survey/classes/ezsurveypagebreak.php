<?php
class eZSurveyPagebreak extends eZSurveyQuestion
{
    /*
     * constructor
     */
    function __construct( $row = false )
    {
        $row[ 'type' ] = 'Pagebreak';
        $this->eZSurveyQuestion( $row );
    }

    /*!
      This is a page break and should not require an answer.
    */
    function canAnswer()
    {
        return false;
    }

    /*!
      We don't want to increment the question number since it's not a question.
    */
    function questionNumberIterate( &$iterator ) {}
}
eZSurveyQuestion::registerQuestionType( ezpI18n::tr( 'survey', 'Page break' ), 'Pagebreak' );
?>
