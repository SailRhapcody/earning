<?php
/**
 * Created by PhpStorm.
 * User: a.dubrovskii
 * Date: 21.12.2018
 * Time: 11:22
 */

namespace com\earningmillion;

interface IGrabber
{
    function entryPoint();
    function setConnection($connection_data);
    function getData($b);
}