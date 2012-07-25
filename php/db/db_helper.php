<?php
/**
 * unsets the system Variables form an given Array
 *
 * @author Kevin Siegerth
 */
function sys_unsetSysVariables ($array)
{
    unset($array['table']);
    unset($array['uid']);
    unset($array['pid']);
    unset($array['action']);
    return $array;
} 