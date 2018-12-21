<?php
/**
 * Created by PhpStorm.
 * User: a.dubrovskii
 * Date: 21.12.2018
 * Time: 11:24
 */


namespace com\earningmillion;

class AdvertiserCabinetParser implements IGrabber
{

    private $connection_data = array(
        "web_username" => "ac@alfaleads.ru",
        "web_password" => "RFpqFj7V3GF554"
    );

    function entryPoint()
    {

        $ch = $this->setConnection($this->connection_data);

        $this->getData($ch);

    }


    //Логинимся
    function setConnection($connection_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.earningmillion.com/login");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// просто отключаем проверку сертификата
        curl_setopt($ch, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt'); // сохранять куки в файл
        curl_setopt($ch, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);

        //Данные, отправляемые формой.
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            http_build_query(
                array(
                    'type' => '1',
                    'login' => $connection_data['web_username'],
                    'passe' => $connection_data['web_password'],
                )
            )
        );
        return $ch;
    }

    //Парсим кабинет веба за прошлый день.Находим два числа : Количество регистраций, Количество первых депозитов
    //Числа записываются в ассоциативный массив $singup_deposit и возвращаются функцией
    function getData($ch)
    {
        curl_setopt($ch, CURLOPT_URL, "http://www.earningmillion.com/editeur/report.php");
        curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query(array(
            'idx' => '151',
            'datedeb' => '7-12-2018',
            'datefin' => '7-12-2018',
        )));
        $server_page_response = curl_exec($ch);
        $document = \phpQuery::newDocument($server_page_response);

        $hentry = $document->find('tbody.pyjamaDotted tr:last');


        $counter = 0;

        $signup_deposit = array();

        foreach ($hentry as $item) {
            print_r($item);

            echo "<br>";

            if($counter == 1){
                $signup_deposit['signup'] = $item->textContent;
            }
            if($counter == 3){
                $signup_deposit['deposit'] = $item->textContent;
            }
            $counter++;
        }

        print_r($signup_deposit);

        return $signup_deposit;


    }
}