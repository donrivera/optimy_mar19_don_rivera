<?php

class Comment
{
    protected $id, $body, $createdAt, $newsId;

    // dynamically set properties
    public function __set($name, $value)
    {
        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    // dynamically get properties
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
    }
}
/**
 *Comments
 *Define getter and setter methods for each property
 */