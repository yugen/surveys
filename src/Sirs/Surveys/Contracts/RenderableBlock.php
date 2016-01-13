<?

namespace Sirs\Surveys\Contacts;

use Closure;
/**
 * Interface for renderable blocks
 *
 * @package sirs/surveys
 **/
interface RenderableBlock
{

  /**
   * Sets the template to use for this block
   *
   * @param string $template
   * @return void
   **/
  public function setTemplate($template = null);

  /**
   * Get the template for this block
   *
   * @return string
   **/
  public function getTemplate();

  /**
   * Render the block using it's template
   *
   * @param Closure $beforeRender - function to call before rendering the block
   * @param Closure $afterRender - function to call after rendering
   * @return string
   **/
  public function render(Closure $beforeRender = null, Closure$afterRender = null);
}
