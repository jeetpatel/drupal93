<?php

namespace Drupal\Core\Field;

/**
 * Defines an item list class for map fields.
 */
class MapFieldItemList extends FieldItemList
{
  /**
   * {@inheritdoc}
   */
    public function equals(FieldItemListInterface $list_to_compare)
    {
        $count1 = count($this);
        $count2 = count($list_to_compare);
        if ($count1 === 0 && $count2 === 0) {
            // Both are empty we can safely assume that it did not change.
            return true;
        }
        if ($count1 !== $count2) {
            // The number of items is different so they do not have the same values.
            return false;
        }

        // The map field type does not have any property defined (because they are
        // dynamic), so the only way to evaluate the equality for it is to rely
        // solely on its values.
        $value1 = $this->getValue();
        $value2 = $list_to_compare->getValue();

        return $value1 == $value2;
    }
}
