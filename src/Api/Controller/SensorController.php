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
        $res = $app['db']->insert("values_$deviceId", $postData);
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
        //SELECT * FROM `values_rpi_shower` WHERE dt>STR_TO_DATE('2016-11-06 00:00:00', '%Y-%m-%d %H:%i:%s')
        //SELECT * FROM `values_rpi_shower` WHERE dt>FROM_UNIXTIME(1478379600000/1000)

        //rpi_shower
        $sql = "SELECT * FROM (SELECT * FROM `values_$device` WHERE dt>FROM_UNIXTIME($timestamp) ORDER BY dt DESC LIMIT $limit) 
                as sub ORDER BY dt ASC";
        $rows = $app['db']->fetchAll($sql);
        $result = new \stdClass();
        foreach ($rows as $arr ) {
            $dt = (new \DateTime($arr["dt"]))->format("U000");
            foreach ($arr as $sensor => $value) {
                $result->$sensor[] = array(intval($dt), floatval($value));
            }
        }
        //echo "<pre>";print_r($result);
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