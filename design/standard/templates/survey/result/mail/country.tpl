{def $countryFormat = ezini( 'QuestionSettings', 'CountryFormat', 'mugosurvey.ini' )}
{def $country = fetch( 'content', 'country_list', hash( 'filter', $countryFormat, 'value', $question.answer ))}
{$question.question_number}. {$question.text}
    {$country.Name}
