<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    
    private $encoder;

    //constructor so we can user $encoder
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');

        /////// USERS //////
        $users = [];
        $genders = ['male', 'female']; //in order to get logical pictures and names acording to ones gender.

        for($i=1; $i<=10; $i++){
            $user = new User();
            $gender = $faker->randomElement($genders);
            $picture='https://randomuser.me/api/portraits/'; // random avatar pics
            $picture = $picture . ($gender == 'male' ? 'men/' : 'women/') . mt_rand(1, 99) . '.jpg';

            $hash = $this->encoder->encodePassword($user, 'password');
            
            $user -> setFirstName($faker->firstname($gender))
                    -> setLastName($faker->lastname)
                    -> setEmail($faker->email)
                    -> setHash($hash)
                    -> setIntroduction($faker->sentence())
                    -> setTel($faker->e164PhoneNumber)
                    ->setPicture($picture)
                    -> setText('RANDOM TEXT HERE');
                    

            $manager->persist($user);
            $users[]=$user;
        }


        ////// ADS //////
        for ($i=1; $i<=30; $i++){

        $title = $faker->sentence();
        $intro = $faker->paragraph(2);
        $content = '<p>'. join('</p><p>', $faker->paragraphs(5)) . '</p>';
        $coverImage = $faker->imageUrl(1000, 350);

        $user = $users[mt_rand(0, count($users)-1)];

        $ad = new Ad();
        $ad -> setTitle($title)
            ->setHoursPerDay(\mt_rand(1, 6))
            ->setIntroduction($intro)
            ->setContent($content)
            ->setCoverImage($coverImage)
            ->setAuthor($user)
            ->setDaysPerMission(\mt_rand(1,6));

            for($j=1; $j<= mt_rand(2,5); $j++){
                $image = new Image();

                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAd($ad);
                    $manager->persist($image);
            }



        $manager->persist($ad);
    }

        $manager->flush();
    }
}
