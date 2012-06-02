<h2 class="attributetype">{"RSVP Code"|i18n('survey')}</h2>

<div class="block">
<label>{"Number of columns for an answer textarea"|i18n('survey')}:</label>
<input type="text" name="{$prefix_attribute}_ezsurvey_question_{$question.id}_num_{$attribute_id}" value="{$question.num|wash('xhtml')}" size="3" />
</div>

<div class="block">
<label>{"Number of rows"|i18n('survey')}:</label>
<input type="text" name="{$prefix_attribute}_ezsurvey_question_{$question.id}_num2_{$attribute_id}" value="{$question.num2|wash('xhtml')}" size="3" />
</div>

<div class="block">
<label>{"Text of question"|i18n('survey')}:</label>
<input class="box" type="text" name="{$prefix_attribute}_ezsurvey_question_{$question.id}_text_{$attribute_id}" value="{$question.text|wash('xhtml')}" size="70" />
</div>

<div class="block">
<label>{"Code (separated by commas)"|i18n('survey')}:</label><div class="labelbreak"></div>
<input class="box" type="text" name="{$prefix_attribute}_ezsurvey_question_{$question.id}_text2_{$attribute_id}" value="{$question.text2|wash('xhtml')}" size="70" />
</div>