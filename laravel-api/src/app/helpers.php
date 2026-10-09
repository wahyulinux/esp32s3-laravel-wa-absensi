<?php

function avatarColor(string $nama): string
{
    $colors = ['#3b82f6','#10b981','#8b5cf6','#f59e0b','#ef4444','#ec4899','#14b8a6','#f97316','#6366f1','#84cc16'];
    $hash   = 0;
    foreach (str_split($nama) as $char) {
        $hash = (ord($char) + (($hash << 5) - $hash)) & 0x7FFFFFFF;
    }
    return $colors[$hash % count($colors)];
}

function avatarInitials(string $nama): string
{
    $parts = array_filter(explode(' ', trim($nama)));
    if (count($parts) >= 2) {
        return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr(end($parts), 0, 1));
    }
    return strtoupper(mb_substr($nama, 0, 2));
}
