-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Окт 21 2016 г., 18:11
-- Версия сервера: 5.7.14
-- Версия PHP: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `smarthome`
--

-- --------------------------------------------------------

--
-- Структура таблицы `devices`
--

CREATE TABLE `devices` (
  `id` int(11) NOT NULL,
  `name` varchar(20) NOT NULL,
  `info` varchar(40) DEFAULT 'Датчик ...'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список зарегистрирвоанных устройств';

--
-- Дамп данных таблицы `devices`
--

INSERT INTO `devices` (`id`, `name`, `info`) VALUES
  (1, 'rpi_shower', 'RaspberryPi в душевой');

-- --------------------------------------------------------

--
-- Структура таблицы `values_rpi_shower`
--

CREATE TABLE `values_rpi_shower` (
  `dt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wetness` tinyint(4) DEFAULT NULL,
  `gpu` float DEFAULT NULL,
  `cpu` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devices_id_uindex` (`id`),
  ADD UNIQUE KEY `devices_name_uindex` (`name`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `devices`
--
ALTER TABLE `devices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
