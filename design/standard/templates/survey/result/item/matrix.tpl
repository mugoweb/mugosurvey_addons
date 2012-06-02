{def $questions = $question.text2|unserializematrix()}
<div id="matrixform">
<label>{$question.question_number}. {$question.text|wash('xhtml')}</label>
<table cellpadding="0" cellspacing="0">
    <tr>
    {foreach $questions.1 as $text}
        <th>{$text}</th>
    {/foreach}
    </tr>
    {def $result = fetch('survey','text_entry_result_item',hash(   'question', $question,
                                                                    'metadata', $metadata,
                                                                    'result_id', $result_id ))}
    <tr>
        {set $result = $result|unserializematrix()}
        {foreach $questions.1 as $key => $text}
        <td>{if is_set( $questions.0[$result[$key]] )}{$questions.0[$result[$key]]}{/if}</td>
        {/foreach}
    <tr>
</table>
</div>
