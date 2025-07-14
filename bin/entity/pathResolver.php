<?php
class pathResolver{
    public static function root(): string{
        return dirname(__FILE__, 3);
    }
}