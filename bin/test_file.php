<?php
error_reporting(E_ALL);
require_once("entity/file.php");
$file = new file();
$d = [
    'name' => 'test',
    'actualName' => 'test.jpg',
    'size' => 10000,
    'extension' => 'jpg',
    'type' => 'image/jpg',
    'operating_system' => 'Other',
    'architecture' => 'x86',
    'sha256' => 'abcdefghijklmnopqrstuvwxyz',
    'categories' => 'kahsg,jhgsh,jagsjhsg,ajsgsja',
    'clearance' => 1,
    'information' => 'kkkkkkkkkkkkkk',
    'publisher' => 'micro',
    'publisher_link' => 'http://localhost.com',
];
$file->setName($d['name']);
$file->setIdUser(1);
                $file->setFileName($d['actualName']);
                $file->setFileSize($d['size']);
                $file->setFileType($d['type']);
                $file->setExtension($d['extension']);
                $file->setOS($d['operating_system']);
                $file->setArchitecture($d['architecture']);
                $file->setSha256($d['sha256']);
                $file->setCategories($d['categories']);
                if(isset($d['clearance']) && !empty($d['clearance'])){
                    if($d['clearance'] != 0){
                         $file->setNeedClearance(1);
                         $file->setClearanceLevel($d['clearance']);
                    }
                }
                $file->setInformation($d['information']);
                $file->setPublisher($d['publisher']);
                $file->setPublisherLink($d['publisher_link']);
                $file->setVersion("1.3.3");
                $file->save();
echo $file->getDBstatus()."\n";
echo $file->getDBerror()."\n";