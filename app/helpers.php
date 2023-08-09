<?php
if (!function_exists('makeProgress')) {
    function makeProgress($progress, $h = 20)
    {
        return "<el-progress :text-inside='true' :stroke-width='$h' :percentage='$progress'></el-progress>";
    }
}


if (!function_exists('makeProviderLogo')) {
    function makeProviderLogo($provider)
    {
        if (!$provider) return "Cadastro manual";
        $providerName = ucfirst($provider);
        return "<div class='gap-2 flex items-center'><span class='fab fa-{$provider}'/> {$providerName}</div>";
    }
}


if (!function_exists('makeBadge')) {
    function makeBadge($type, $text)
    {
        return "<span class='el-tag el-tag--$type el-tag--mini'>{$text}</span>";
    }
}


if (!function_exists('SayGoodMorning')) {
    function SayGoodMorning()
    {
        $hour = date('H');
        if ($hour >= 5 && $hour <= 12) {
            return "Bom dia";
        } else if ($hour > 12 && $hour <= 18) {
            return "Boa tarde";
        } else {
            return "Boa noite";
        }
    }
}
