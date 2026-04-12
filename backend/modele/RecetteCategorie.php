<?php
namespace backend\modele;

enum RecetteCategorie
{
    case DESSERT;
    case PLAT;
    case ENTREE;

    public static function fromName(string $name): ?RecetteCategorie
    {
        foreach (self::cases() as $status) {
            if( strtolower(trim($name)) === strtolower(trim($status->name)) ){
                return $status;
            }
        }

        return null;
    }
}
