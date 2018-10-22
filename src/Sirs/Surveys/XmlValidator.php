<?php
namespace Sirs\Surveys;

/**
 * validates xml and returns detailed error messages
 *
 * @package default
 * @author
 **/
class XmlValidator
{
    protected $schemaPath;

    public function __construct($schemaPath)
    {
        $this->schemaPath = __DIR__.'/survey.xsd';
    }

    protected function libxml_display_error($error, $xmlString)
    {
        $return = "";
        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $return .= "Warning $error->code: ";
                break;
            case LIBXML_ERR_ERROR:
                $return .= "Error $error->code: ";
                break;
            case LIBXML_ERR_FATAL:
                $return .= "Fatal Error $error->code: ";
                break;
        }
        $return .= trim($error->message);
        // if ($error->file) {
        //     $return .=    " in $error->file";
        // }
        $line = preg_split("/\n/", $xmlString)[$error->line-1];
        $return .= "\nError on line $error->line: \n";
        $return .= "...\n$line\n...";

        return $return;
    }

    public function validate($xmlString)
    {
        // Enable user error handling
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadXML($xmlString);

        if (!$doc->schemaValidate($this->schemaPath)) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                throw new \Exception($this->libxml_display_error($error, $xmlString));
            }
            libxml_clear_errors();
        }
    }
} // END XmlValidator class
