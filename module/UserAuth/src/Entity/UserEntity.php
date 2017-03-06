<?php
namespace UserAuth\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class UserEntity
{
    // User status constants.
    const STATUS_NEW        = 1; // New user.
    const STATUS_APPROVED   = 2; // Approved user.
    const STATUS_BLOCKED    = 3; // Blockes user.

    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @ORM\Column(name="email")
     */
    protected $email;

    /**
     * @ORM\Column(name="user_name")
     */
    protected $userName;

    /**
     * @ORM\Column(name="password")
     */
    protected $password;

    /**
     * @ORM\Column(name="status")
     */
    protected $status;

    /**
     * @ORM\Column(name="user_created")
     */
    protected $userCreated;

    /**
     * @ORM\Column(name="user_updated")
     */
    protected $userUpdated;
    
    /**
     * @ORM\Column(name="pwd_created")
     */
    protected $pwdCreated;
    
    /**
     * @ORM\Column(name="pwd_updated")
     */
    protected $pwdUpdated;
    
      /**
     * @ORM\Column(name="pwd_reset_token")
     */
    protected $passwordResetToken;

    /**
     * @ORM\Column(name="pwd_reset_token_creation_date")
     */
    protected $passwordResetTokenCreationDate;

    /**
     * Returns user ID.
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets user ID.
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns email.
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets email.
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Returns user name.
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Sets user name.
     * @param string $userName
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;
    }

    /**
     * Returns status.
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList()
    {
        return [
            self::STATUS_NEW        => 'New',
            self::STATUS_APPROVED   => 'Approved',
            self::STAUS_BLOCKED     => 'Blocked',
        ];
    }

    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];

            return 'Unknown';
    }

    /**
     * Sets status.
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Returns password.
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets password.
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the date of user creation.
     * @return string
     */
    public function getUserCreated()
    {
        return $this->userCreated;
    }

    /**
     * Sets the date when this user was created.
     * @param string $dateCreated
     */
    public function setUserCreated($userCreated)
    {
        $this->userCreated = $userCreated;
    }

    /**
     * Returns the date of user creation.
     * @return string
     */
    public function getUserUpdated()
    {
        return $this->userUpdated;
    }
    
    /**
     * Sets the date when this user account was changed.
     * @param string $dateUpdated
     */
    public function setUserUpdated($userUpdated)
    {
        $this->userUpdated = $userUpdated;
    }
    

    /**
     * Returns the date of pwd creation.
     * @return string
     */
    public function getPwdCreated()
    {
        return $this->pwdCreated;
    }
    
    /**
     * Sets the date when this pwd was created.
     * @param string $dateCreated
     */
    public function setPwdCreated($pwdCreated)
    {
        $this->pwdCreated = $pwdCreated;
    }
    
    /**
     * Returns the date of pwd creation.
     * @return string
     */
    public function getPwdUpdated()
    {
        return $this->pwdUpdated;
    }
    
    /**
     * Sets the date when this pwd account was changed.
     * @param string $dateUpdated
     */
    public function setPwdUpdated($pwdUpdated)
    {
        $this->pwdUpdated = $pwdUpdated;
    }
    
    /**
     * Returns password reset token.
     * @return string
     */
    public function getResetPasswordToken()
    {
        return $this->passwordResetToken;
    }

    /**
     * Sets password reset token.
     * @param string $token
     */
    public function setPasswordResetToken($token)
    {
        $this->passwordResetToken = $token;
    }

    /**
     * Returns password reset token's creation date.
     * @return string
     */
    public function getPasswordResetTokenCreationDate()
    {
        return $this->passwordResetTokenCreationDate;
    }

    /**
     * Sets password reset token's creation date.
     * @param string $date
     */
    public function setPasswordResetTokenCreationDate($date)
    {
        $this->passwordResetTokenCreationDate = $date;
    }
}