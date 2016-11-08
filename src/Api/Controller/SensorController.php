<?php
/**
 * Created by PhpStorm.
 * User: kukareko
 * Date: 19.10.16
 * Time: 19:01
 */

namespace Api\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\DBAL\Connection;

class SensorController
{
    /**
     * Регистрация значения датчика от RaspberryPi
     *
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     *
     * @todo Сделать проверку на наличие девайса и корректность пришедших данных
     */
    public function reception(Application $app, Request $request, $deviceId)
    {

        // insert reception data
        $postData = $request->request->all();
        $db = $app['db'];
        /** @var  Connection $db */
        $res = $db->insert("values_$deviceId", $postData);
        return  $app->json($res);

        //$sql = "SELECT * FROM g2krb_content WHERE id = ?";
        //$post = $app['db']->fetchAssoc($sql, array(25));

    }

    /**
     * Получение списка последних значений зарегистрированного устройства
     *
     * @param Application $app
     * @param $device
     * @param $limit
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function last(Application $app, $device, $limit, $timestamp)
    {
        // Приведение timestamp к форматированной строке для сравнения в БД
        $dt = (new \DateTime())->setTimestamp($timestamp/1000)->format("Y-m-d H:i:s");
        // Получим актуальные данные из БД
        $sql = "SELECT * FROM (SELECT * FROM `values_$device` WHERE dt>'$dt' ORDER BY dt DESC LIMIT $limit) 
                as sub ORDER BY dt ASC";
        $rows = $app['db']->fetchAll($sql);
        // Подготовим результирующий объект
        $result = new \stdClass();
        //$result->debug=array();

        foreach ($rows as $arr ) {
            $dt = (new \DateTime($arr['dt']))->format("U000");
            //$dt2 = (new \DateTime($arr['dt']))->getTimestamp()*1000;
            //array_push($result->debug, array($arr["dt"],$dt));
            foreach ($arr as $sensor => $value) {
                if (!is_array(@$result->$sensor))
                    $result->$sensor = array();
                array_push($result->$sensor, array(intval($dt), floatval($value)));
            }
        }
        return  $app->json($result);
    }

    /**
     * Получение описания и структуры данных зарегистрированного устройства
     *
     * @param Application $app
     * @param $name
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function struct(Application $app, $name){

        // получим описание из таблицы устройств
        $sql = "SELECT * FROM devices WHERE name = '$name'";
        $statement = $app['db']->executeQuery($sql);
        $device = (object)$statement->fetch();

        // получим структуру из таблицы описания
        $sql = "describe `values_$name`";
        $describe = $app['db']->fetchAll($sql);
        $device->struct = [];
        foreach ($describe as $sensor)
            if ($sensor["Field"]!="dt")
                array_push($device->struct, array('name'=>$sensor['Field'],'type'=>$sensor['Type']));

        return  $app->json($device);
    }


}