<?php
if (!function_exists('makeProgress')) {
    function makeProgress($progress, $h = 20)
    {

        return "<el-progress :text-inside='true' :stroke-width='$h' :percentage='$progress'></el-progress>";
    }
}
