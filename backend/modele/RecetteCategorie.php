<?php
namespace api\Modele\Joueur;

enum RecetteCategorie
{
    case DESSERT;

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
