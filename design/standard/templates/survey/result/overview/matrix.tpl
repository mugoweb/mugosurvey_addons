{def $questions = $question.text2|unserializematrix()}
<label>{$question.question_number}.
{$question.text|wash('xhtml')} {if $question.mandatory}<strong class="required">*</strong>{/if}</label>

<div id="matrixform">
<table cellpadding="0" cellspacing="0">
    {def $results = fetch('survey','text_entry_result',hash( 'question', $question,
                                                        'contentobject_id', $contentobject_id,
                                                        'contentclassattribute_id', $contentclassattribute_id,
                                                        'language_code', $language_code,
                                                        'metadata', $metadata ))}
    {* gather the answers and add them up *}
    {def $arrayResult = null}
    {def $answers = array()}
    {foreach $results as $result}
        {set $arrayResult = $result.value|unserializematrix()}
        {foreach $arrayResult as $question => $answer}
            {set $answers = $answers|addmatrixanswer( $question, $answer )}
        {/foreach}
    {/foreach}

    {* display the global results *}
    <tr>
        <th></th>
        {foreach $questions.0 as $text}
        <th colspan="2">{$text}</th>
        {/foreach}
    <tr>
    {foreach $questions.1 as $row => $text}
    <tr>
        <th>{$text}</th>
        {foreach $questions.0 as $col => $text}
        <td>
            {if is_set( $answers[$row][$col] )}
                {$answers[$row][$col]}
            {else}
                -
            {/if}
        </td>
        <td>
            {if is_set( $answers[$row][$col] )}
                ({$answers[$row][$col]|mul(100)|div($results|count)|round}%)
            {else}
                -
            {/if}
        </td>
        {/foreach}
    </tr>
    {/foreach}
</table>
</div>