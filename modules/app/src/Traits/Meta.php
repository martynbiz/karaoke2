<?php
namespace App\Traits;

trait Meta
{
    public function getMeta($name)
    {
        if ($metas = $this->meta) {
            foreach ($metas as $meta) {
                if ($meta->name == $name) {
                    return $meta->value;
                }
            }
        }

        return null;
    }
}
