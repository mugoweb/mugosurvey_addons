{def $answer=null}
{if is_set( $question.answer )}
    {set $answer = $question.answer|unserializematrix()}
{/if}
{if is_set( $question_result.text )}
    {if is_set( $survey_validation.post_variables.variables[ $question.id ] )}
        {set $answer = $survey_validation.post_variables.variables[ $question.id ]|unserializematrix()}
    {else}
        {set $answer = $question_result.text|unserializematrix()}
    {/if}
{/if}
<div class="survey-choices">
    {def $matrix = $question.text2|unserializematrix()}
    <label>{$question.question_number}. {$question.text|wash('xhtml')} {if $question.mandatory}<strong class="required">*</strong>{/if}</label>

    <div id="matrixform">
    <table cellpadding="0" cellspacing="0">
    {for 0 to $question.num2|wash('xhtml') as $i}
        <tr>
            <td class="full">
                {if $i}
                {$matrix.1[$i|dec]}
                {/if}
            </td>

            {for 0 to $question.num|wash('xhtml')|dec as $j}
                {if eq( $i, 0 )}
                <td class="full">{$matrix.0[$j]}</td>
                {else}
                <td>
                    <input type="radio" name="{$prefix_attribute}_ezsurvey_answer_{$question.id}_{$attribute_id}[{$i|dec}]" value="{$j}" {if and( is_set( $answer[$i|dec] ), eq( $answer[$i|dec], $j ))}checked="checked"{/if} />
                </td>
                {/if}
            {/for}
        </tr>
    {/for}
    </table>
    </div>
</div>