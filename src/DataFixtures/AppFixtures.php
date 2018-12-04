<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('FR-fr');
        for ($i=1; $i<=30; $i++){

        $title = $faker->sentence();
        $intro = $faker->paragraph(2);
        $content = '<p>'. join('</p><p>', $faker->paragraphs(5)) . '</p>';
        $coverImage = $faker->imageUrl(1000, 350);



        $ad = new Ad();
        $ad -> setTitle($title)
            ->setHoursPerDay(\mt_rand(1, 6))
            ->setIntroduction($intro)
            ->setContent($content)
            ->setCoverImage($coverImage)
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
