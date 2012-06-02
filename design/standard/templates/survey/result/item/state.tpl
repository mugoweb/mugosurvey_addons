{def $stateListUS = ezini( 'States-US', 'StateList', 'mugosurvey.ini' )}
{def $stateListCA = ezini( 'States-CA', 'StateList', 'mugosurvey.ini' )}
<label>{$question.question_number}. {$question.text|wash('xhtml')}</label>
{def $result = fetch('survey','text_entry_result_item',hash(   'question', $question,
                                                                    'metadata', $metadata,
                                                                    'result_id', $result_id ))}
{if is_set( $stateListUS[$result] )}
    {$stateListUS[$result]}
{elseif is_set( $stateListCA[$result] )}
    {$stateListCA[$result]}
{else}
    {$result}
{/if}