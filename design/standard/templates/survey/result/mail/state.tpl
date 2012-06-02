{def $stateListUS = ezini( 'States-US', 'StateList', 'mugosurvey.ini' )}
{def $stateListCA = ezini( 'States-CA', 'StateList', 'mugosurvey.ini' )}
{$question.question_number}. {$question.text}

{if is_set( $stateListUS[$question.answer] )}
    {$stateListUS[$question.answer]}
{elseif is_set( $stateListCA[$question.answer] )}
    {$stateListCA[$question.answer]}
{else}
    {$question.answer}
{/if}
