-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Мар 22 2023 г., 11:17
-- Версия сервера: 10.1.48-MariaDB
-- Версия PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `test_task`
--

-- --------------------------------------------------------

--
-- Структура таблицы `files`
--

CREATE TABLE `files` (
  `id` int(222) NOT NULL,
  `uniq_name` varchar(222) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(222) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(222) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `files`
--

INSERT INTO `files` (`id`, `uniq_name`, `file_name`, `date`) VALUES
(33, 'NmqduqL0SSurEcnTY8GlPKayV1v7LD', 'file_5', '22-03-2023 02:30:12'),
(34, 'DcE74iENfEZj84kOseEXurZcIPUqFK', 'file_5', '22-03-2023 02:30:14'),
(35, 'q9qZWvOgq36Yi5i2WKbdsGJELMq56q', 'file_5', '22-03-2023 02:30:15'),
(36, 'Z71Ccmkt9xWd8g3s628TRac5AulVAX', 'file_5', '22-03-2023 02:30:16'),
(37, 'GdOZXERtt3nsGncRYw9P34iCl6tOYO', 'file_3', '22-03-2023 02:49:48'),
(38, '7eXugFRwzHoaaKq76w5VUpU6gMQVmk', 'file_3', '22-03-2023 02:50:22'),
(39, 'RUKQLM2m1Ojvy4Mii4xMW4Vq9cQ50u', 'file_3', '22-03-2023 02:54:01'),
(40, '98RUqq8jH90ujNImIkhytTDkiFoRn1', 'file_3', '22-03-2023 02:54:39'),
(41, 'Z0T0TQt5uruDJReQIptRNk5E6GxYVo', 'file_2', '22-03-2023 02:55:17'),
(42, 'ogf77EXj0TkvxZy6oV8OYXvbo3f6LY', 'file_3', '22-03-2023 03:28:10'),
(43, '9chG3rdS7SM7yZqZteZ14LOda7rlx8', 'file_3', '22-03-2023 11:15:27'),
(44, 'Vve0XMw2M9HF3m6a3oV4KjbSINrNfR', 'file_2', '22-03-2023 11:16:11');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `files`
--
ALTER TABLE `files`
  MODIFY `id` int(222) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
