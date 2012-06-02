<h2 class="attributetype">{"State"|i18n('survey')}</h2>

<div class="block">
    <label>{"Text of question"|i18n('survey')}:</label>
    <input class="box" type="text" name="{$prefix_attribute}_ezsurvey_question_{$question.id}_text_{$attribute_id}" value="{$question.text|wash('xhtml')}" size="70" />
</div>

<div class="block">
    <input type="hidden" name="{$prefix_attribute}_ezsurvey_question_{$question.id}_mandatory_hidden_{$attribute_id}" value="1" />
    <label><input type="checkbox" name="{$prefix_attribute}_ezsurvey_question_{$question.id}_mandatory_{$attribute_id}" value="1" {if $question.mandatory}checked{/if} />
    {"Mandatory answer"|i18n('survey')}</label>
</div>

