-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Ноя 12 2019 г., 05:45
-- Версия сервера: 5.7.25
-- Версия PHP: 7.1.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `marlin`
--

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `dt_add` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `text` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `show_comment` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `dt_add`, `text`, `name`, `user_id`, `show_comment`) VALUES
(1, '2019-10-23 21:34:34', 'Привет!', 'Алексей', 23, 1),
(9, '2019-10-30 06:47:59', 'Пробуем', 'Алексей', 23, 1),
(10, '2019-11-02 08:36:44', 'Проверка', 'Алексей', 23, 1),
(11, '2019-11-08 06:03:37', '123', 'Иван', 24, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` text,
  `admin` varchar(256) DEFAULT NULL,
  `dt_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password_cookie_token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `avatar`, `admin`, `dt_add`, `password_cookie_token`) VALUES
(23, 'Алексей', '$2y$10$Wyct1Z.zf.kn4OZGcDKhS.QcBqnn2n436sHkWMSKzhwq6uJeQSjLq', 'alexey.moroz87@gmail.com', 'img/5dc4d96115ced.jpg', 'admin', '2019-11-06 02:41:53', '548b0a662deccd7811e31cb173bdec60'),
(26, 'Иван', '$2y$10$STDqiGwOdBoC3UeZFhdHje/sSaYdwd1nF3cxbB9Fu5vcS9rgwjhOS', 'ivan@mail.ru', 'img/no-user.jpg', '0', '2019-11-08 03:11:53', '1950fe69afc7689fde35a163b968bbd7');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
