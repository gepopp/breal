<?php

namespace App\Enums;

enum EnergieausweissEnum
{
    case A;
    case B;
    case C;
    case D;
    case E;
    case F;




    public function getBadge(): string
    {
        return match ($this) {
            self::A => '<div class="w-6 h-6 flex items-center justify-center shrink-0 bg-green-500 text-white">A</div>',
            self::B => '<div class="w-6 h-6 flex items-center justify-center shrink-0 bg-green-300 text-white">B</div>',
            self::C => '<div class="w-6 h-6 flex items-center justify-center shrink-0 bg-red-100 text-white">C</div>',
            self::D => '<div class="w-6 h-6 flex items-center justify-center shrink-0 bg-yellow-300 text-black">D</div>',
            self::E => '<div class="w-6 h-6 flex items-center justify-center shrink-0 bg-red-300 text-black">E</div>',
            self::F => '<div class="w-6 h-6 flex items-center justify-center shrink-0 bg-red-500 text-white">F</div>',
        };
    }
}
