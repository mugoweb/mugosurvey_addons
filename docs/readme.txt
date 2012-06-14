MUGOSURVEY_ADDONS EXTENSION V2.1
======================

   The purpose of this extension is to add more question types to eZ Survey

   * The Matrix question type can be as many rows/columns you wish and the answers
   are entered as radio buttons, one answer per row:
   +---------------------------------------------------+
   ¦           ¦ Choice1 ¦ Choice2 ¦ Choice3 ¦ Choice4 ¦
   +-----------+---------+---------+---------+---------¦
   ¦ Question1 ¦    O    ¦    X    ¦    O    ¦    O    ¦
   +-----------+---------+---------+---------+---------¦
   ¦ Question2 ¦    O    ¦    O    ¦    X    ¦    O    ¦
   +-----------+---------+---------+---------+---------¦
   ¦ Question3 ¦    O    ¦    X    ¦    O    ¦    O    ¦
   +---------------------------------------------------+

   Results will be displayed in the survey tab of the Administration Interface as follows:
   - Summary view
   +---------------------------------------------------+
   ¦           ¦ Choice1 ¦ Choice2 ¦ Choice3 ¦ Choice4 ¦
   +-----------+---------+---------+---------+---------¦
   ¦ Question1 ¦ 5 ¦(50%)¦ 3 ¦(30%)¦ 1 ¦(10%)¦ 1 ¦(10%)¦
   +-----------+---+-----+---+-----+---+-----+---+-----¦
   ¦ Question2 ¦ - ¦  -  ¦ 6 ¦(60%)¦ 1 ¦(10%)¦ 3 ¦(30%)¦
   +-----------+---+-----+---+-----+---+-----+---+-----¦
   ¦ Question3 ¦ 2 ¦(20%)¦ 4 ¦(40%)¦ - ¦  -  ¦ 4 ¦(40%)¦
   +---------------------------------------------------+

   - User result view
   +-----------------------------------+
   ¦ Question1 ¦ Question2 ¦ Question3 ¦
   +-----------+-----------+-----------¦
   ¦  Choice2  ¦  Choice3  ¦  Choice2  ¦
   +-----------------------------------+

    * The Country question type is a dropdown list of countries populated by the default eZ Publish country.ini.
    The format used to store the country selections can be modified in extension/mugosurvey_addons/settings/mugosurvey.ini.append.php.
    (The default format is Alpha2.)

    * The State question type is a dropdown list of USA states and Canadian provinces populated by
    extension/mugosurvey_addons/settings/mugosurvey.ini.append.php.
    There is also an extra option "Other State" that enable users to type in a state that is not in the list.
    At the moment, this question type is not tied to the Country selection.

    * The Page break question type enables you to create long surveys within the same object and paginate them on the public side.
    Each survey page will start after each Page break question. This functionality is supported by JavaScript (specifically jQuery).
    The submit button for the survey is shown on the last page.
    If there are validation errors, then all questions (and answers) are displayed on one page, but the questions whose answers were valid
    are hidden on the page in togglable layers.
    If JavaScript is disabled in the browser, the survey will still be functional as a 1-page survey.

    * The RSVP code question type adds validation to a normal text entry type,
    so that the user must type specific text in that field before the survey is accepted.
    Valid codes can be entered by the editor separated by a comma.
    Validation is case insensitive.
    The field can be prefilled using a (code) view parameter.

REQUIREMENTS
======================

1/ This extension is an addition to eZ Survey and therefore requires that eZ Survey is already installed.
   You can find eZ Survey at: https://github.com/ezsystems/ezsurvey

2/ From the eZ Survey requirements, you need a version of eZ Publish 4.0 and higher.

3/ jQuery is required to support the Page break question type and some other enhanced functionality.


INSTALLATION
======================

1/ Make sure that eZ Survey is already installed and functional.

2/ Extract the mugosurvey_addons folder below the extension folder.

3/ Enable the extension globally (or as necessary per siteaccesses), clear the eZ Publish cache, and regenerate the autoloads array.

4/ Create or edit an object of the Survey class (or a custom one that uses the Survey datatype).
   You should now be able to add the question types provided by this extension.

USING THE "Text Entry (Validated)" QUESTION TYPE
================================================
The mugo survey addons extension comes with a question type that only accepts validated input. This question type is called
"Text Entry (Validated)" in the eZ Publish admin panel. The php class is called "ezsurveytextentryvalidated.php". This question type
uses the validation classes in mugosurvey_addons/modules/survey/classes/validation to validate answers.

The validation methods used by this question type can be extended. Currently only 2 validation types exist:
MugoSurveyAlphabeticalValidationType and MugoSurveyAlphanumericValidationType.

1) Using the question type
        If, for example, one of your surveys has a questions that should only have
        letters or spaces in the value (no numbers, etc.):
            a. go to the survey to edit and add a "Text Entry (Validated)" attribute
            b. for the newly added question, select the "Only letters or spaces" option for that specific question's "Validation Type"

