{def $stateListUS = ezini( 'States-US', 'StateList', 'mugosurvey.ini' )}
{def $stateListCA = ezini( 'States-CA', 'StateList', 'mugosurvey.ini' )}
{default te_limit=5}
<label>{$question.question_number}. {$question.text|wash('xhtml')}</label>
<dl>
    <dt>{"Last answers"|i18n( 'survey' )}:</dt>
    <dd>
    <ul>
    {def $results=fetch('survey','text_entry_result',hash( 'question', $question,
                                                           'contentobject_id', $contentobject_id,
                                                           'contentclassattribute_id', $contentclassattribute_id,
                                                           'language_code', $language_code,
                                                           'metadata', $metadata,
                                                           'limit', $te_limit ))}
    {def $country = null}
    {foreach $results as $result}
        {if is_set( $stateListUS[$result.value] )}
            <li>{$stateListUS[$result.value]}</li>
        {elseif is_set( $stateListCA[$result.value] )}
            <li>{$stateListCA[$result.value]}</li>
        {else}
            <li>{$result.value}</li>
        {/if}
    {/foreach}
    </ul>
    </dd>
</dl>
{/default}