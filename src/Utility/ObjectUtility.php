<?php


namespace App\Utility;


use Doctrine\Persistence\ManagerRegistry;

class ObjectUtility
{
    public static function handleUpdate(ManagerRegistry $managerRegistry, $object, $isDelete = false)
    {
        $manager = $managerRegistry->getManager();
        if ($isDelete) {
            $manager->remove($object);
        } else {
            $manager->persist($object);
        }
        $manager->flush();
    }
}