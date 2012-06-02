{def $countryFormat = ezini( 'QuestionSettings', 'CountryFormat', 'mugosurvey.ini' )}
{def $answer=null}
{if is_set( $question.answer )}
    {set $answer = $question.answer}
{/if}
{if is_set( $question_result.text )}
    {if is_set( $survey_validation.post_variables.variables[ $question.id ] )}
        {set $answer = $survey_validation.post_variables.variables[ $question.id ]}
    {else}
        {set $answer = $question_result.text}
    {/if}
{/if}
<div class="survey-choices">
    <label>{$question.question_number}. {$question.text|wash('xhtml')} {if $question.mandatory}<strong class="required">*</strong>{/if}</label>

    {def $countryList = fetch( 'content', 'country_list' )}
    <select name="{$prefix_attribute}_ezsurvey_answer_{$question.id}_{$attribute_id}">
        <option value="">Select a Country</option>
        {foreach $countryList as $country}
        <option value="{$country[$countryFormat]}" {if eq( $answer, $country[$countryFormat] )}selected{/if}>{$country.Name}</option>
        {/foreach}
    </select>
</div>