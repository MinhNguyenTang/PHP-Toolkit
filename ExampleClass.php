<?php

class User {

    private string $id;
    private string $token;

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

    public function getToken($alpha_length)
    {
        $alpha = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $alpha_length = strlen($alpha);
        $random_string = '';

        for ($i = 0; $i < $alpha_length; $i++) {
            $random_index = random_int(0, $alpha_length - 1);
            $this->token = $random_string .= $alpha[$random_index];
        }

        return $this->token;
    }
}

$em = new User();
echo 'User TimeStamp ID: ' . $em->getTimeStampId() . PHP_EOL;
echo 'User UuID: ' . $em->getId() . PHP_EOL;
echo 'Token : ' . $em->getToken(10);