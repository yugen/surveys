<?php 

namespace Sirs\Surveys;

trait HasMetadataTrait
{
    public function parseMetadata(\SimpleXMLElement $simpleXmlElement)
    {
        if ($simpleXmlElement->metadata) {
            $metadata = [];
            foreach ($simpleXmlElement->metadata->children() as $datum) {
                if ($datum->key && $datum->value) {
                    $metadata[$datum->key->__toString()] = $datum->value->__toString();
                } else {
                    $metadata[$datum->attributes()['key']->__toString()] = $datum->attributes()['value']->__toString();
                }
            }
            $this->metadata = $metadata;
        }
    }

    public function setMetadata(array $metadata)
    {
        $this->metadata = $metadata;
        return $this;
    }

    public function getMetadata()
    {
        return (isset($this->metadata)) ? $this->metadata : [];
    }

    public function addMetadata($name, $value)
    {
        if (!isset($this->metadata)) {
            $this->metadata = [];
        }
        $this->metadata[$name] = $value;
    }

    public function removeMetadata($name)
    {
        if (isset($this->metadata) && isset($this->metadata[$name])) {
            unset($this->metadata[$name]);
        }
    }

    public function mergeMetadata($metadata)
    {
        $this->metadata = array_merge($metadata, $this->getMetadata());
    }

    public static function createWithMetadata($xmlElement, $metadata)
    {
        $class = get_called_class();
        $obj = new $class();
        $obj->setMetadata($metadata);
        $obj->parse($xmlElement); // calls the parse method
        return $obj;
    }
}
