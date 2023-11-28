<?php

namespace Thinmoto\LunarContent\Traits;

trait HasManySyncable
{
    /**
     * Overrides the default Eloquent hasMany relationship to return a HasManySyncable.
     *
     * {@inheritDoc}
     * @return \Thinmoto\LunarContent\Models\Relations\HasManySyncable
     */
    public function hasManySyncable($related, $foreignKey = null, $localKey = null)
    {
        $instance = $this->newRelatedInstance($related);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return new \Thinmoto\LunarContent\Models\Relations\HasManySyncable(
            $instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey
        );
    }
}
