<?php

namespace Drupal\Component\Diff\Engine;

/**
 * @todo document
 * @private
 * @subpackage DifferenceEngine
 */
class DiffOpDelete extends DiffOp
{
    public $type = 'delete';

    public function __construct($lines)
    {
        $this->orig = $lines;
        $this->closing = false;
    }

    public function reverse()
    {
        return new DiffOpAdd($this->orig);
    }
}
