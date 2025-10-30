<?php
namespace App\Enum;

enum Providers: string
{
    case Unity = 'unity';
    case Unreal = 'unreal';
    case Godot = 'godot';
    case CryEngine = 'cryengine';
    case Source = 'source';
    case Custom = 'custom';
}
