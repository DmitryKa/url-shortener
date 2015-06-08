<?php

namespace Ttb\Entity;

class Url
{
    protected $full_url;
    protected $short_url;

    /**
     * @return mixed
     */
    public function getFullUrl() {
        return $this->full_url;
    }

    /**
     * @param mixed $full_url
     */
    public function setFullUrl($full_url) {
        $this->full_url = $full_url;
    }

    /**
     * @return mixed
     */
    public function getShortUrl() {
        return $this->short_url;
    }

    /**
     * @param mixed $short_url
     */
    public function setShortUrl($short_url) {
        $this->short_url = $short_url;
    }
}