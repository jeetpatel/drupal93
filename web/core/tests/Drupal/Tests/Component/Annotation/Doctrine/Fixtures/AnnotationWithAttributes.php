<?php

namespace Drupal\Tests\Component\Annotation\Doctrine\Fixtures;

/**
 * @Annotation
 * @Target("ALL")
 * @Attributes({
      @Attribute("mixed",                type = "mixed"),
      @Attribute("boolean",              type = "boolean"),
      @Attribute("bool",                 type = "bool"),
      @Attribute("float",                type = "float"),
      @Attribute("string",               type = "string"),
      @Attribute("integer",              type = "integer"),
      @Attribute("array",                type = "array"),
      @Attribute("arrayOfIntegers",      type = "array<integer>"),
      @Attribute("arrayOfStrings",       type = "string[]"),
      @Attribute("annotation",           type = "Drupal\Tests\Component\Annotation\Doctrine\Fixtures\AnnotationTargetAll"),
      @Attribute("arrayOfAnnotations",   type = "array<Drupal\Tests\Component\Annotation\Doctrine\Fixtures\AnnotationTargetAll>"),
  })
 */
final class AnnotationWithAttributes
{
    final public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    private $mixed;
    private $boolean;
    private $bool;
    private $float;
    private $string;
    private $integer;
    private $array;
    private $annotation;
    private $arrayOfIntegers;
    private $arrayOfStrings;
    private $arrayOfAnnotations;

    /**
     * @return mixed
     */
    public function getMixed()
    {
        return $this->mixed;
    }

    /**
     * @return boolean
     */
    public function getBoolean()
    {
        return $this->boolean;
    }

    /**
     * @return bool
     */
    public function getBool()
    {
        return $this->bool;
    }

    /**
     * @return float
     */
    public function getFloat()
    {
        return $this->float;
    }

    /**
     * @return string
     */
    public function getString()
    {
        return $this->string;
    }

    public function getInteger()
    {
        return $this->integer;
    }

    /**
     * @return array
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @return Drupal\Tests\Component\Annotation\Doctrine\Fixtures\AnnotationTargetAll
     */
    public function getAnnotation()
    {
        return $this->annotation;
    }

    /**
     * @return string[]
     */
    public function getArrayOfStrings()
    {
        return $this->arrayOfIntegers;
    }

    /**
     * @return array<integer>
     */
    public function getArrayOfIntegers()
    {
        return $this->arrayOfIntegers;
    }

    /**
     * @return array<Drupal\Tests\Component\Annotation\Doctrine\Fixtures\AnnotationTargetAll>
     */
    public function getArrayOfAnnotations()
    {
        return $this->arrayOfAnnotations;
    }
}
