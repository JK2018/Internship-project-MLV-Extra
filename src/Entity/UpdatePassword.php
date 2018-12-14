<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert; //in order to put constraints on feilds and make sure they are valid

/**
 * *********************************************************************************************
 * *********************************************************************************************
 * 
 * This class is not intended to be mapped with the ORM and should not be migrated to database
 * , it is no longer an entity.
 * it's only purpose is to be a "fake class" so that we can benefit of symfonys form validation.
 * 
 * *********************************************************************************************
 * *********************************************************************************************
 */






class UpdatePassword
{
 


    private $oldPassword;

    /**
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit faire au moins 8 caractères!")
     */
    private $newPassword;

    /**
     * @Assert\EqualTo(propertyPath="newPassword", message="La reconfirmation de votre nouveau mot de passe a échoué. Vérifiez que le nouveau mot de passe et sa confirmation soient bien identiques!")
     */
    private $confirmPassword;





    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(string $newPassword): self
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }
}
