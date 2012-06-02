{def $stateListUS = ezini( 'States-US', 'StateList', 'mugosurvey.ini' )}
{def $stateListCA = ezini( 'States-CA', 'StateList', 'mugosurvey.ini' )}
{def $answer=''}
{def $answer2=''}
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
{if and( $answer, is_unset( $stateListUS[ $answer ] ), is_unset( $stateListCA[ $answer ] ))}
    {set $answer2 = $answer}
    {set $answer = 'other'}
{/if}

<div class="survey-choices">
    <label>{$question.question_number}. {$question.text|wash('xhtml')} {if $question.mandatory}<strong class="required">*</strong>{/if}</label>
    <select name="{$prefix_attribute}_ezsurvey_answer_{$question.id}_{$attribute_id}" onchange="changeState($(this).val())">
        <option value="">Select a State</option>
        <option value="other" {if eq( $answer, 'other' )}selected{/if}>** Other State **</option>
        {if $stateListUS}
	        <optgroup label="US States">
	        {foreach $stateListUS as $code => $state}
	        <option value="{$code}" {if eq( $answer, $code )}selected{/if}>{$state}</option>
	        {/foreach}
	        </optgroup>
        {/if}
        {if $stateListCA}
	        <optgroup label="Canadian Provinces">
	        {foreach $stateListCA as $code => $state}
	        <option value="{$code}" {if eq( $answer, $code )}selected{/if}>{$state}</option>
	        {/foreach}
	        </optgroup>
	    {/if}
    </select>
    <input name="{$prefix_attribute}_ezsurvey_answer2_{$question.id}_{$attribute_id}" value="{$answer2}" />
</div>
<script type="text/javascript">

{if ne( $answer, 'other' )}
    {literal}
    $(document).ready(function() {
    {/literal}
        $('input[name="{$prefix_attribute}_ezsurvey_answer2_{$question.id}_{$attribute_id}"]').hide();
    {literal}
    });
    {/literal}
{/if}
{literal}
function changeState( state )
{
    if( state == 'other' )
    {
        $('input[name="{/literal}{$prefix_attribute}_ezsurvey_answer2_{$question.id}_{$attribute_id}{literal}"]').show();
    }
    else
    {
        $('input[name="{/literal}{$prefix_attribute}_ezsurvey_answer2_{$question.id}_{$attribute_id}{literal}"]').hide();
    }
}
</script>
{/literal}