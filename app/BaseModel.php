<?php

namespace App;
use \Illuminate\Database\Eloquent\Model;

abstract class BaseModel extends Model
{

    protected $writeTable;
    protected $readFrom;
    protected $readOnly = [];
    protected $readOnlyCache = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->writeTable = $this->getTable();

        $this->toReadMode();
    }

    /**
     * 重写保存方法
     *
     * @param array $options
     * @return bool
     * @throws \Exception
     */
    public function save(array $options = [])
    {
        $this->toWriteMode();
        $this->cacheReadOnly();

        try {
            $saved = parent::save($options);
        } catch (\Exception $e) {
            $this->toReadMode();
            throw $e;
        }

        $this->toReadMode();
        $this->restoreReadOnly();

        return $saved;
    }

    /**
     * 缓存和移除只读属性的字段
     *
     * @return void
     */
    protected function cacheReadOnly()
    {
        $this->readOnlyCache = [];

        foreach ($this->readOnly as $key) {
            $value = $this->getAttributeValue($key);
            $this->readOnlyCache[$key] = $value;
            $this->__unset($key);
        }
    }

    /**
     * 恢复缓存只读属性的字段
     *
     * @return void
     */
    protected function restoreReadOnly()
    {
        foreach ($this->readOnlyCache as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * 获取可写表
     *
     * @return string
     */
    public function getWriteTable()
    {
        return $this->writeTable;
    }

    /**
     * 交换可写表
     *
     * @return void
     */
    protected function toWriteMode()
    {
        $this->setTable($this->getWriteTable());
    }
    /**
     * 获取只读表
     *
     * @return string
     * @throws \Exception
     */
    public function getReadFrom()
    {
        if (is_null($this->readFrom)) {
            $this->readFrom = $this->getWriteTable();
        }

        return $this->readFrom;
    }

    /**
     * 交换只读表
     *
     * @return void
     */
    protected function toReadMode()
    {
        $this->setTable($this->getReadFrom());
    }

    /**
     * 转换只读属性字段为数组
     *
     * @param string|array $value
     * @return array
     */
    protected function intArrayAttribute($value)
    {
        if (is_string($value) and strlen($value) > 0) {
            $value = explode(',', $value);
            $value = array_map('intval', $value);
        }

        return is_array($value) ? $value : [];
    }

}
