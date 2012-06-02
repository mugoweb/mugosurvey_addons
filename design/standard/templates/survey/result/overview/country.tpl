{def $countryFormat = ezini( 'QuestionSettings', 'CountryFormat', 'mugosurvey.ini' )}
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
        {set $country = fetch( 'content', 'country_list', hash( 'filter', $countryFormat, 'value', $result.value ) )}
        {if is_set( $country.Name )}
            <li>{$country.Name}</li>
        {/if}
    {/foreach}
    </ul>
    </dd>
</dl>
{/default}