<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;


class StatsService {

    private $manager;

    public function __construct(ObjectManager $manager){
        $this->manager = $manager;
    }


    public function getAdsCount(){
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }


    public function getBookingsCount(){
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }


    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }


    public function getCommentsCount(){
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();

    }


    public function getBestAds(){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note DESC'
        )->setMaxResults(5)->getResult();
    }


    public function getWorstAds(){
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ASC'
        )->setMaxResults(5)->getResult();
    }
 


public function getMostTimeWorked($limit = 5){
    return $this->manager->createQuery(
        'SELECT SUM(b.amount) as total, u.firstName, u.lastName, u.picture, u.id, u.slug
        FROM App\Entity\Booking b
        JOIN b.booker u
        GROUP BY u
        ORDER BY total DESC'
    )->setMaxResults($limit)->getResult();
}


public function getLessTimeWorked($limit = 5){
    return $this->manager->createQuery(
        'SELECT SUM(b.amount) as total, u.firstName, u.lastName, u.picture, u.id
        FROM App\Entity\Booking b
        JOIN b.booker u
        GROUP BY u
        ORDER BY total ASC'
    )->setMaxResults($limit)->getResult();

    
}






}