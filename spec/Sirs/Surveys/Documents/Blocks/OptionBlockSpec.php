<?php

namespace spec\Sirs\Surveys\Documents\Blocks;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionBlockSpec extends ObjectBehavior
{
    function let()
    {
      $this->beConstructedWith('variable_name');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sirs\Surveys\Documents\Blocks\OptionBlock');
    }

    function it_should_get_and_set_its_label()
    {
      $label = 'Beans';
      $this->setLabel($label);
      $this->getLabel()->shouldBe($label);
    }

    function it_should_get_and_set_its_value()
    {
      $value = '1';
      $this->setValue($value);
      $this->getValue()->shouldBe($value);
    }

    function it_should_get_and_set_selected()
    {
      $selected = 1;
      $this->setSelected($selected);
      $this->getSelected()->shouldBe(true);
    }

    function it_should_default_to_not_selected()
    {
      $this->getSelected()->shouldBe(false);
    }

    function it_should_get_and_set_its_name()
    {
      $name = 'Test';
      $this->setValue(1);
      $this->getName()->shouldBe('variable_name');

      $this->setName($name);
      $this->getName()->shouldBe($name);
    }

    function it_generates_a_default_id_if_it_has_none()
    {
      $this->name = 'test';
      $this->value = '1';
      $this->getId()->shouldBe('test-1');
    }

    function it_should_render_itself_with_a_default_template()
    {
      $xml = '<option id="medicare"><label>Medicare</label><value>1</value></option>';
      $this->beConstructedWith('medicare', $xml);
      $output = <<<OUT
<div class="radio">
  <label>
    <input 
      type="radio" 
      name="medicare" 
      id="medicare"
      value="1"
    />
    Medicare
  </label>
</div>

OUT;
      $this->render()->shouldBe($output);

    }

}
