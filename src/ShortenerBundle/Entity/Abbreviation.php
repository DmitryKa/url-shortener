<?php

namespace ShortenerBundle\Entity;

class Abbreviation
{

    /**
     */
    protected $id;

    /**
     * @var string key
     */
    protected $clue;

    /**
     * @var string full url
     */
    protected $fullUrl;

    /**
     * @var string user id
     */
    protected $userId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClue()
    {
        return $this->clue;
    }

    /**
     * @param string $clue
     */
    public function setClue($clue)
    {
        $this->clue = $clue;
    }

    /**
     * @return string
     */
    public function getFullUrl()
    {
        return $this->fullUrl;
    }

    /**
     * @param string $fullUrl
     */
    public function setFullUrl($fullUrl)
    {
        $this->fullUrl = $fullUrl;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }
}