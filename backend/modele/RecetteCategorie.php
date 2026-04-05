<?php
namespace backend\modele;

enum RecetteCategorie
{
    case DESSERT;
    case PLATPRINCIPAL;
    case ENTREE;

    public static function fromName(string $name): ?RecetteCategorie
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status;
            }
        }

        return null;
    }
}
