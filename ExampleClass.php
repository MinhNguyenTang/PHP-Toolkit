<?php

class User {

    private string $id;

    public function getId()
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        $this->id = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        return $this->id;
    }

    public function getTimeStampId() 
    {
        $counter = 0;
        $counter++;
        $this->id = uniqid() . sprintf('%04d', $counter);
        return $this->id;
    }
}

$em = new User();
echo 'User TimeStamp ID: ' . $em->getTimeStampId() . PHP_EOL;
echo 'User UuID: ' . $em->getId();