{def $questions = $question.text2|unserializematrix()}
{def $answers = $question.answer}
{$question.question_number}. {$question.text}
{foreach $questions.1 as $key => $text}

    {$text}: {$questions.0[$answers[$key]]}
{/foreach}
