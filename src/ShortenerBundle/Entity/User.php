<?php

namespace ShortenerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 */
class User implements UserInterface, \Serializable
{

    /**
     */
    protected $id;

    /**
     * @var string email
     */
    protected $email;

    /**
     * @var string username
     */
    protected $username;

    /**
     * @var string password
     */
    protected $password;

    /**
     * @var string salt
     */
    protected $salt;

    /**
     * @var ArrayCollection $userRoles
     */
    protected $userRoles;

    /**
     * @var string google_id
     */
    protected $googleId;

    /**
     * Геттер для id пользователя.
     *
     * @return string The id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Геттер для почты пользователя.
     *
     * @return string The email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Сеттер для почты пользователя.
     *
     * @param string $value The email.
     */
    public function setEmail($value)
    {
        $this->email = $value;
    }

    /**
     * Геттер для имени пользователя.
     *
     * @return string The username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Сеттер для имени пользователя.
     *
     * @param string $value The username.
     */
    public function setUsername($value)
    {
        $this->username = $value;
    }

    /**
     * Геттер для пароля.
     *
     * @return string The password.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Сеттер для пароля.
     *
     * @param string $value The password.
     */
    public function setPassword($value)
    {
        $this->password = $value;
    }

    /**
     * Геттер для соли к паролю.
     *
     * @return string The salt.
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Сеттер для соли к паролю.
     *
     * @param string $value The salt.
     */
    public function setSalt($value)
    {
        $this->salt = $value;
    }

    /**
     * Геттер для google id.
     *
     * @return string The googleId.
     */
    public function getGoogleId()
    {
        return $this->googleId;
    }

    /**
     * Сеттер для google id.
     *
     * @param string $value The googleId.
     */
    public function setGoogleId($value)
    {
        $this->googleId = $value;
    }

    /**
     * Геттер для ролей пользователя.
     *
     * @return ArrayCollection A Doctrine ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Конструктор класса User
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    /**
     * Сброс прав пользователя.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Геттер для массива ролей.
     *
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }

    /**
     * Сравнивает пользователя с другим пользователем и определяет
     * один и тот же ли это человек.
     *
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }

    /**
     * Serializes the content of the current User object
     * @return string
     */
    public function serialize()
    {
        return \json_encode(
            array($this->username, $this->password, $this->salt,
                $this->userRoles, $this->id));
    }

    /**
     * Unserializes the given string in the current User object
     * @param serialized
     */
    public function unserialize($serialized)
    {
        list($this->username, $this->password, $this->salt,
            $this->userRoles, $this->id) = \json_decode(
            $serialized);
    }
}