2) Extending & writing your own validation
        To write your own validation method for the Validated Text Entry question type:
            a. In the mugosurveyvalidators.ini settings file, add your validation method, description and class name
                to the arrays: ValidationTypes[], ValidationTypesDescriptions[], ValidationTypesClasses[]
            b. Create the class with the name you specified in the ValidationTypesClasses[] array,
                inside your extension's classes folder. Your class has to override the "validate()"
                and your own "validate()" function must set the validation error messages. Here's an example
                of a validation type class, that validates input via a simple regular expression:
                ----------------------------------------------------------------------------------------------
                class SampleSurveyValidationType extends MugoSurveyValidationType {
                    public function validate( $text )
                    {
                        //your regular expression
                        $acceptedExpression = "<your-regex>";

                        //you can have multiple error messages, depending on your validation method;
                        //when using regular expressions, one error message is enough
                        $errorMessage       = "<your-error-message-when-something-goes-wrong>";

                        //if the input is matched to the accepted expression and return true
                        if( preg_match( $acceptedExpression, $text ) )
                        {
                            return true;
                        }
                        //otherwise, set the class errormessage and return false
                        else
                        {
                            $this->errorMessage = ezpI18n::tr( 'survey', $errorMessage );
                            return false;
                        }
                    }
                }
                ===============================================================================================
            c. Regenerate the autoloads array
            d. The new validation type should now be in the system, available to all your "Text Entry (Validated)" questions

NOTES
======================

   * In order to support page break functionality, we had to override the ezsurvey datatype template
   (extension/mugosurvey_addons/design/standard/templates/content/datatype/view/ezsurvey.tpl).
   This means that if you have already overriden this template, you will have to manually merge your changes with this extension's changes.

   * You may get unstable behavior if the Matrix question details are edited after visitors have started answering the survey.
   Recorded answers could then match different questions depending on how you the matrix has changed.

   * Changing the storage format of the Country question type will make all previous answered surveys
   unreadable for those questions, so choose a format first and stick to it!

   * States and countries are not tied together (and this extension currently only lists USA states and Canadian provinces).
   This is a feature consideration for a future version.

   * The CSV export feature of eZ Survey will export all of the answers as they are stored in the database.
   This is not particularly user-friendly for matrix, since its answers are stored as serialized in the database.
   You can, of course, parse this yourself from the CSV export. However, you might want to format the output a bit differently
   on the eZ Publish end. eZ Survey's export functionality is currently a bit rigid, so the following is a brief outline of how
   you could modify the export logic:

   1/ Create your own export module/view
   module.php:
   <?php
    $Module = array( 'name' => 'Mugo Survey Addons', 'variable_params' => true );

    $ViewList = array();
    $ViewList['export'] = array(
        'script' => 'export.php',
        'functions' => array( 'administration' ),
        'params' => array( 'ContentObjectID', 'ContentClassAttributeID', 'LanguageCode' ),
        'default_navigation_part' => 'ezsurveynavigationpart' );
    
    $FunctionList                   = array();
    $FunctionList['administration'] = array();
    ?>

    2/ In your module folder, create a "classes" folder and create a file with a PHP class in it that has an exportCSV method.
       You can use the existing export method in the eZ Survey extension as an example:
       extension/ezsurvey/modules/survey/classes/ezsurveyresult.php
       You may have to re-write large parts of it, depending on your needs for the formatting of questions in the export.

    3/ Create the export view file and replace '<YourResultClass>' in the code below with the name of the class you just created.
    <?php
    $Module = $Params['Module'];
    $contentObjectID = $Params['ContentObjectID'];
    $contentClassAttributeID = $Params['ContentClassAttributeID'];
    $languageCode = $Params['LanguageCode'];

    $survey = eZSurvey::fetchByObjectInfo( $contentObjectID, $contentClassAttributeID, $languageCode );
    if ( !is_object( $survey ) )
    {
        return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
    }
    
    $output = <YourResultClass>::exportCSV( $contentObjectID, $contentClassAttributeID, $languageCode );
    if ( $output !== false )
    {
        $contentLength = strlen( $output );
        header( "Pragma: " );
        header( "Cache-Control: " );
        header( "Content-Length: $contentLength" );
        header( "Content-Type: text/comma-separated-values" );
        header( "X-Powered-By: eZ Publish" );
        header( "Content-disposition: attachment; filename=export.csv" );
        ob_end_clean();
        print( $output );
    }
    else
    {
        echo ezpI18n::tr( 'survey', 'No results' );
        echo "\n";
    }
    eZExecution::cleanExit();
    ?>

    Once you have your module view created, you will need to override the default eZ Survey result templates for the
    Administration Interface to link the CSV export button to your module/view instead of eZ Survey's module.
    eZ Survey's templates are located here:
    extension/ezsurvey/design/standard/templates/ezsurvey/result.tpl
    extension/ezsurvey/design/standard/templates/ezsurvey/result_list.tpl
    extension/ezsurvey/design/standard/templates/ezsurvey/rview.tpl





