<?php
class MugoSurveyOperators
{
    function __construct()
    {
    }

    function operatorList()
    {
        return array(
                'unserializematrix',
                'addmatrixanswer'
        );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array(
                        'unserializematrix' => array(),
                        'addmatrixanswer'   => array(
                                                'question'  => array( 'type' => 'integer', 'required' => true ),
                                                'answer'    => array( 'type' => 'integer', 'required' => true )
                                             )
                    );
    }

    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            // we need to unserialize the matrix descriptions and answers
            case 'unserializematrix':
            {
                if( !is_array( $operatorValue ) )
                {
                    $operatorValue = unserialize( $operatorValue );
                }
            } break;
            // this operator is used to add each answer from a survey in an array so that we
            // can display stats for a specific question
            case 'addmatrixanswer':
            {
                $question   = $namedParameters[ 'question' ];
                $answer     = $namedParameters[ 'answer' ];
                if( isset( $operatorValue[ $question ][ $answer ] ) )
                {
                    $operatorValue[ $question ][ $answer ] ++;
                }
                else
                {
                    $operatorValue[ $question ][ $answer ] = 1;
                }
            } break;
        }
    }
}

?>
