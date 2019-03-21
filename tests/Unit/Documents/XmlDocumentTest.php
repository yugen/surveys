<?php

namespace Sirs\Surveys\Tests\Unit\Documents;

use Sirs\Surveys\Documents\XmlDocument;
use Sirs\Surveys\Tests\TestCase;

class XmlDocumentTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function can_compile_an_xml_string_with_includes()
    {
        $xmlDoc = $this->getMockForAbstractClass(XmlDocument::class);

        $compiled = $this->invokeMethod($xmlDoc, 'compile', [file_get_contents(__DIR__.'/../../files/survey_xml/test_survey_one.xml')]);

        $this->assertRegExp('/name="included_page"/', $compiled);
    }
}
