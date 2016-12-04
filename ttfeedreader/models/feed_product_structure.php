<?php


/**
 * Product structure.
 */
class FeedProductStructure {

    /**
     * @var string
     */
    public $id = false;

    /**
     * @var string
     */
    public $name = false;

    /**
     * @var string
     */
    public $currency = false;

    /**
     * @var string
     */
    public $price = false;

    /**
     * @var string
     */
    public $url = false;

    /**
     * @var string[]
     */
    public $images = false;

    /**
     * @var string
     */
    public $description = false;

    /**
     * @var string[]
     */
    public $categories = false;

    /**
     * @var string
     */
    public $brand = false;
	
	/**
     * @var string
     */
    public $type = false;
	
	/**
     * @var string
     */
    public $sku = false;
	
	/**
     * @var string
     */
    public $stock = false;
	
	/**
     * @var string
     */
    public $ean = false;
	
	/**
     * @var string
     */
    public $thumbnail = false;
	
	/**
     * @var string
     */
    public $largeimage = false;
	
	/**
     * @var string
     */
    public $deliverycost = false;
	
	/**
     * @var string
     */
    public $deliverytime = false;
	
	/**
     * @var string
     */
    public $featuredurl = false;
	
	/**
     * @var string
     */
    public $alt = false;
	
    /**
     * @return this|int
     */
    function id($newValue = null) {
        return $this->setget('id', $newValue);
    }

    /**
     * @return this|[]
     */
    function name($newValue = null) {
        return $this->setget('name', $newValue);
    }

    /**
     * @return this|string
     */
    function currency($newValue = null) {
        return $this->setget('currency', $newValue);
    }

    /**
     * @return this|string
     */
    function price($newValue = null) {
        return $this->setget('price', $newValue);
    }

    /**
     * @return this|string
     */
    function url($newValue = null) {
        return $this->setget('url', $newValue);
    }

    /**
     * @return this|string
     */
    function images($newValue = null) {
        return $this->setget('images', $newValue);
    }

    /**
     * @return this|string
     */
    function description($newValue = null) {
        return $this->setget('description', $newValue);
    }

    /**
     * @return this|string[]
     */
    function categories($newValue = null) {
        return $this->setget('categories', $newValue);
    }
	
    /**
     * @return this|string
     */
    function brand($newValue = null) {
        return $this->setget('brand', $newValue);
    }
	
    /**
     * @return this|string
     */
    function type($newValue = null) {
        return $this->setget('type', $newValue);
    }
	
	/**
     * @return this|string
     */
    function sku($newValue = null) {
        return $this->setget('sku', $newValue);
    }
	
    /**
     * @return this|string
     */
    function stock($newValue = null) {
        return $this->setget('stock', $newValue);
    }
	
    /**
     * @return this|string
     */
    function ean($newValue = null) {
        return $this->setget('ean', $newValue);
    }
	
    /**
     * @return this|string
     */
    function thumbnail($newValue = null) {
        return $this->setget('thumbnail', $newValue);
    }
	
    /**
     * @return this|string
     */
    function largeimage($newValue = null) {
        return $this->setget('largeimage', $newValue);
    }
	
    /**
     * @return this|string
     */
    function featuredurl($newValue = null) {
        return $this->setget('featuredurl', $newValue);
    }
	
    /**
     * @return this|string
     */
    function alt($newValue = null) {
        return $this->setget('alt', $newValue);
    }
	
    /**
     * @return this|string
     */
    function deliverycost($newValue = null) {
        return $this->setget('deliverycost', $newValue);
    }
	
    /**
     * @return this|string
     */
    function deliverytime($newValue = null) {
        return $this->setget('deliverytime', $newValue);
    }
	
    /**
     * @return $this
     */
    function __set($name, $newValuealue) {
        if (isset($this->$name)) {
            $this->$name = $newValuealue;
        }

        return $this;
    }

    /**
     * Sets field if value provided. Return value otherwise
     *
     * @return this|value
     */
    private function setget($name, $newValuealue = null) {
        if ($newValuealue !== null) {
            $this->$name = $newValuealue;
            return $this;
        }

        return $this->$name;
    }

}
