{def $countryFormat = ezini( 'QuestionSettings', 'CountryFormat', 'mugosurvey.ini' )}
<label>{$question.question_number}. {$question.text|wash('xhtml')}</label>
{def $result = fetch('survey','text_entry_result_item',hash(   'question', $question,
                                                                    'metadata', $metadata,
                                                                    'result_id', $result_id ))}
{def $country = fetch( 'content', 'country_list', hash( 'filter', $countryFormat, 'value', $result ))}
{$country.Name}