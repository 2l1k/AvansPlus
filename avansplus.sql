-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 13 2018 г., 07:56
-- Версия сервера: 5.6.38-log
-- Версия PHP: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `avansplus`
--

-- --------------------------------------------------------

--
-- Структура таблицы `banks`
--

CREATE TABLE `banks` (
  `id` int(10) NOT NULL,
  `name` varchar(70) DEFAULT NULL,
  `bik` varchar(8) DEFAULT NULL COMMENT 'БИК',
  `bin` bigint(12) DEFAULT NULL COMMENT 'БИН',
  `is_active` tinyint(1) NOT NULL COMMENT 'Статус отображения'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Названия банков';

-- --------------------------------------------------------

--
-- Структура таблицы `borrowers`
--

CREATE TABLE `borrowers` (
  `id` int(10) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL COMMENT 'Имя заёмщика',
  `lastname` varchar(50) DEFAULT NULL,
  `fathername` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone_number` bigint(12) NOT NULL COMMENT 'Номер телефона',
  `DOB` date DEFAULT NULL COMMENT 'Дата рождения',
  `place_birth` varchar(50) DEFAULT NULL COMMENT 'Место рождения',
  `maiden_name` varchar(50) DEFAULT NULL COMMENT 'Девичья фамилия матери',
  `dependents_number` int(2) DEFAULT NULL COMMENT 'Число иждивенцев',
  `is_banned` tinyint(1) DEFAULT NULL COMMENT 'Забанен',
  `is_registered` tinyint(1) DEFAULT NULL COMMENT 'Зарегистрирован',
  `marital_status_id` int(10) NOT NULL COMMENT 'Семейное положение',
  `gender_id` int(2) NOT NULL COMMENT 'Пол',
  `borrower_status_id` int(2) NOT NULL COMMENT 'Статус заёмщика',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `password` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Заёмщики';

--
-- Дамп данных таблицы `borrowers`
--

INSERT INTO `borrowers` (`id`, `firstname`, `lastname`, `fathername`, `email`, `phone_number`, `DOB`, `place_birth`, `maiden_name`, `dependents_number`, `is_banned`, `is_registered`, `marital_status_id`, `gender_id`, `borrower_status_id`, `created_at`, `updated_at`, `password`) VALUES
(24, 'ОЛЕСЯ ', 'КОНОНЕНКО', 'ПАВЛОВНА', 'alex6ndryakovlev@ya2.ru', 77053902178, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '2018-07-06 17:48:56', '2018-07-06 17:48:56', '$2y$10$zafs2g44e6POWKk0yQ88keVz8fLosCDDToUgu37uCYNony9ZIlGKO'),
(25, 'Александр', 'Яковлев', 'Анатольевич', 'alex6ndryakovlev@ya.ru', 77053902179, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, 0, '2018-07-09 03:46:00', '2018-07-09 10:35:39', '$2y$10$HRy07ID6czX3VnA8dtyhQua8jvHa.51IX/aMXit/odC4w1WuJpUfu');

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_addresses`
--

CREATE TABLE `borrower_addresses` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `ra_city_id` int(10) NOT NULL COMMENT 'Город',
  `ra_street_name` varchar(255) DEFAULT NULL COMMENT 'Улица',
  `ra_house_number` varchar(10) DEFAULT NULL COMMENT 'Номер дома',
  `ra_apartment_number` varchar(10) DEFAULT NULL,
  `ra_postcode` varchar(50) DEFAULT NULL COMMENT 'Почтовый индекс',
  `ha_city_id` int(10) NOT NULL,
  `ha_street_name` varchar(255) DEFAULT NULL,
  `ha_house_number` varchar(10) DEFAULT NULL,
  `ha_apartment_number` varchar(10) DEFAULT NULL,
  `ha_postcode` varchar(50) DEFAULT NULL,
  `home_phone_number` bigint(14) DEFAULT NULL COMMENT 'Домашний телефон',
  `time_stay_option_id` int(2) NOT NULL COMMENT 'Время проживания по текущему адресу'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Адрес регистрации и проживания';

--
-- Дамп данных таблицы `borrower_addresses`
--

INSERT INTO `borrower_addresses` (`id`, `borrower_id`, `ra_city_id`, `ra_street_name`, `ra_house_number`, `ra_apartment_number`, `ra_postcode`, `ha_city_id`, `ha_street_name`, `ha_house_number`, `ha_apartment_number`, `ha_postcode`, `home_phone_number`, `time_stay_option_id`) VALUES
(1, 24, 0, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, 0),
(2, 25, 1, 'Улица1', 'Номер дома', 'Квартира 1', '', 2, 'Улица2', '222', '333', '', NULL, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_address_documents`
--

CREATE TABLE `borrower_address_documents` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `images` varchar(255) DEFAULT NULL COMMENT 'Загруженная адресная справка',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Комментарии менеджера по адресной справке',
  `document_check_status_id` tinyint(4) NOT NULL COMMENT 'Статус проверки'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `borrower_address_documents`
--

INSERT INTO `borrower_address_documents` (`id`, `borrower_id`, `images`, `comment`, `document_check_status_id`) VALUES
(1, 24, NULL, NULL, 1),
(2, 25, '[\"\"]', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_bank_accounts`
--

CREATE TABLE `borrower_bank_accounts` (
  `id` int(10) NOT NULL COMMENT 'Банковские карты заёмщика',
  `borrower_id` int(10) NOT NULL,
  `number` varchar(20) DEFAULT NULL COMMENT 'Номер банковского счета',
  `bank_id` int(10) DEFAULT NULL COMMENT 'Банк',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Банковские карты заёмщика, привязанные через систему processing';

--
-- Дамп данных таблицы `borrower_bank_accounts`
--

INSERT INTO `borrower_bank_accounts` (`id`, `borrower_id`, `number`, `bank_id`, `created_at`, `updated_at`) VALUES
(1, 24, '11111111111111111111', NULL, '2018-07-06 17:48:56', '2018-07-06 17:48:56'),
(2, 25, '11111111111111111111', NULL, '2018-07-09 03:46:00', '2018-07-09 03:46:00');

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_bank_cards`
--

CREATE TABLE `borrower_bank_cards` (
  `id` int(10) NOT NULL COMMENT 'Банковские карты заёмщика',
  `borrower_id` int(10) NOT NULL,
  `processing_card_id` int(10) DEFAULT NULL COMMENT 'Номер карты присвоенный при регистрации',
  `processing_user_id` int(10) DEFAULT NULL COMMENT 'Идентификатор пользователя в системе CNP',
  `pan_masked` varchar(16) DEFAULT NULL COMMENT 'Маскированный номер карты',
  `card_holder` varchar(50) DEFAULT NULL COMMENT 'Имя держателя карты',
  `is_attached` tinyint(1) NOT NULL COMMENT 'Статус привязки',
  `bank_id` int(10) NOT NULL COMMENT 'Отношение к банку',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Банковские карты заёмщика, привязанные через систему processing';

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_employments`
--

CREATE TABLE `borrower_employments` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `work_place` varchar(255) DEFAULT NULL COMMENT 'Место работы',
  `salary` decimal(10,2) DEFAULT NULL COMMENT 'Размер заработной платы',
  `working_position` varchar(255) NOT NULL COMMENT 'Рабочая должность',
  `education_option_id` int(2) DEFAULT NULL COMMENT 'Образование',
  `employment_period_option_id` int(2) DEFAULT NULL COMMENT 'Занятость',
  `monthly_income_option_id` int(2) DEFAULT NULL COMMENT 'Ежемесячный доход',
  `work_experience_option_id` int(2) DEFAULT NULL COMMENT 'Общий стаж работы',
  `home_ownership_period_option_id` int(2) DEFAULT NULL COMMENT 'Время владения недвижимостью',
  `loan_expenditure` decimal(10,0) NOT NULL COMMENT 'Ежемесячные расходы по кредитным обязательствам',
  `car_ownership` tinyint(1) NOT NULL COMMENT 'Владение автомобилем',
  `organization_name` varchar(100) DEFAULT NULL COMMENT 'Название организации',
  `work_phone_number` bigint(14) DEFAULT NULL COMMENT 'Рабочий телефон',
  `salary_obtaining_method_id` int(2) NOT NULL COMMENT 'Способ получения зарплаты'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Трудоустройство заёмщика';

--
-- Дамп данных таблицы `borrower_employments`
--

INSERT INTO `borrower_employments` (`id`, `borrower_id`, `work_place`, `salary`, `working_position`, `education_option_id`, `employment_period_option_id`, `monthly_income_option_id`, `work_experience_option_id`, `home_ownership_period_option_id`, `loan_expenditure`, `car_ownership`, `organization_name`, `work_phone_number`, `salary_obtaining_method_id`) VALUES
(1, 24, 'test', '99999999.99', 'test', NULL, NULL, NULL, NULL, NULL, '0', 0, NULL, NULL, 1),
(2, 25, 'test', '1000000.00', 'test', NULL, NULL, NULL, NULL, NULL, '0', 0, NULL, NULL, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_existing_loans`
--

CREATE TABLE `borrower_existing_loans` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `bank_id` int(10) NOT NULL COMMENT 'Банк',
  `loan_type_id` int(3) NOT NULL COMMENT 'Тип займа',
  `date` date DEFAULT NULL COMMENT 'Дата получения займа',
  `currency_id` int(2) NOT NULL COMMENT 'Валюта',
  `sum` decimal(10,0) DEFAULT NULL COMMENT 'Сумма или кредитный лимит по займу'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Имеющиеся кредиты в других банках';

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_identification_cards`
--

CREATE TABLE `borrower_identification_cards` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `issue_date` date DEFAULT NULL COMMENT 'Дата выдачи',
  `expiration_date` date DEFAULT NULL COMMENT 'Дата истечения срока действия ',
  `IIN` bigint(12) DEFAULT NULL COMMENT 'ИИН',
  `number` int(9) DEFAULT NULL COMMENT 'Номер удостоверения',
  `issued_authority_id` int(2) NOT NULL COMMENT 'Кем выдано'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Трудоустройство заёмщика';

--
-- Дамп данных таблицы `borrower_identification_cards`
--

INSERT INTO `borrower_identification_cards` (`id`, `borrower_id`, `issue_date`, `expiration_date`, `IIN`, `number`, `issued_authority_id`) VALUES
(7, 24, NULL, NULL, 810305400330, NULL, 0),
(8, 25, '2018-07-10', '2018-07-11', 930426301494, 12345, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_id_card_documents`
--

CREATE TABLE `borrower_id_card_documents` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `images` varchar(255) DEFAULT NULL COMMENT 'Загруженное изображени',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Комментарий менеджера',
  `document_check_status_id` tinyint(4) NOT NULL COMMENT ' \r\n '
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Скан уд. личности заёмщика';

--
-- Дамп данных таблицы `borrower_id_card_documents`
--

INSERT INTO `borrower_id_card_documents` (`id`, `borrower_id`, `images`, `comment`, `document_check_status_id`) VALUES
(1, 24, NULL, NULL, 1),
(2, 25, '[\"images\\/uploads\\/account\\/25\\/documents\\/15311083461634_49678deb1134e9ed1fc504570f44fcfb.jpg\"]', '', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_loans`
--

CREATE TABLE `borrower_loans` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `sum` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма займа',
  `interest_rate` decimal(4,4) NOT NULL COMMENT 'Процентная ставка.',
  `reward_sum` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма вознаграждения',
  `fine_sum` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма начисленного пени',
  `paid_sum` decimal(20,0) DEFAULT NULL COMMENT 'Погашенная сумма',
  `fine_interest_rate` decimal(4,4) NOT NULL COMMENT 'Процентная ставка при просрочке',
  `dealy_days` int(5) NOT NULL DEFAULT '0' COMMENT 'Кол-во дней просрочки',
  `penalty_sum` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма начисленного единовременного штрафа (МРП)',
  `notary_sum` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма за нотариальную подпись',
  `judgment_sum` decimal(7,0) NOT NULL DEFAULT '0' COMMENT 'Сумма за судебные услуги',
  `duration_agreement` int(3) NOT NULL COMMENT 'Срок, на который оформляется займ\r\n',
  `duration_actual` int(4) NOT NULL COMMENT 'Действующий срок займа',
  `issue_date` datetime DEFAULT NULL COMMENT 'Дата выдачи займа',
  `expiration_date` datetime DEFAULT NULL COMMENT 'Дата истечения срока договора',
  `dialing_status_id` int(2) DEFAULT NULL COMMENT 'Последний статус обзвона',
  `loan_status_id` int(2) NOT NULL DEFAULT '0' COMMENT 'Текущее состояние займа',
  `loan_status_category_id` int(2) NOT NULL COMMENT 'Статус заявки',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Является ли займ действующим',
  `created_at` datetime DEFAULT NULL COMMENT 'Дата формирования заявки',
  `updated_at` datetime DEFAULT NULL COMMENT 'Дата обновления информации в заявке',
  `closing_date` datetime DEFAULT NULL COMMENT 'Дата закрытия',
  `counteroffer_sum` decimal(7,2) NOT NULL DEFAULT '0.00' COMMENT 'Сумма встречного предложения',
  `counteroffer_duration_agreement` int(3) NOT NULL DEFAULT '0' COMMENT 'Срок займа встречного предложения',
  `extension_date` datetime DEFAULT NULL COMMENT 'Дата продления'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Займы клиента';

--
-- Дамп данных таблицы `borrower_loans`
--

INSERT INTO `borrower_loans` (`id`, `borrower_id`, `sum`, `interest_rate`, `reward_sum`, `fine_sum`, `paid_sum`, `fine_interest_rate`, `dealy_days`, `penalty_sum`, `notary_sum`, `judgment_sum`, `duration_agreement`, `duration_actual`, `issue_date`, `expiration_date`, `dialing_status_id`, `loan_status_id`, `loan_status_category_id`, `is_active`, `created_at`, `updated_at`, `closing_date`, `counteroffer_sum`, `counteroffer_duration_agreement`, `extension_date`) VALUES
(7, 24, '10000.00', '0.0150', '750.00', '0.00', '0', '0.0150', 0, '0.00', '0.00', '0', 30, 0, '2018-07-06 17:48:56', '2018-08-05 17:48:56', NULL, 12, 2, 1, '2018-07-06 17:48:56', '2018-07-11 09:12:38', NULL, '10000.00', 30, NULL),
(8, 25, '10000.00', '0.0150', '750.00', '0.00', '85015', '0.0150', 0, '0.00', '0.00', '0', 30, 0, '2018-07-10 00:00:00', '2018-08-09 00:00:00', 0, 11, 3, 1, '2018-07-09 03:46:00', '2018-07-11 09:16:26', NULL, '10000.00', 30, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_loan_agreement_documents`
--

CREATE TABLE `borrower_loan_agreement_documents` (
  `id` int(10) NOT NULL,
  `file_paths` varchar(255) DEFAULT NULL COMMENT 'Загруженный документ',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Комментарий менеджера',
  `borrower_loan_id` int(10) NOT NULL COMMENT 'ID займа',
  `document_check_status_id` tinyint(4) NOT NULL COMMENT 'Статус проверки договора',
  `pledge_agreement_file_path` varchar(255) DEFAULT NULL COMMENT 'Сформированный документ договор-залога',
  `pledge_ticket_file_path` int(10) DEFAULT NULL COMMENT 'Залоговый билет'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Скан уд. личности заёмщика';

--
-- Дамп данных таблицы `borrower_loan_agreement_documents`
--

INSERT INTO `borrower_loan_agreement_documents` (`id`, `file_paths`, `comment`, `borrower_loan_id`, `document_check_status_id`, `pledge_agreement_file_path`, `pledge_ticket_file_path`) VALUES
(1, NULL, NULL, 7, 1, NULL, NULL),
(2, '[\"images\\/uploads\\/account\\/25\\/documents\\/15311325955488_dc01b5078e680702a900e79441d833fc.jpg\"]', 'Криво загружен', 8, 2, 'account/25/documents/8_agreement.pdf', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_pension_documents`
--

CREATE TABLE `borrower_pension_documents` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL,
  `images` varchar(225) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL COMMENT 'Комментарий менеджера по загрузке документа',
  `document_check_status_id` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `borrower_pension_documents`
--

INSERT INTO `borrower_pension_documents` (`id`, `borrower_id`, `images`, `comment`, `document_check_status_id`) VALUES
(1, 19, '[\"\"]', '', 1),
(6, 24, NULL, NULL, 1),
(7, 25, '[\"\"]', '', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_relative_contacts`
--

CREATE TABLE `borrower_relative_contacts` (
  `id` int(10) NOT NULL COMMENT 'Контакты друзей и родсвтенников',
  `borrower_id` int(10) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL COMMENT 'ФИО',
  `phone_number` bigint(12) DEFAULT NULL,
  `who_is` varchar(255) DEFAULT NULL COMMENT 'Кем приходится'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Дополнительные контакты';

-- --------------------------------------------------------

--
-- Структура таблицы `borrower_statuses`
--

CREATE TABLE `borrower_statuses` (
  `id` int(2) NOT NULL,
  `text` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Статусы заёмщика';

-- --------------------------------------------------------

--
-- Структура таблицы `cities`
--

CREATE TABLE `cities` (
  `id` int(10) NOT NULL,
  `name` varchar(20) DEFAULT NULL COMMENT 'Название города',
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Города';

--
-- Дамп данных таблицы `cities`
--

INSERT INTO `cities` (`id`, `name`, `is_active`) VALUES
(1, 'Алматы', NULL),
(2, 'Астана', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `companies`
--

CREATE TABLE `companies` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `companies`
--

INSERT INTO `companies` (`id`, `title`, `address`, `phone`, `created_at`, `updated_at`) VALUES
(1, 'Wolf-Rogahn', '31921 Cassidy Spur Suite 374', '542-302-9602 x5998', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(2, 'Dach, Bogan and Rohan', '40032 Willy Junction', '731-599-9388', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(3, 'Sipes PLC', '538 Noemy Prairie Suite 338', '+1-784-895-8739', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(4, 'Cummings, Roob and Kuvalis', '770 Wilhelmine Walks', '+1-712-485-5800', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(5, 'Williamson, Hoeger and Cruickshank', '402 Thompson Cape Apt. 693', '(653) 577-4857 x313', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(6, 'Schmidt, Hilll and Wiza', '6355 Jaquan Key', '760-349-3378', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(7, 'Rippin Ltd', '810 Kelsie Neck', '+19297969668', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(8, 'Morissette, Gislason and Rutherford', '3933 Reichert Port Suite 036', '324.271.5188', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(9, 'Price-Champlin', '329 Hagenes Meadows', '1-413-519-8993 x138', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(10, 'Upton Group', '13813 Sterling Glen Apt. 790', '1-792-255-4640 x67676', '2018-05-29 04:15:01', '2018-05-29 04:15:01');

-- --------------------------------------------------------

--
-- Структура таблицы `company_contact`
--

CREATE TABLE `company_contact` (
  `id` int(10) UNSIGNED NOT NULL,
  `company_id` int(10) UNSIGNED NOT NULL,
  `contact_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `company_contact`
--

INSERT INTO `company_contact` (`id`, `company_id`, `contact_id`, `created_at`, `updated_at`) VALUES
(1, 3, 4, NULL, NULL),
(2, 9, 5, NULL, NULL),
(3, 9, 16, NULL, NULL),
(4, 5, 11, NULL, NULL),
(5, 4, 5, NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `contacts`
--

CREATE TABLE `contacts` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `contacts`
--

INSERT INTO `contacts` (`id`, `firstName`, `lastName`, `photo`, `birthday`, `phone`, `address`, `country_id`, `comment`, `created_at`, `updated_at`, `height`, `user_id`) VALUES
(1, 'Deja', 'Cassin', NULL, '1970-09-26', '697-352-4705', '88200 Keagan Shore Apt. 980\nBradybury, NM 50167', 8, 'Sapiente est quos ullam aut sint. Quam tempore quia molestiae vel tempora. Culpa totam illum illo labore molestias. Nulla ipsum voluptatum eaque praesentium excepturi et. Sit dolores ipsam commodi laudantium.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 165, 2),
(2, 'Ezekiel', 'Wisoky', NULL, '1950-12-18', '+1-957-606-6596', '8827 Myrtis Viaduct\nWest Elna, MO 73586-3931', 10, 'Doloribus optio et quos aspernatur rerum molestiae. Nihil cum laudantium et cumque cum ipsam sed. Sunt ullam molestiae magni quis. Ut in unde possimus eum aut vel dolore. Minus sit asperiores nostrum eos tempora veritatis. Exercitationem et qui illo itaque.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 182, 1),
(3, 'Shanny', 'Ryan', NULL, '1930-02-10', '474-650-1898 x88490', '63846 Goyette Radial\nWest Jeanne, TN 16118-3438', 10, 'Et voluptatem veritatis laboriosam nobis et magni. Sed et est et pariatur. Incidunt minus quaerat fugiat. Dignissimos officia repellendus fuga libero ducimus. Aspernatur omnis quia est dolorem et ea unde maxime.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 119, 1),
(4, 'Abbey', 'Champlin', NULL, '1938-08-18', '+1 (408) 353-8682', '918 Simonis Forge Suite 566\nPfefferland, NH 68608-2808', 4, 'Occaecati facilis alias ut natus fugit dolorem. Et quod et quia doloremque neque ut. Natus perspiciatis repellendus impedit et et distinctio et. Et ipsa vitae sed tempore. Maiores unde ut omnis odit nihil ut non. Aspernatur voluptatem modi voluptas aut sed non modi eum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 184, 1),
(5, 'Wilfred', 'Strosin', NULL, '1925-04-27', '1-721-361-9778 x82820', '826 Denesik Road Suite 797\nBudmouth, UT 90936-5776', 20, 'Ullam voluptatem eum eius error. Qui cupiditate quo omnis. Iure enim qui qui. Voluptatem tempora mollitia voluptas. Impedit et debitis quis non cum suscipit sed maiores. Blanditiis beatae iste qui at. Sed et autem eum quo et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 122, 2),
(6, 'Matteo', 'Pollich', NULL, '1944-04-01', '1-780-204-8482 x16415', '527 Miller Via\nEast Fayborough, AR 51163', 22, 'Rerum dolor vitae dolores omnis eligendi iusto. Repellat mollitia molestias quod exercitationem. Dolorem impedit ea ad iusto dolores temporibus sed. Aut voluptatem qui et totam voluptas. Consequuntur distinctio temporibus nisi velit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 192, 2),
(7, 'Cicero', 'Leffler', NULL, '1960-09-04', '+1 (841) 707-2192', '609 Floy Manor\nKutchberg, AR 27344', 26, 'Est eligendi labore et numquam qui nisi reiciendis. Libero placeat et cupiditate ut sint. Omnis sequi ipsa ipsum asperiores. Doloremque natus qui sit porro cupiditate voluptatem.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 190, 1),
(8, 'Idella', 'Bode', NULL, '1991-09-20', '(441) 797-8091', '2711 Hauck Road\nSouth Angie, HI 21786', 8, 'Rerum minima sit corporis suscipit voluptatibus ipsum perspiciatis. Error at expedita non accusantium ut aut consequuntur. Sit error accusantium et facilis qui ab quia. Natus est et ut odio est. Eum esse qui aspernatur officiis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 138, 2),
(9, 'Clara', 'Herzog', NULL, '1926-07-23', '837-357-1628', '5538 Jakubowski Spurs Apt. 167\nNolaville, NM 02513', 11, 'Similique suscipit corporis molestias id vero. Molestiae animi tenetur excepturi aut tenetur. Sed amet sed incidunt. Itaque magni autem qui dolor voluptas iure ut. Consequatur voluptatum dolores corrupti sunt consequatur inventore distinctio. Quaerat deleniti consequatur qui id eum veritatis ipsam in.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 150, 2),
(10, 'Cielo', 'Greenfelder', NULL, '1944-10-11', '(543) 261-3676', '4909 Watsica Turnpike Suite 481\nVinniefort, WY 43295', 26, 'Ea sit qui rerum autem debitis. Mollitia neque voluptatibus necessitatibus ipsam velit sit quae. Veritatis ut recusandae enim vitae placeat culpa amet. Beatae delectus est dolorem aut eos expedita reiciendis ullam. Esse sit sapiente sed corporis voluptas. Tempore architecto ut iure et vel itaque. Inventore odio sed rerum recusandae officiis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 112, 1),
(11, 'Ford', 'Zboncak', NULL, '2005-02-23', '392-912-7462 x5240', '12390 Altenwerth Burg\nLeolastad, RI 13291', 18, 'Nostrum delectus modi ab expedita explicabo. Deserunt maxime aut omnis dignissimos sit consectetur. Eos et asperiores earum. Esse rem consequuntur ad excepturi voluptatem nihil minus. Quisquam in id sunt. Explicabo unde enim voluptatem aliquid.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 173, 2),
(12, 'Ahmed', 'Beahan', NULL, '1984-02-08', '1-483-417-4625 x9512', '3740 Buddy Point\nNorth Tianaland, ND 85854-6052', 4, 'Quam expedita laudantium soluta assumenda excepturi. Veritatis quas quo tempore accusamus. Ex recusandae soluta enim quasi perspiciatis quod quibusdam. Quae perferendis tempora et. Vitae sed quo ex rerum. Maxime magnam officiis optio quia nihil recusandae. Deleniti sed blanditiis atque laborum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 111, 2),
(13, 'Eladio', 'Will', NULL, '1972-10-26', '+1 (578) 596-2769', '94044 Lillian Valley Suite 997\nKaleyville, TN 50562-6120', 11, 'Vel nisi aliquam est dolor sint. Dignissimos doloremque eius laborum molestiae. Dolorum assumenda voluptatum dolorem modi. Aut ex molestias est vel et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 127, 2),
(14, 'Jayce', 'Bednar', NULL, '1979-05-05', '345.447.9383 x587', '336 Mohammad Track Suite 296\nEast Emelie, NY 26895-7676', 13, 'Ipsa sequi qui aspernatur et. Aliquid excepturi sunt delectus est. Non consequatur saepe quia voluptatem ex minima quia accusamus. Itaque sed rerum ut accusamus voluptatem possimus. Molestiae architecto reprehenderit nostrum. Optio ab soluta natus architecto quis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 176, 2),
(15, 'Tyrese', 'Spencer', NULL, '1989-01-11', '741-573-0631 x921', '440 Cremin Mountains Apt. 922\nStrosinstad, IN 88062-3877', 7, 'Impedit doloribus voluptate cupiditate natus totam voluptatem explicabo. Fuga praesentium nisi impedit qui sed dolorem in eaque. Autem nam deserunt consectetur cumque. Sed ab aut impedit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 172, 1),
(16, 'Mertie', 'Lesch', NULL, '1974-09-15', '1-956-410-6840 x03751', '98350 Waelchi Trail Suite 546\nLewisborough, PA 37719-8349', 16, 'Doloribus corrupti eligendi quia sed vitae et ut ex. Laboriosam sequi debitis aut ad. Dolor adipisci eum nam deserunt pariatur vel. Voluptas magnam magnam ut. Debitis ut illo rerum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 138, 1),
(17, 'Nigel', 'Jast', NULL, '1931-11-29', '259.503.7802 x9362', '5735 Steuber Meadow Apt. 467\nNorth Diamond, WI 43342', 1, 'Itaque consequuntur unde hic officia. Rerum sint tenetur exercitationem itaque. Iste repudiandae sequi laboriosam deserunt. Ipsum voluptatum doloribus quia facilis saepe et architecto quae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 168, 2),
(18, 'Janiya', 'Rath', NULL, '1999-03-24', '834-245-1601 x193', '83559 Nienow Points Suite 496\nKuhnborough, FL 36307', 15, 'Nihil a ex laudantium non et sint. Laboriosam sed consequatur ipsum laboriosam eius qui optio. Cum ipsam iste illum consequatur in fugiat est. Voluptatem provident sit vitae dolore sed. Doloremque esse eius veniam qui.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 156, 1),
(19, 'Jeramie', 'Herzog', NULL, '1933-11-30', '1-235-974-4085 x2821', '9774 Denesik Lodge Apt. 274\nKrisport, WV 43980', 30, 'Et eum excepturi neque sed. Est qui aut quibusdam provident corrupti at. Eius perspiciatis iusto quis assumenda aliquid beatae placeat. Magnam dignissimos vitae vitae eligendi enim iusto.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 146, 2),
(20, 'Gus', 'Adams', NULL, '1929-08-22', '751-812-5396', '8493 Zackery Turnpike\nGusikowskiland, AR 14697', 18, 'Totam rerum ea doloremque voluptates veniam sint. Eius numquam ipsam veniam expedita quo qui. Dignissimos et corrupti sunt quam. Rem voluptatem aut aspernatur necessitatibus. Rem aut quibusdam maiores iste hic expedita. In ea iure odio repellat voluptates eum. Voluptas non qui porro provident perferendis qui non quaerat.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', 172, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `countries`
--

CREATE TABLE `countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `countries`
--

INSERT INTO `countries` (`id`, `title`, `order`, `created_at`, `updated_at`) VALUES
(1, 'Spain', 0, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(2, 'China', 1, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(3, 'Canada', 2, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(4, 'Kuwait', 3, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(5, 'Vietnam', 4, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(6, 'South Georgia and the South Sandwich Islands', 5, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(7, 'Puerto Rico', 6, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(8, 'Guam', 7, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(9, 'Timor-Leste', 8, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(10, 'Paraguay', 9, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(11, 'Guatemala', 10, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(12, 'British Virgin Islands', 11, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(13, 'Ireland', 12, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(14, 'Azerbaijan', 13, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(15, 'Belgium', 14, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(16, 'Haiti', 15, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(17, 'United States Virgin Islands', 16, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(18, 'Argentina', 17, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(19, 'Israel', 18, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(20, 'Northern Mariana Islands', 19, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(21, 'Rwanda', 20, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(22, 'Cape Verde', 21, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(23, 'Nigeria', 22, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(24, 'Qatar', 23, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(25, 'Philippines', 24, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(26, 'Sao Tome and Principe', 25, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(27, 'Hong Kong', 26, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(28, 'Kiribati', 27, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(29, 'Korea', 28, '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(30, 'Chile', 29, '2018-05-29 04:15:01', '2018-05-29 04:15:01');

-- --------------------------------------------------------

--
-- Структура таблицы `currencies`
--

CREATE TABLE `currencies` (
  `id` int(2) NOT NULL,
  `text` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Валюта';

-- --------------------------------------------------------

--
-- Структура таблицы `dialing_statuses`
--

CREATE TABLE `dialing_statuses` (
  `id` int(2) NOT NULL,
  `text` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Статусы дозвона';

--
-- Дамп данных таблицы `dialing_statuses`
--

INSERT INTO `dialing_statuses` (`id`, `text`) VALUES
(1, 'Дозвонился'),
(2, 'Не дозвонился');

-- --------------------------------------------------------

--
-- Структура таблицы `document_check_statuses`
--

CREATE TABLE `document_check_statuses` (
  `id` tinyint(4) NOT NULL,
  `text` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Статус проверки документов\r\n- Не проверено,\r\n- Проверка пройдена,\r\n- Требуется повторная загрузка\r\n ';

--
-- Дамп данных таблицы `document_check_statuses`
--

INSERT INTO `document_check_statuses` (`id`, `text`) VALUES
(1, 'Отсутствует'),
(2, 'На проверке'),
(3, 'Требуется повторная загрузка'),
(4, 'Проверено');

-- --------------------------------------------------------

--
-- Структура таблицы `education_options`
--

CREATE TABLE `education_options` (
  `id` int(2) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Образование';

-- --------------------------------------------------------

--
-- Структура таблицы `employment_period_options`
--

CREATE TABLE `employment_period_options` (
  `id` int(2) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Занятость';

-- --------------------------------------------------------

--
-- Структура таблицы `forms`
--

CREATE TABLE `forms` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `textaddon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `checkbox` tinyint(1) NOT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `images` text COLLATE utf8_unicode_ci,
  `select` int(11) DEFAULT NULL,
  `custom` timestamp NULL DEFAULT NULL,
  `textarea` text COLLATE utf8_unicode_ci,
  `ckeditor` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `forms`
--

INSERT INTO `forms` (`id`, `title`, `textaddon`, `checkbox`, `date`, `time`, `timestamp`, `image`, `images`, `select`, `custom`, `textarea`, `ckeditor`, `created_at`, `updated_at`) VALUES
(1, 'Maiores qui nesciunt architecto.', 'Debitis ut.', 0, '2010-03-19', '15:09:38', '1988-07-18 04:01:54', NULL, 'images/uploads,images/uploads', NULL, NULL, 'Maiores et saepe incidunt deserunt magni cum dolores quae. Veritatis aut tempore qui a eius inventore. Voluptatem voluptate quam aut. Est voluptatem numquam qui dolor architecto cum. Unde nihil atque itaque. Cumque natus quam minus et.', 'Minima est libero aut quidem rem dignissimos. Saepe saepe tenetur iusto explicabo eius porro. Ipsam est mollitia harum sapiente consequuntur nisi. Tempore nobis esse voluptas id quidem sit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(2, 'Fugit ipsam eum.', 'Molestiae facere.', 1, '2000-09-11', '11:44:47', '2009-09-29 02:10:24', NULL, 'images/uploads,images/uploads,images/uploads', 3, NULL, 'Commodi quidem at quia ratione ea. Architecto a repellendus rem commodi pariatur. Hic ut laboriosam itaque voluptas at deleniti rerum. Reprehenderit deserunt minima placeat commodi. Assumenda aut fuga hic et sit veritatis assumenda. Et nihil excepturi deserunt quos consequuntur accusamus.', 'Sint aperiam accusamus praesentium. Neque error dolor voluptatibus delectus eaque. Iure earum voluptatem laboriosam quis. Quia et sint nesciunt nostrum qui itaque. Sunt dolore repudiandae velit porro alias.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(3, 'Excepturi dolor delectus laudantium optio consequatur.', 'Culpa et.', 1, '1989-12-13', '12:00:15', '2015-06-24 04:00:48', NULL, 'images/uploads,images/uploads', 2, NULL, 'Nihil facere quo debitis sunt numquam. Sunt dolorum beatae sit ab sint. Libero nam dolores tempore ipsum possimus nobis. In et accusamus aliquam aliquam. Ipsa ratione ut soluta odit voluptatem beatae. Doloremque atque animi est error dolorum ducimus. Dolorum unde aut quibusdam.', 'Praesentium ducimus deserunt dolor minima numquam qui. Praesentium sit aspernatur facere debitis veritatis minima. Ratione ut vitae sit. Eum nesciunt saepe recusandae quisquam quam at modi. Vitae sed molestiae commodi et sit sunt et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(4, 'Quisquam ad animi quaerat consequatur fugiat.', 'Magni necessitatibus aperiam.', 1, '1994-09-17', '06:51:38', '2012-05-13 21:46:17', NULL, '', 2, NULL, 'Voluptas facere officia corrupti. Inventore blanditiis non qui eligendi eos asperiores sed. Sequi sit perferendis veritatis quasi voluptatibus earum inventore. Velit ducimus harum non molestiae nemo.', 'Eaque placeat eum unde qui quia. Et amet ratione officiis itaque voluptas. Quis quis delectus aliquid optio earum neque. Aut qui consequatur consequuntur. Non et quo esse nisi et eum vero. Dicta ducimus quia aperiam odit omnis rerum hic. In mollitia alias vel.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(5, 'Sint dignissimos est minus neque.', 'Laudantium dolor.', 0, '1983-05-18', '10:02:31', '2006-01-03 17:23:50', NULL, 'images/uploads,images/uploads', NULL, NULL, 'Est quia reiciendis in repellendus similique nesciunt. Veniam dignissimos quisquam maxime eveniet impedit. Quia est sit id ex atque molestias fuga. Eos vitae itaque est ipsam. Eveniet est unde tempora tenetur est. Impedit neque cupiditate animi neque magni.', 'Nostrum officiis suscipit ut ut. Sapiente veritatis numquam voluptas distinctio soluta. Et odio et ut accusantium. Pariatur itaque officia nihil illo voluptatum nihil.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(6, 'Eum quo explicabo natus commodi.', 'Similique cupiditate quia.', 0, '2016-05-02', '19:42:05', '2012-04-14 09:23:12', NULL, 'images/uploads,images/uploads', NULL, NULL, 'Vero voluptas ut omnis asperiores totam nostrum. Est velit et et labore animi in perferendis eaque. Sit debitis eius eum architecto. Cumque minus aut ut neque consequatur assumenda laudantium.', 'Consequuntur ut perspiciatis at adipisci repudiandae totam veritatis. Sint explicabo dolorum reiciendis. Laborum culpa hic facilis voluptatem in amet architecto voluptas. Iste voluptas consequatur quod suscipit consectetur voluptatem. Qui necessitatibus aut dolorem voluptatem nihil accusantium laboriosam. Sapiente non quia corrupti impedit. Cumque dolores itaque nesciunt autem error sequi.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(7, 'Fuga assumenda velit accusantium aut delectus.', 'Aut sit.', 0, '2015-02-07', '21:42:45', '1970-05-15 21:28:06', NULL, 'images/uploads', 2, NULL, 'Minus repellendus odit veritatis. Dolorem aliquam soluta consequatur sint. Maxime molestiae iusto sint veritatis. Dolores sed aliquam qui similique. Incidunt fugit sed ut quis dolor. Quasi quisquam non nemo. Nihil iure eum adipisci.', 'Qui voluptatem eos veritatis veniam labore ut. In doloribus est nam rerum quod nobis. Quae qui qui error sit impedit tenetur et. Eum aperiam ea hic aut. Dolorum laboriosam ratione architecto omnis aspernatur et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(8, 'Omnis eaque doloribus quasi quam.', 'Dicta repudiandae.', 1, '1980-01-02', '09:11:26', '1989-12-15 06:56:03', NULL, 'images/uploads,images/uploads,images/uploads', NULL, NULL, 'Suscipit ullam facere itaque atque. Nihil earum et vitae eum officiis magnam. Ut velit animi non debitis molestias culpa. Maiores aut quas sapiente consequatur modi.', 'Et nostrum velit ducimus distinctio. Ut doloremque quae assumenda praesentium eum eveniet. Aut tempore est ut quia sapiente dolores voluptates. Velit illo ut rerum libero illum. Ab quod itaque cum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(9, 'Aut nulla vel aut.', 'Fuga natus.', 1, '1993-03-11', '18:27:19', '1992-01-29 18:51:59', NULL, 'images/uploads', NULL, NULL, 'Et vel quia pariatur qui officia libero. Impedit voluptas vitae omnis magnam voluptas id. Nisi et ipsam qui inventore sed sed. Qui suscipit architecto non omnis sint qui rem. Illo cum qui modi maiores ab excepturi id.', 'Assumenda voluptatem iste harum eius repudiandae et quisquam. Dolore dolores perferendis qui commodi ut magnam. Molestias nobis hic molestiae qui doloremque. Fugiat harum molestias saepe voluptates. Architecto sapiente omnis facere qui consequatur sint et. Aut vel modi omnis atque et. Officiis rerum blanditiis exercitationem corrupti vel.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(10, 'Dicta distinctio minus qui.', 'Porro atque.', 1, '2012-05-05', '22:56:26', '1995-07-01 06:19:38', NULL, 'images/uploads', NULL, NULL, 'Eligendi pariatur dolorum et natus sint natus et. Labore incidunt repudiandae eius reprehenderit ducimus ratione et. Qui nisi aut sunt at repellat minima. Enim porro molestias expedita cupiditate aut. Maxime optio ea placeat voluptatem sit quisquam.', 'Omnis quo architecto similique animi corporis natus. Necessitatibus amet voluptatibus perspiciatis. Voluptas molestias et nobis quia exercitationem animi earum. Optio nesciunt error qui omnis dolore accusantium reprehenderit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(11, 'Quo suscipit aut nisi.', 'Sapiente ad ipsam.', 0, '1985-03-11', '23:17:14', '2002-11-23 17:19:21', NULL, 'images/uploads,images/uploads', 3, NULL, 'Consequatur tempora vel aut suscipit occaecati qui temporibus. Corporis odio fugiat non nobis laboriosam quo. Tenetur debitis sunt nihil dolorem unde provident eos. Quaerat saepe voluptas velit itaque distinctio qui. Ab aut qui eum est.', 'Pariatur blanditiis necessitatibus qui sit reiciendis qui culpa doloribus. Delectus ab dolores ullam possimus. Est quasi ullam iste iusto. Natus hic sunt quis tenetur omnis qui. Nisi reiciendis saepe quia quo nihil.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(12, 'Quisquam dolorem omnis aspernatur id aut.', 'Accusantium et placeat.', 1, '1997-11-09', '19:02:58', '2003-09-10 22:09:34', NULL, 'images/uploads,images/uploads,images/uploads', 1, NULL, 'Natus ut voluptate eos. Qui voluptatibus ut fugit repellendus. Magnam culpa soluta optio provident velit aut. Nulla eos dolores dolor maiores. Adipisci quis maiores sint et aut. Excepturi quos sunt sed facere aliquam.', 'In officia harum beatae minus deleniti ea. Maxime est quidem sint aut eum recusandae iusto. Tempora nisi cupiditate voluptates nesciunt quasi. Dicta et veritatis voluptas omnis harum sunt est. Quo harum mollitia et quis. Accusantium magnam eos nostrum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(13, 'Ut vitae ad dolore aspernatur dolorem.', 'Ducimus est.', 0, '2013-05-05', '17:28:00', '2001-09-20 08:16:05', NULL, '', NULL, NULL, 'Unde corporis omnis voluptate excepturi. Esse impedit ullam necessitatibus omnis quo recusandae. Ea illum culpa mollitia adipisci commodi amet. Tempore est ut et cupiditate molestias nesciunt rerum.', 'Dolorem corporis perferendis labore. Veniam voluptas hic ut voluptate. Sed rerum modi quia repellat est. Aut laborum dolor voluptatem praesentium. Non consequatur omnis quos aspernatur velit consequatur. Aspernatur quia ea cum vel facilis dignissimos. In ab voluptatem est ipsum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(14, 'Voluptas debitis consequatur numquam.', 'Nulla maiores.', 0, '1983-10-01', '14:52:30', '1983-08-05 23:55:26', NULL, 'images/uploads,images/uploads,images/uploads', 3, NULL, 'Similique voluptas numquam eligendi accusantium accusantium aut iusto. Reiciendis repudiandae perferendis quis dolorum ducimus vel eum. Repellat velit soluta consequatur natus et quod et repellat. Autem non fuga temporibus quia quidem quibusdam quidem laborum. Ratione similique cumque unde consequatur illo quo.', 'Laboriosam iure explicabo iure dolorem. Repellendus enim ratione rerum nihil non at. Tempore est accusamus numquam. A voluptatum adipisci fuga minima pariatur ipsam consequatur.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(15, 'Sunt accusamus fugiat maxime.', 'In qui voluptas.', 1, '2017-03-07', '09:48:42', '2007-01-27 02:35:26', NULL, 'images/uploads', 3, NULL, 'Aut similique itaque velit quis et sed. Occaecati ut ducimus vitae unde eum. Eius necessitatibus labore nemo sapiente eligendi ad quisquam. Et aperiam illo et odit quia vel.', 'Aut beatae suscipit saepe cumque sapiente voluptatum. Tempora ea et earum maiores. Eum et magnam optio quia. Magni quia alias quidem et doloribus nobis. In nisi cum officia accusamus. Voluptatem harum molestiae aut rerum voluptatem nulla.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(16, 'Pariatur ducimus vero.', 'Aut odio officia.', 0, '1970-04-01', '23:04:01', '1978-04-01 17:59:45', NULL, '', NULL, NULL, 'Illum dolor dolorem ut vitae ipsa. Voluptatem explicabo doloremque quis velit dolor minus neque. Ea suscipit praesentium repudiandae natus est. Sunt dolor et sit qui.', 'Nemo nostrum ab perferendis. Voluptatem et unde aliquam praesentium ratione et. Qui tenetur sunt et ipsa aut nisi et. At ipsam est rerum voluptatem quo.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(17, 'Atque at quidem.', 'Eveniet sint.', 1, '1973-11-25', '15:40:43', '2009-07-09 05:19:02', NULL, 'images/uploads,images/uploads', NULL, NULL, 'Ratione repudiandae rem qui ut molestias quidem. Enim minus atque voluptas veniam et mollitia. Sit quia aspernatur quaerat quo in et. Voluptates occaecati quo fuga quis rem ipsa voluptas. Dolorem asperiores error sed omnis et esse est.', 'Ab beatae sint tempore quos accusantium. Velit modi aut vitae illum. Sunt facere tenetur aliquam iste magnam molestiae ex. Deserunt tempora quo optio quam. Reprehenderit et quidem officia. Repellendus impedit repudiandae ut veniam a labore. Consequatur ut culpa earum suscipit illum. Omnis ratione non consequatur dolor maxime.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(18, 'Quis nulla ad eos.', 'At quasi.', 0, '1988-10-20', '06:15:10', '1980-02-24 02:14:32', NULL, 'images/uploads,images/uploads', 2, NULL, 'Dicta ut praesentium ab sunt at ut eveniet. Suscipit sed aspernatur a quibusdam sit quibusdam eum. Officia qui rerum autem iste et ab. Impedit enim consectetur animi similique est dolor.', 'Enim optio blanditiis et ratione. Illo quisquam officia ipsum et. Quaerat est ex rerum expedita vitae. Cum corporis sunt sit eaque dolorum in. Nihil natus quia nihil ratione.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(19, 'Enim recusandae dolore necessitatibus.', 'Non inventore.', 0, '1970-08-03', '13:41:26', '1991-12-03 13:19:00', NULL, 'images/uploads', 1, NULL, 'Voluptatem vel accusamus sed. Vero eaque ducimus placeat itaque nam quis tempora dolor. Eos fugit dignissimos culpa sapiente repellendus rerum iste. Quibusdam labore quisquam labore consequatur eos non similique illum. Vero velit molestiae sapiente delectus optio et dolores. Expedita totam architecto esse. Assumenda facere aperiam rerum repudiandae iure omnis est corrupti.', 'Quo voluptates dolor officiis placeat quos. Voluptate et et quisquam molestias esse vitae qui. Accusantium dicta quia consequatur perspiciatis rerum asperiores. Dolores aut aliquam saepe earum. Officia et odit minus. Odit ea nihil voluptas doloribus. Aspernatur aliquid distinctio aspernatur velit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(20, 'Minus sunt tempora velit.', 'Magni architecto nihil.', 0, '1988-08-26', '11:03:17', '1985-06-14 19:32:19', NULL, 'images/uploads', 3, NULL, 'Iusto non et ut. Quibusdam corrupti exercitationem ut ut. Esse id amet maxime et minus. Rem odio eos debitis occaecati quis. Assumenda eum vero iste eos aut distinctio voluptates. Aspernatur optio quo assumenda sed magnam reprehenderit et animi. Nemo temporibus tempore necessitatibus aut eius fugiat.', 'Dignissimos est recusandae optio temporibus. Voluptas eveniet qui sit nemo deserunt nulla. Laboriosam quos natus nihil aut aut commodi et. Blanditiis et dolor quisquam temporibus id pariatur hic. Qui numquam et et ullam et laboriosam ducimus.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(21, 'Dolorum qui sunt.', 'Numquam rem consequatur.', 0, '2005-04-01', '04:02:10', '2015-10-08 04:14:07', NULL, 'images/uploads,images/uploads,images/uploads', NULL, NULL, 'Voluptas odit ut aut qui animi eum. Voluptatem dolores nulla velit autem voluptatem ab. Harum enim qui ea voluptatem rerum. Iure non earum commodi rerum quo omnis et nulla. Incidunt est beatae praesentium harum soluta.', 'Ea accusamus amet fuga temporibus voluptas autem hic quidem. Velit aspernatur earum blanditiis optio maxime aut. Minus excepturi quibusdam sunt. Aspernatur debitis voluptatem ullam qui hic. Nam recusandae rerum omnis sit voluptatibus est magni.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(22, 'In voluptas quia sit vel.', 'Aut ex.', 0, '2012-03-15', '15:51:48', '1995-02-08 04:19:06', NULL, '', 1, NULL, 'Rerum quae impedit voluptatem dolores. Quaerat incidunt possimus aspernatur suscipit. Iure nihil vel id quisquam odit. Accusantium consequatur eius vel illum fugiat. Cum eos doloremque aut quaerat consequatur architecto.', 'Veritatis ab totam ex maiores aspernatur assumenda. Iste in qui facilis rem repellat. Hic qui voluptatibus rerum eos velit. Voluptates doloremque amet magni repellendus quia. Placeat quis incidunt adipisci quia maiores sed repellat aut. Non dignissimos nobis voluptatem doloremque dolorem.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(23, 'Quia non ex nulla quos in.', 'Error autem aut.', 1, '2015-01-27', '02:09:38', '2011-02-10 20:17:32', NULL, 'images/uploads,images/uploads', 2, NULL, 'Debitis qui tempore voluptatibus dolor quod illum. Doloribus laboriosam iusto beatae doloribus totam ut. Omnis pariatur placeat suscipit earum ducimus molestiae tenetur. Nihil nam aut quis aut nobis.', 'Sunt culpa architecto aut aliquam. Hic et laudantium eum voluptatum possimus magnam laboriosam. Aut cumque unde consequatur ducimus ut. Ut enim fuga quis. Nisi eos vel voluptates quia sint.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(24, 'Corrupti et minus.', 'Quia quia ratione.', 0, '1972-06-15', '16:50:22', '2009-10-03 09:35:37', NULL, 'images/uploads', 1, NULL, 'Rerum sed consequatur cum fuga excepturi iusto. Aut necessitatibus corporis perspiciatis eius. Odit sed ducimus quam. Ab sed dicta facere ad. Accusantium consequuntur sit quo quam.', 'Consequatur voluptate unde cupiditate possimus voluptatem sunt minus. Possimus veritatis sed doloremque non. Et ut consequuntur rerum ullam. Dolor consequuntur dolor cum corrupti.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(25, 'Temporibus mollitia consectetur reiciendis.', 'Repellendus reprehenderit.', 0, '1971-10-06', '07:08:13', '1998-07-04 20:57:03', NULL, 'images/uploads,images/uploads,images/uploads', NULL, NULL, 'Necessitatibus aliquid eos adipisci et. Quo odit veritatis ducimus officia doloremque. Dolores delectus quisquam magnam neque labore molestias. Repellendus doloribus excepturi dolorem quos voluptatem reprehenderit. Quo deserunt repellendus architecto repellendus. Magnam hic perferendis odit blanditiis voluptates laudantium cupiditate. Commodi quis et aut ut error ducimus.', 'Porro praesentium maxime ut eum corporis maxime. Est laborum itaque consequatur dolor sit non eligendi. Velit neque non ducimus consequuntur. Ut optio qui dolorem. Ducimus est et similique ducimus voluptas dolore. Omnis tenetur est voluptas laudantium odio saepe.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(26, 'Beatae consequuntur dolorem.', 'Porro nihil.', 1, '1981-03-05', '04:53:42', '2001-02-15 09:47:05', NULL, 'images/uploads,images/uploads', NULL, NULL, 'Incidunt pariatur laboriosam reiciendis accusamus. Vel fugiat doloremque cupiditate ea doloremque quia. Dicta fugiat laudantium tenetur veritatis reiciendis esse. Qui officia quia id quia rem.', 'Aut repellendus dolorum facere vel omnis qui asperiores. Amet in aut dolorem recusandae nam. Tenetur aut odio laudantium debitis. Quod quis et aut dolorem explicabo minima. Dolore earum aut aut voluptas id qui.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(27, 'Cupiditate delectus reprehenderit quo sint aperiam.', 'Voluptate dolor consequuntur.', 1, '1987-10-17', '16:32:58', '1981-04-18 14:51:05', NULL, 'images/uploads', NULL, NULL, 'Fugiat occaecati mollitia magni atque voluptatibus. Aperiam quia eveniet est. Praesentium voluptas distinctio voluptatem. Unde aliquam voluptatem accusantium eligendi odit a autem. Incidunt voluptatem omnis aperiam deleniti similique nesciunt molestias dolores.', 'Aliquid provident qui quod inventore tempora est. Ad voluptates vitae et voluptatem. Tempore iure impedit minus aliquam rerum quia. Reiciendis voluptatem aut in voluptatibus minus voluptas. Minus sunt enim atque et quisquam perspiciatis ea maiores. Nam non ullam deserunt vel dolore deserunt et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(28, 'Odit et eos dolorum optio voluptatem.', 'Eveniet vero repellat.', 1, '2016-03-30', '03:45:31', '1992-05-02 00:14:07', NULL, 'images/uploads,images/uploads,images/uploads', 1, NULL, 'Tenetur et odio sunt officia harum quibusdam. Fugiat optio sit enim cum porro excepturi. Dolor illum non in maiores rem. Ad exercitationem animi laudantium ut vel ut eius officiis. Nam maxime blanditiis facere ut deleniti. Aut earum omnis aspernatur rerum deserunt. Itaque earum est et.', 'Ipsum omnis tempora id qui. In quia aliquam numquam est mollitia. Neque aliquam et enim odio omnis. Qui qui nihil sit minus incidunt repellendus.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(29, 'Nostrum esse quam.', 'Excepturi dolor.', 1, '2007-03-28', '13:28:36', '1974-08-15 12:52:02', NULL, 'images/uploads,images/uploads', 2, NULL, 'Quia ut repellendus repellat ipsam molestiae consequatur officiis autem. Assumenda laudantium omnis consequatur et. Illo excepturi rem id sit voluptas. Consectetur aut quas quis molestias.', 'Eius qui explicabo repellat nisi autem reiciendis. Voluptatem et temporibus aliquid consequatur in nihil aliquam vel. Consequatur nemo inventore quisquam. Eius asperiores corporis eos officia.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(30, 'Odit non quia.', 'Qui in.', 0, '1999-01-19', '13:21:34', '1994-08-04 01:06:46', NULL, 'images/uploads,images/uploads,images/uploads', NULL, NULL, 'Et soluta cupiditate quia hic voluptate. Ut accusantium odit iure laudantium. Laborum fugiat officiis dignissimos. Quas dolorem est quo laboriosam architecto qui est. Voluptas qui sed ea officia amet aut. Ut ut nulla qui rerum maxime eum. Ea neque ut dicta quod omnis nam.', 'Consequatur debitis rerum magni amet dolorem. Aut rerum officiis consequatur est tempora. Aliquam deserunt et tenetur. Qui nulla sapiente amet aut. Error dolor perferendis consequatur. Eaque numquam omnis tempora tenetur earum quidem nesciunt.', '2018-05-29 04:15:01', '2018-05-29 04:15:01');

-- --------------------------------------------------------

--
-- Структура таблицы `genders`
--

CREATE TABLE `genders` (
  `id` int(2) NOT NULL,
  `text` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Гендер.';

--
-- Дамп данных таблицы `genders`
--

INSERT INTO `genders` (`id`, `text`) VALUES
(1, 'Мужской'),
(2, 'Женский');

-- --------------------------------------------------------

--
-- Структура таблицы `home_ownership_period_options`
--

CREATE TABLE `home_ownership_period_options` (
  `id` int(2) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Время владения недвижимостью';

-- --------------------------------------------------------

--
-- Структура таблицы `issued_authorities`
--

CREATE TABLE `issued_authorities` (
  `id` int(2) NOT NULL,
  `text` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Органы выдачи удостверения';

--
-- Дамп данных таблицы `issued_authorities`
--

INSERT INTO `issued_authorities` (`id`, `text`) VALUES
(1, 'МЮ РК'),
(2, 'МВД РК');

-- --------------------------------------------------------

--
-- Структура таблицы `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `loan_history_events`
--

CREATE TABLE `loan_history_events` (
  `id` int(10) NOT NULL,
  `borrower_loan_id` int(10) NOT NULL COMMENT 'Займ',
  `text` varchar(255) DEFAULT NULL COMMENT 'Событие',
  `history_key` varchar(250) DEFAULT NULL COMMENT 'Групповой ключ',
  `history_data` text COMMENT 'Дополнительные данные',
  `initiator` varchar(100) DEFAULT NULL COMMENT 'Ссылка на инициатор события события',
  `remote_addr` varchar(50) DEFAULT NULL COMMENT 'IP адрес инициатора',
  `created_at` datetime DEFAULT NULL COMMENT 'Дата формирования заявки',
  `updated_at` datetime DEFAULT NULL COMMENT 'Дата обновления информации в заявке'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `loan_history_events`
--

INSERT INTO `loan_history_events` (`id`, `borrower_loan_id`, `text`, `history_key`, `history_data`, `initiator`, `remote_addr`, `created_at`, `updated_at`) VALUES
(1, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 0 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:34:58', '2018-07-11 08:34:58'),
(2, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 0 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:35:28', '2018-07-11 08:35:28'),
(3, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 1 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:35:36', '2018-07-11 08:35:36'),
(4, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 1 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:37:15', '2018-07-11 08:37:15'),
(5, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 1 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:38:20', '2018-07-11 08:38:20'),
(6, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 1 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:42:52', '2018-07-11 08:42:52'),
(7, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 2 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:42:58', '2018-07-11 08:42:58'),
(8, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 3 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:43:07', '2018-07-11 08:43:07'),
(9, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 1000 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:44:16', '2018-07-11 08:44:16'),
(10, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 2000 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:44:37', '2018-07-11 08:44:37'),
(11, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 7000 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:44:41', '2018-07-11 08:44:41'),
(12, 8, 'Оплата через QIWI терминал. \nСумма: 10740 тг. \nЗайм оплачен.', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:49:28', '2018-07-11 08:49:28'),
(13, 8, 'Оплата через QIWI терминал. \nСумма: 10740 тг. \nЗайм оплачен.', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:49:33', '2018-07-11 08:49:33'),
(14, 8, 'Оплата через QIWI терминал. \nСумма: 10740 тг. \nЗайм оплачен.', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:54:29', '2018-07-11 08:54:29'),
(15, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 0 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 08:58:13', '2018-07-11 08:58:13'),
(16, 8, 'Внесено недостаточно средств для закрытия платежа.\nСумма: 0 тг. ', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 09:11:49', '2018-07-11 09:11:49'),
(17, 8, 'Оплата через QIWI терминал. \nСумма: 0 тг. \nЗайм оплачен.', 'client_history, qiwi_transaction', NULL, NULL, NULL, '2018-07-11 09:12:38', '2018-07-11 09:12:38');

-- --------------------------------------------------------

--
-- Структура таблицы `loan_statuses`
--

CREATE TABLE `loan_statuses` (
  `id` int(2) NOT NULL,
  `text` varchar(50) DEFAULT NULL,
  `loan_status_category_id` int(2) NOT NULL COMMENT 'Базовый статус заявки'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Статусы займа';

--
-- Дамп данных таблицы `loan_statuses`
--

INSERT INTO `loan_statuses` (`id`, `text`, `loan_status_category_id`) VALUES
(1, 'Заявка заполнена клиентом', 1),
(2, 'Загрузка документов', 1),
(3, 'Документы загружены клиентом', 1),
(4, 'Загрузка договора', 1),
(7, 'Договор загружен клиентом', 1),
(8, 'Подтверждение заявки', 1),
(9, 'Заявка подтверждена', 1),
(10, 'На выдачу', 2),
(11, 'Займ выдан', 2),
(12, 'Займ оплачен', 3),
(13, 'Займ продлён', 2),
(14, 'Займ просрочен', 2),
(15, 'Передано нотариусу', 2),
(18, 'Закрыто', 3),
(19, 'Передано судебному исполнителю', 3),
(20, 'Закрыто по решению суда', 3),
(21, 'Отказано', 4);

-- --------------------------------------------------------

--
-- Структура таблицы `loan_status_categories`
--

CREATE TABLE `loan_status_categories` (
  `id` int(2) NOT NULL,
  `text` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Базовые статусы займа';

--
-- Дамп данных таблицы `loan_status_categories`
--

INSERT INTO `loan_status_categories` (`id`, `text`) VALUES
(1, 'Новая заявка'),
(2, 'Одобрено'),
(3, 'Закрыто'),
(4, 'Отказано');

-- --------------------------------------------------------

--
-- Структура таблицы `loan_types`
--

CREATE TABLE `loan_types` (
  `id` int(3) NOT NULL,
  `text` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Валюта';

-- --------------------------------------------------------

--
-- Структура таблицы `marital_statuses`
--

CREATE TABLE `marital_statuses` (
  `id` int(10) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Семейное положение';

--
-- Дамп данных таблицы `marital_statuses`
--

INSERT INTO `marital_statuses` (`id`, `text`) VALUES
(1, 'Не женат / Не замужем'),
(2, 'Женат / Замужем'),
(3, 'Разведен / Разведена'),
(4, 'Вдовец / Вдова'),
(5, 'Гражданский брак');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2014_10_15_103219_create_countries_table', 1),
(4, '2014_10_15_103414_create_companies_table', 1),
(5, '2014_10_15_103706_create_contacts_table', 1),
(6, '2014_10_15_103900_create_company_contact_table', 1),
(7, '2014_10_15_113706_create_pages_table', 1),
(8, '2015_04_24_142119_create_news_table', 1),
(9, '2015_04_25_112637_create_posts_table', 1),
(10, '2015_04_26_093247_create_forms_table', 1),
(11, '2015_05_06_101632_add_height_field_to_contacts', 1),
(12, '2015_09_10_193651_create_roles_tables', 1),
(13, '2016_02_26_073157_add_user_id_to_contacts_table', 1),
(14, '2016_03_25_135800_add_text_html_to_posts', 1),
(15, '2016_05_18_134804_add_avatar_to_users_table', 1),
(16, '2017_04_07_095256_add_contact_to_posts_table', 1),
(17, '2018_05_16_150910_create_some_models_table', 1),
(18, '2018_05_16_151625_create_some_another_models_table', 1),
(19, '2018_07_06_183653_create_jobs_table', 2);

-- --------------------------------------------------------

--
-- Структура таблицы `monthly_income_options`
--

CREATE TABLE `monthly_income_options` (
  `id` int(2) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ежемесячный доход';

-- --------------------------------------------------------

--
-- Структура таблицы `news`
--

CREATE TABLE `news` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `published` tinyint(1) NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `news`
--

INSERT INTO `news` (`id`, `title`, `date`, `published`, `text`, `created_at`, `updated_at`) VALUES
(1, 'Velit in voluptatem distinctio sunt possimus.', '1965-06-06', 1, 'Vitae doloremque ab illo rerum voluptatem est dolores voluptatum. Saepe magni quis odio aut aliquid sit. Iusto occaecati nihil tempora excepturi et tempora enim. Id omnis aliquam porro ut quis id.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(2, 'Sed autem numquam quis.', '1992-08-31', 1, 'Et officiis dolor qui quaerat dolorem vitae rerum. Veniam deserunt ipsa aut impedit et. Et sequi et dolorum est. Laudantium ad quos sint fuga. Dicta veritatis quidem ad. Officia corrupti quod sint sunt veniam incidunt. Temporibus soluta minus voluptatem laborum et consequatur pariatur.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(3, 'Nostrum et et laudantium dolore fugit.', '1983-11-21', 0, 'Sit reiciendis consequatur soluta eum. Aliquam ex soluta est dolores pariatur. Quaerat animi laudantium nulla nihil. In ipsam inventore dolorem.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(4, 'Quia mollitia sunt veritatis nisi.', '1938-03-05', 1, 'Asperiores alias voluptatum veniam soluta. Dignissimos temporibus ut voluptatem vero similique consectetur est quisquam. Ex autem quis quo debitis aliquam illo et. Necessitatibus voluptatibus itaque et soluta. Non ut quia aliquid illo.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(5, 'Ratione quasi officia dolor.', '1975-12-07', 1, 'Soluta ipsam a voluptatem culpa. Quasi totam ex animi qui aut autem nam omnis. Est quasi inventore aut quia et. Aut officia ea debitis est. Facere ut hic eius rerum et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(6, 'Aut consequatur nobis sint omnis.', '1966-07-12', 1, 'Dolor eos aut nemo suscipit nemo. Aut aut eos incidunt explicabo et recusandae amet. Mollitia ipsum recusandae neque eos molestiae fugiat laboriosam perferendis. Quas eligendi at ab aut deleniti.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(7, 'Deserunt eum sunt.', '1953-06-09', 1, 'Placeat voluptatem et sit odio hic. Modi inventore eos ut eos aut non. Cumque delectus aut itaque corrupti. Sunt iste laudantium et reprehenderit. Animi quia a alias similique asperiores ex aperiam.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(8, 'Est et excepturi nesciunt ut.', '1924-09-23', 0, 'Vel cumque vel earum id numquam. Necessitatibus voluptatibus atque facere. Eum esse quaerat nihil tempora et. Numquam molestias ipsa beatae quod assumenda porro. Molestiae iste ut sed aut reprehenderit accusamus. Labore harum placeat similique harum dolorem cumque fugit qui.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(9, 'Consequuntur nobis exercitationem inventore non voluptatum.', '1925-09-07', 0, 'Aspernatur voluptas autem ipsum voluptatem. Autem sunt et soluta ea. Quam est omnis aliquid voluptatem dolorem in animi ducimus. Consequatur est dolor suscipit animi possimus sit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(10, 'Ut at alias.', '1965-08-31', 1, 'Quae neque repellat dolor aut nam quo rem. Rerum molestiae ipsa sit reprehenderit est quo. Deleniti velit recusandae iure accusamus et fuga. Omnis voluptatem sit aliquid molestiae delectus enim culpa. Atque maxime tempora et culpa facere et. Et culpa explicabo quas quos voluptatem quaerat. Vero eos omnis perspiciatis et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(11, 'Quasi consequatur culpa.', '2014-10-13', 0, 'Adipisci eum doloremque ut est sint est. Aspernatur rerum molestiae at et et modi beatae enim. Qui qui beatae corrupti et. Harum corporis nostrum exercitationem adipisci qui nobis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(12, 'Sint saepe sit nemo reiciendis rem.', '2016-07-18', 1, 'Quia dicta qui voluptatem numquam dolorem dolores et. Facilis vitae officiis repellendus fuga et. Culpa at perferendis rerum tenetur totam totam. Rem ipsum cumque modi minima laborum esse dignissimos. Velit reprehenderit velit atque sapiente.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(13, 'Sint soluta aperiam.', '1948-05-03', 0, 'Sit sit quae ad maxime accusamus. Ea sequi voluptates eum non voluptatibus nihil. Id dicta quae non commodi. Sapiente id adipisci nam voluptas autem. Laborum illo impedit eveniet cumque. Sit adipisci voluptas ipsum distinctio.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(14, 'Placeat est quisquam nisi modi.', '1954-12-14', 1, 'Aut possimus ex quos ea aut ad consequatur adipisci. Qui distinctio sunt optio et. Atque animi nostrum id tempora. Nesciunt beatae voluptatem culpa voluptas.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(15, 'Numquam ab quia laudantium voluptatem.', '1950-01-07', 0, 'Voluptates delectus sed provident voluptatem numquam quo et. Maiores reprehenderit nemo sequi animi voluptatum et enim. Rerum blanditiis harum accusantium labore perferendis quod. Autem occaecati dolores commodi fugiat aut. Et illo quia ipsam eum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(16, 'Magnam incidunt optio sunt.', '1997-01-14', 1, 'Et rerum nam sint odit itaque officiis velit sed. Et saepe dolor pariatur quis quaerat beatae eum. Maiores aut dolor quia modi iste quam. Est ad illum aut amet repellat doloremque. Exercitationem neque consequatur accusantium veritatis. Provident quod autem officiis quis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(17, 'Voluptatum omnis et quod.', '2017-04-28', 1, 'Consectetur ut velit doloremque dicta odit ipsa. Tenetur quo ipsum qui nesciunt dolor. Earum possimus molestiae ducimus consequatur est nostrum culpa. Dolor nostrum tempora explicabo est. Exercitationem impedit tempora aut qui expedita laboriosam aut. Labore saepe provident nostrum eveniet.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(18, 'Quis rerum voluptates eum praesentium.', '1926-08-07', 0, 'Excepturi placeat qui aliquam eum quis. Quos ut veniam a sit. Quod facere nam quia quod sunt magnam. Quis ut et laboriosam voluptatem cum. In consectetur soluta voluptatem omnis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(19, 'Provident enim eligendi optio et.', '2016-06-09', 0, 'Odit est perspiciatis nostrum similique dolorum explicabo harum sapiente. Perferendis assumenda rerum repudiandae magni. Veniam accusantium incidunt at odit. Optio ut eos error veniam sit ipsum a. Iure esse consequuntur ratione et dolores.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(20, 'Dolor deleniti quis accusamus sapiente ipsum.', '1999-09-19', 0, 'In sit consectetur placeat aut. Rem velit sint ipsa. Et quis explicabo voluptate voluptas. Ad assumenda et dolor aut qui. Pariatur assumenda aut autem et. Totam quia iste fugiat aspernatur commodi et reiciendis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(21, 'Accusantium assumenda cum et mollitia.', '1926-09-12', 0, 'Quasi facere ducimus voluptatem itaque sed recusandae omnis eveniet. Numquam rerum est officiis unde quis molestiae. Dignissimos quidem et aut qui neque quibusdam et. Fugiat ullam illo totam amet voluptatibus omnis beatae occaecati. Saepe odit vitae qui.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(22, 'Vel sed sequi.', '1919-10-31', 0, 'Et veniam ad quis et eaque. Qui explicabo porro quidem ad. Doloribus consectetur qui vel. Sed expedita natus autem laboriosam quasi dolorem exercitationem. Qui est et porro corrupti. Ab quaerat eos eum veniam quae sint.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(23, 'Explicabo laborum numquam magnam totam.', '2018-03-19', 1, 'Alias dolor excepturi voluptas ab reiciendis quidem. Distinctio enim sint architecto. Ullam et aut recusandae. Facere velit velit ut eveniet itaque id excepturi. Itaque ab est ut itaque nobis voluptates rerum. Et illo at vel explicabo sapiente. Sed enim modi dolore voluptates sit nobis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(24, 'Autem beatae architecto eum.', '1961-02-03', 1, 'Pariatur repellendus neque iusto est dolor. Ipsum dicta rerum est at. Repellat modi delectus beatae. Voluptas maxime qui at. Qui repellendus sit aspernatur nihil molestias eos et. Rerum aspernatur aliquid enim velit. Enim quae iusto officia et inventore repellat.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(25, 'Dolores dolores accusamus similique.', '1999-08-15', 0, 'Eum et ex rerum molestiae quisquam veritatis magni. Odio voluptatum architecto accusantium non earum debitis. Illo facilis praesentium cupiditate et amet at. Perferendis ex at eum est id quisquam sed quasi. Corrupti voluptatem natus consequatur nobis magni aut. Iure est commodi quia debitis. Error sed ut eius et illo neque ipsum voluptas.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(26, 'Pariatur officia officia.', '1952-11-08', 0, 'Voluptas soluta nulla fuga et dicta quidem cumque magni. Quae omnis magnam dolor dolorem iste perspiciatis. Et qui eligendi facilis et. Molestias necessitatibus at neque ut mollitia hic.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(27, 'Ea est veritatis neque.', '1929-04-16', 1, 'Occaecati modi dolore odit velit quidem. Quia nihil dolore molestiae sit illo id reprehenderit. Voluptatibus nisi aut vero et consequuntur distinctio rerum. Praesentium autem maxime odit eum. Possimus sunt quae non incidunt ut aut.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(28, 'Sit nam ea dolor commodi tempora.', '1970-11-17', 1, 'Ut minus voluptatem in quo ex harum eaque. Recusandae et magni accusamus cumque facere eos ea est. Et quia ab nemo enim laboriosam. Sit fugit illum aut expedita aspernatur. Quia hic similique et alias commodi. Temporibus aut illum unde assumenda at blanditiis id. Alias laborum cupiditate quidem possimus quo.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(29, 'Qui quae labore excepturi aut.', '1973-06-30', 1, 'Dolore reprehenderit harum et ea veniam nihil. Totam id occaecati placeat officiis dolorem et sit. Aspernatur sed voluptatem amet non ullam voluptatibus qui iusto. Voluptatem quia beatae quia sunt corporis fugit maiores. Omnis reiciendis suscipit praesentium labore. Inventore commodi ipsam qui aut. Excepturi tenetur dolorum voluptatum aut ipsam aliquid.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(30, 'Ducimus provident cumque vero.', '1930-04-27', 1, 'Tenetur eum qui fugit rerum nesciunt sed aut. Aliquam corporis explicabo sequi blanditiis doloribus. Rerum autem aut tenetur veniam recusandae rerum. Nam beatae consequatur aut possimus impedit sint. Sint rerum voluptatum cum facilis porro tenetur qui. Commodi porro itaque asperiores.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(31, 'Magni omnis sint.', '1946-10-27', 1, 'Similique ullam dolor sunt ad. Necessitatibus soluta vel distinctio minima. Voluptatem incidunt omnis unde provident. Error laborum quibusdam necessitatibus eum officiis. Eius alias expedita id vero repudiandae quia. Sit labore illum earum officia. Autem non eum non et aut quo laborum alias.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(32, 'Aut rerum harum recusandae.', '1957-06-11', 0, 'Commodi consequatur nisi eos. Voluptatem perferendis ut omnis facilis dolor expedita est voluptatibus. Quidem ex ipsam explicabo totam rerum. Modi eligendi facere quas eum ullam qui non. Ipsa dolor sunt eum mollitia consequuntur sed mollitia.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(33, 'Odit et incidunt consequatur.', '1940-02-12', 0, 'Soluta tenetur ut voluptatem ipsum. Enim iure et voluptas et distinctio aut. In deleniti voluptatem accusamus cum adipisci quia. Quae perspiciatis aut animi. Reiciendis voluptatibus et quisquam vero cum et deleniti sunt.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(34, 'Fuga eos harum ex recusandae quia.', '2006-04-27', 0, 'Numquam voluptates alias sapiente voluptatibus sed tempora sequi sint. At qui est ipsa earum hic quos. Ducimus et et mollitia. Recusandae ut qui rerum. Quas voluptatem ducimus nemo deleniti quas. Reiciendis et deleniti pariatur sint laborum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(35, 'Ut occaecati atque pariatur.', '1975-11-01', 0, 'Provident facilis provident aspernatur ipsum. Qui harum sapiente magnam voluptas. Fugiat corporis enim non sed sit. Aut eos sed corporis et. Unde et et sed nemo. Tenetur aspernatur vel itaque dolores pariatur.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(36, 'Quae at ex voluptatem voluptatem.', '1961-04-07', 1, 'Aut quasi quis ad quo quia sequi dolores. Maxime magni dolorem qui eveniet tenetur repellat dolore. Officiis nam accusantium vel impedit soluta quia. Facere autem aut non cum et. Dolor assumenda corrupti et unde. Quis ea a recusandae praesentium unde iusto dolor. Qui deserunt quis omnis quas culpa nostrum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(37, 'Doloribus sit eum et perspiciatis.', '1933-05-03', 1, 'Est commodi nihil ipsum doloremque est amet. Voluptates dicta laboriosam dolor ut. Quo incidunt est quo adipisci aperiam atque. Perspiciatis eos sed quia illum eligendi molestiae. Voluptate nam ut non repellat ut expedita quod ut. Quia dolores voluptatum possimus sunt enim et. Aliquid temporibus facere rerum quis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(38, 'Omnis rem dolore quos.', '1919-08-26', 1, 'Non eum voluptatibus tempore eum voluptate. Molestiae est sunt aspernatur et. Aut odit tenetur voluptatibus alias et et cumque. Laudantium illo deserunt sed sed. Id voluptatem exercitationem consequatur autem et dolorem delectus. Sint consequatur consectetur voluptas pariatur.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(39, 'Sapiente nostrum neque et.', '1952-07-08', 0, 'Optio quo repellendus aliquid eaque soluta ipsa. Voluptas voluptatem iusto fugit facilis blanditiis rerum asperiores. Ad cumque ducimus aperiam dolorem. Esse delectus vero quis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(40, 'Ipsam vel quasi facilis.', '2001-04-29', 0, 'Dolore quam molestiae corrupti provident quaerat ipsum. Asperiores quidem possimus totam hic maiores itaque. Voluptatum dolorum tenetur iste earum. Sint alias occaecati praesentium occaecati qui veritatis autem. Reiciendis eum qui laboriosam voluptas ut.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(41, 'Omnis excepturi at.', '1964-09-30', 1, 'Repellendus odit voluptatum occaecati dolores repellat dolore ipsum. Et reprehenderit sit et suscipit. Minima consequatur commodi perferendis. Id et et est dicta. Eaque aut officiis sapiente ex ipsa id.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(42, 'Mollitia porro adipisci id.', '1923-10-03', 0, 'Recusandae sunt repudiandae omnis et aperiam. Unde et aut ut quis. Autem aliquid corrupti molestiae reiciendis libero et. Ipsum ducimus molestias omnis neque aut id.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(43, 'Occaecati sed tempore inventore labore quos.', '2011-07-05', 0, 'Aut non dolorem molestias sint. Nihil tenetur libero eius. Porro reiciendis nulla sed quod ut est nulla velit. Delectus omnis at facere sint qui facere voluptates. Molestias ea accusamus quasi reiciendis. Maiores qui excepturi velit quo explicabo sequi. Non placeat consectetur repellendus provident eos.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(44, 'Accusamus et id.', '1988-09-19', 0, 'Assumenda aut culpa voluptatem consequuntur. Quos quos tenetur dolorum est nihil ea ipsum. Dolorem non id ratione eveniet. Illo voluptatem sit impedit ad deserunt molestiae eos laboriosam. Quasi a corrupti nihil delectus labore aut reprehenderit sed. Nostrum deleniti voluptatem adipisci ut.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(45, 'Velit veniam et a quae ratione.', '1948-10-01', 1, 'Aut beatae labore facilis ut ipsa id ad eos. Reprehenderit nobis maxime deleniti est omnis. Et corrupti dolor unde officia sequi impedit. Doloremque minus nihil quod facere. Aut possimus quidem nobis ab sunt rerum et. Consequatur hic autem quia.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(46, 'Consectetur molestias et.', '1948-03-06', 0, 'Excepturi corrupti quo sit. Quod nisi ipsam est aspernatur ex reprehenderit. Tenetur et voluptatem porro ullam ut. Ex ratione dolorem veniam iste velit explicabo. Culpa dicta et ratione qui harum est. Odit soluta vero qui quod. Fugit eligendi sed ipsa non vel.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(47, 'Facere velit distinctio in.', '1944-01-11', 0, 'Repellendus dolor vero et nobis sint dolores optio. Architecto nulla reiciendis dolores saepe fugiat accusantium voluptas voluptatem. Enim voluptatum dolores voluptas qui dolore ea iste. Rerum veritatis est autem harum et sunt fugiat. Ut suscipit est repellendus labore eum aspernatur. Iure neque quaerat enim minima voluptatibus id. A vero sunt ea modi.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(48, 'Optio ullam fuga.', '2013-01-28', 0, 'Nam suscipit minima dicta. Autem molestiae dolores doloribus est rerum id. Eos aspernatur est vitae molestiae maiores accusantium. Adipisci labore consequatur consectetur nulla sit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(49, 'Delectus illum iusto et.', '1963-12-09', 0, 'Dolor architecto exercitationem recusandae eveniet. Dolor voluptatum exercitationem expedita tempore saepe culpa aperiam. Magnam quas cum quia accusantium maxime totam quod qui. Deserunt suscipit omnis amet quam aut dolor. Adipisci enim reiciendis voluptatem occaecati aut reprehenderit necessitatibus. Vel esse et nostrum eligendi porro.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(50, 'Nihil nisi repudiandae accusantium id.', '1943-10-07', 1, 'Excepturi dolorum sed necessitatibus expedita. Qui molestiae praesentium inventore officia. Quisquam nam et temporibus deserunt tempora ipsa ipsum. Sit ducimus possimus enim et quae. Quia excepturi commodi deserunt nihil rerum architecto. Voluptatem quibusdam impedit perspiciatis quidem. Nobis ipsam dolores voluptatibus perferendis eum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01');

-- --------------------------------------------------------

--
-- Структура таблицы `pages`
--

CREATE TABLE `pages` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `lft` int(11) DEFAULT NULL,
  `rgt` int(11) DEFAULT NULL,
  `depth` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `pages`
--

INSERT INTO `pages` (`id`, `parent_id`, `lft`, `rgt`, `depth`, `order`, `title`, `text`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 2, 0, 0, 'Quis officia cupiditate aut.', 'Ut inventore quibusdam voluptate et doloremque nesciunt. Non aut aut delectus tempora corporis et. Quo suscipit tenetur perferendis ut sit eos. Reiciendis officiis maiores consequatur natus illo.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(2, NULL, 3, 4, 0, 0, 'Consequatur voluptatem praesentium unde.', 'Aut eaque nobis facere et enim placeat laborum. Id et ut labore rerum tenetur et aut. Id est quaerat eligendi velit sed corrupti. Nemo dicta qui dolorem molestias ipsam minima. Et odio reiciendis dolorem nesciunt earum. Praesentium id in nihil numquam dolor voluptates magnam.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(3, NULL, 5, 6, 0, 0, 'Architecto ipsam debitis dolor ut.', 'Hic ipsam ut fuga corrupti. Nemo earum consequatur pariatur ab ea. Possimus molestiae vel alias voluptatum neque necessitatibus sunt voluptas. Voluptas veniam pariatur pariatur. Ducimus aut quam iure deserunt.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(4, NULL, 7, 8, 0, 0, 'Nemo eos qui voluptatem iste distinctio.', 'Aspernatur et nostrum quis veritatis. Alias sapiente ut quod praesentium aliquam voluptas. Sed esse provident alias et voluptates. Dolor veritatis at tempora nihil a aut. In ab dolor blanditiis iste enim similique sit eligendi. Rerum reiciendis vero accusantium voluptates ut. Deleniti eum voluptatem itaque dolorem nihil.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(5, NULL, 9, 10, 0, 0, 'Suscipit nemo molestiae aut quo et eaque sit.', 'Dolores sit ut rerum quam tenetur. Iusto dolore reiciendis officiis fugit sit saepe sit recusandae. Qui veritatis inventore voluptates praesentium autem suscipit pariatur. Tempore officia nesciunt voluptates. Praesentium amet possimus minima vel. Sunt sapiente eaque velit at nihil iusto velit. Placeat doloremque sit ullam eos quidem.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(6, NULL, 11, 12, 0, 0, 'Enim eligendi laudantium enim unde.', 'Voluptatibus quaerat non et qui. Repellendus libero ut vel qui a voluptas quidem. Impedit enim quasi voluptates voluptatem sapiente quia. Non rerum maiores possimus distinctio laboriosam distinctio praesentium. Quisquam nihil harum quas sunt voluptas qui ut.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(7, NULL, 13, 14, 0, 0, 'Sint omnis similique accusantium.', 'Officiis quam quis ea. Ipsam doloremque adipisci quo odio. Dignissimos molestias quisquam voluptas consequuntur commodi sequi perspiciatis. Saepe qui quia nesciunt nisi minus eius.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(8, NULL, 15, 16, 0, 0, 'Neque natus omnis tempore exercitationem nobis nihil.', 'Quisquam ut sit fugiat nesciunt. Et aut omnis culpa ut et qui. Qui quia cumque corporis placeat expedita. Possimus minima aspernatur et mollitia et aut. Dolores exercitationem iure aut.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(9, NULL, 17, 18, 0, 0, 'Sed ea officiis deleniti consequatur.', 'Quia mollitia voluptas excepturi neque quidem ad. Amet nesciunt facere corrupti ipsum ea alias enim. Eius veniam quia quasi. Dolor nulla id voluptas autem. Et ut laudantium voluptates est debitis numquam. Quae mollitia aut delectus velit necessitatibus.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(10, NULL, 19, 20, 0, 0, 'Et voluptas esse corrupti.', 'Illum voluptatem dolorem sequi perspiciatis quae hic vero quia. Explicabo est voluptas eaque et et delectus dolore. Et corrupti enim similique est. Itaque quia voluptates autem et unde distinctio.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(11, NULL, 21, 22, 0, 0, 'Consequatur accusantium dolorem similique.', 'Sed sequi veniam vel perspiciatis optio velit. Quos ipsam officiis numquam et est voluptatem. Omnis qui qui aliquam ad dolor voluptas. Porro eligendi quidem aut totam impedit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(12, NULL, 23, 24, 0, 0, 'Ratione distinctio iste reiciendis et sed.', 'Asperiores ex expedita labore quidem. Asperiores aut porro sit. Placeat et non voluptatem ea hic aut necessitatibus. Debitis at itaque velit tempore temporibus. Cumque eius ea ut assumenda. Corporis non explicabo neque error quaerat quam.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(13, NULL, 25, 26, 0, 0, 'Mollitia sit distinctio in ducimus esse voluptas.', 'Distinctio a earum accusantium. Iste in nesciunt harum consequatur aut et esse. Aperiam voluptatum voluptate ullam optio. Ex eum asperiores sunt et repellat temporibus. Accusantium repudiandae odit nemo quis placeat voluptatem est. Natus non error repellat.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(14, NULL, 27, 28, 0, 0, 'Perspiciatis cupiditate nihil aut quos eos vel.', 'Soluta placeat doloribus voluptatem soluta libero occaecati. Voluptatem dignissimos placeat fugit. Ullam sit odio rerum est nisi. Debitis nihil cumque eveniet architecto sunt enim consequatur. Laudantium facere nisi in aut quia. Laboriosam repellat aut sed quia eius error.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(15, NULL, 29, 30, 0, 0, 'Rerum vel ducimus voluptatem.', 'Corporis minima odit natus nulla ex maiores eum. Neque qui dicta consectetur laudantium illum. Ullam alias quia incidunt ratione hic cupiditate. Libero rem doloribus sed dolorem officiis sed ratione. Libero doloremque nam labore ex autem. Earum consequatur rerum et dolor deleniti commodi. Eius quia nihil fuga quae ipsa.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(16, NULL, 31, 32, 0, 0, 'Doloremque enim et ullam cum iusto enim.', 'Dolorem rerum quae est alias mollitia. Dolore asperiores esse culpa corporis. Explicabo tempora nihil molestias facilis facere non. Temporibus eius dolores voluptas dolor atque. Sint suscipit labore aliquam qui. Et dolor ex sapiente numquam est.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(17, NULL, 33, 34, 0, 0, 'Ullam consequatur repudiandae saepe fugiat.', 'Id commodi tempore deleniti sit et. Inventore reiciendis vitae voluptas similique quia. Quidem neque corporis delectus animi eos sapiente. Veniam voluptas est et. Et animi nulla in accusamus quo iure. Consequatur sit quo debitis. Commodi numquam exercitationem voluptatum unde totam non voluptatum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(18, NULL, 35, 36, 0, 0, 'Vel eaque autem delectus doloremque voluptas officia.', 'Dolor asperiores nostrum quasi dicta et dolor. Laborum eaque numquam inventore consequuntur a eos. Unde dolor amet sequi nemo. Quisquam ipsa atque odit vel beatae blanditiis et. Aut dolores ducimus est voluptatem. Voluptatum recusandae est deserunt esse. Aut praesentium ad tempora est. Similique nemo consequatur libero.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(19, NULL, 37, 38, 0, 0, 'Tenetur inventore magnam nulla quos.', 'Exercitationem voluptatem sunt et perspiciatis qui ut perferendis corrupti. Iste laboriosam ab quia magni maiores. Doloribus nisi voluptate non eius quo nisi. Inventore id atque magnam id omnis perferendis voluptate. Vitae consectetur sed libero consequuntur illum vitae provident. Impedit a enim ipsam rerum ducimus porro quisquam. Quidem et eveniet aut aut sit quaerat.', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(20, NULL, 39, 40, 0, 0, 'Distinctio necessitatibus id reprehenderit recusandae.', 'Libero et voluptates sequi eveniet corporis. Molestias molestiae nam dolorum sed nesciunt accusantium. Ut omnis repellat eos excepturi. Iste ea ducimus ad magni ut autem consequatur. Explicabo pariatur totam sit dolor repellat. Nam rerum non facere ipsum explicabo sed sed. Natus culpa quasi sed vero.', '2018-05-29 04:15:01', '2018-05-29 04:15:01');

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `payment_orders`
--

CREATE TABLE `payment_orders` (
  `id` int(10) NOT NULL,
  `borrower_id` int(10) NOT NULL COMMENT 'Заёмщик',
  `borrower_loan_id` int(10) NOT NULL COMMENT 'Займ',
  `order_sum` decimal(10,0) DEFAULT NULL COMMENT 'Сумма заявки на оплату',
  `paid_sum` decimal(10,2) DEFAULT NULL COMMENT 'Оплаченная сумма',
  `pay_key` varchar(20) DEFAULT NULL COMMENT 'Источник оплаты',
  `pay_type` varchar(20) DEFAULT NULL COMMENT 'Тип оплаты',
  `is_paid` tinyint(1) DEFAULT NULL COMMENT 'Оплачено',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL COMMENT 'Является активным',
  `comment` varchar(255) DEFAULT NULL COMMENT 'Комментарий'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `payment_orders`
--

INSERT INTO `payment_orders` (`id`, `borrower_id`, `borrower_loan_id`, `order_sum`, `paid_sum`, `pay_key`, `pay_type`, `is_paid`, `created_at`, `updated_at`, `is_active`, `comment`) VALUES
(1, 25, 8, NULL, NULL, NULL, NULL, NULL, '2018-07-10 19:12:38', '2018-07-10 19:13:09', 0, NULL),
(2, 25, 8, NULL, NULL, NULL, NULL, NULL, '2018-07-10 19:13:09', '2018-07-10 19:24:35', 0, NULL),
(3, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-10 19:24:35', '2018-07-10 19:24:39', 0, NULL),
(4, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-10 19:24:39', '2018-07-10 19:29:25', 0, NULL),
(5, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-10 19:29:25', '2018-07-10 19:30:28', 0, NULL),
(6, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-10 19:30:28', '2018-07-10 19:31:23', 0, NULL),
(7, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-10 19:31:23', '2018-07-10 19:31:29', 0, NULL),
(8, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-10 19:31:29', '2018-07-10 19:32:41', 0, NULL),
(9, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-10 19:32:41', '2018-07-11 03:36:02', 0, NULL),
(10, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-11 03:36:02', '2018-07-11 05:56:27', 0, NULL),
(11, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-11 05:56:27', '2018-07-11 05:58:47', 0, NULL),
(12, 24, 7, '10750', NULL, NULL, 'repayment', NULL, '2018-07-11 05:56:39', '2018-07-11 05:56:44', 0, NULL),
(13, 24, 7, '10750', NULL, NULL, 'repayment', NULL, '2018-07-11 05:56:44', '2018-07-11 05:58:44', 0, NULL),
(14, 24, 7, '10750', NULL, NULL, 'repayment', NULL, '2018-07-11 05:58:44', '2018-07-11 12:13:02', 0, NULL),
(15, 25, 8, '10750', NULL, NULL, 'repayment', NULL, '2018-07-11 05:58:47', '2018-07-11 08:26:04', 0, NULL),
(16, 25, 8, '10750', '3.00', 'qiwi_terminal', 'repayment', NULL, '2018-07-11 08:26:04', '2018-07-11 08:43:28', 0, 'Сумма оплачена не полностью'),
(17, 25, 8, '10740', NULL, NULL, 'repayment', NULL, '2018-07-11 08:43:28', '2018-07-11 08:44:09', 0, NULL),
(18, 25, 8, '10740', '22000.00', 'qiwi_terminal', 'repayment', 1, '2018-07-11 08:44:09', '2018-07-11 08:54:29', 0, 'Сумма оплаты превышена на 11260'),
(19, 25, 8, '-74260', NULL, NULL, 'repayment', NULL, '2018-07-11 08:54:40', '2018-07-11 08:58:03', 0, NULL),
(20, 25, 8, '0', '1.00', 'qiwi_terminal', 'repayment', 1, '2018-07-11 08:58:03', '2018-07-11 09:12:38', 0, 'Сумма оплаты превышена на 1'),
(21, 25, 8, '0', NULL, NULL, 'repayment', NULL, '2018-07-11 12:00:07', '2018-07-11 12:11:35', 0, NULL),
(22, 25, 8, '0', NULL, NULL, 'repayment', NULL, '2018-07-11 12:11:35', '2018-07-11 12:12:40', 0, NULL),
(23, 25, 8, '0', NULL, NULL, 'repayment', NULL, '2018-07-11 12:12:40', '2018-07-11 12:12:40', 1, NULL),
(24, 24, 7, '10750', NULL, NULL, 'repayment', NULL, '2018-07-11 12:13:02', '2018-07-11 12:13:02', 1, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `permission_role`
--

CREATE TABLE `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `posts`
--

CREATE TABLE `posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `text_html` text COLLATE utf8_unicode_ci NOT NULL,
  `contact_id` int(10) UNSIGNED DEFAULT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `posts`
--

INSERT INTO `posts` (`id`, `title`, `text`, `created_at`, `updated_at`, `deleted_at`, `text_html`, `contact_id`, `country_id`) VALUES
(1, 'Culpa est quisquam consequatur laborum cum ratione.', 'Quibusdam expedita consequuntur ipsam accusantium et fuga corporis. Quas omnis consequatur totam et ut et consequatur. Accusantium excepturi quidem optio. Eveniet vero blanditiis consequatur maxime. Fugiat sed odit laborum soluta unde. Ad odit sapiente nisi. Veritatis tenetur fugiat sunt aut.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(2, 'Iusto commodi non error qui et.', 'Sed architecto voluptas et sed voluptatem eligendi. Vitae dolore porro sed non qui ex. Sed qui est iure recusandae quia voluptas et est. Consequuntur eligendi accusantium deleniti perferendis impedit maxime. Totam aut eum facilis perspiciatis. Eligendi officia sed natus vitae qui amet et labore.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(3, 'Beatae deleniti pariatur consequuntur praesentium molestiae nihil.', 'Nihil eos aspernatur fugiat cumque. Ut velit consequuntur in eum sit alias vitae. Et sit accusamus aspernatur quia. Earum quos facere unde a omnis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(4, 'Sed eos est ratione blanditiis.', 'Non aut facere ut modi earum quibusdam molestias. Corrupti aliquid sunt rem quia explicabo. Earum ut nam qui beatae cum suscipit quas recusandae. Dolorum autem quisquam est beatae praesentium eos. Consequatur rem ut nisi quasi minima. Eum non reiciendis accusantium aut voluptas ex.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(5, 'Quia voluptatum error aut.', 'Mollitia neque in quas mollitia vel ex itaque magnam. Praesentium corporis qui rem sapiente. Est libero alias error officiis ex reprehenderit. Voluptatibus magnam velit non molestiae harum at nulla praesentium. Dicta ut error et aperiam. Amet aut aut labore et. Nemo fugiat magni exercitationem dicta et.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(6, 'Tenetur modi et deleniti quaerat impedit expedita.', 'Aut illo optio accusantium. Praesentium vel consectetur provident assumenda expedita. Voluptates maxime a aut voluptatibus saepe dicta et. Non quam commodi nobis. Quae dicta explicabo molestiae aut eveniet.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(7, 'Officiis tempora dignissimos eos sed quae dolorem.', 'Sit odio deserunt suscipit rerum debitis. Aut dolorum at et. Est rem est cum tempora quis sit. Vitae libero sint debitis aut dolores est qui sit. Aut et sed optio quidem. Et hic suscipit est amet. Temporibus quo facere doloremque fugit libero deserunt eum molestiae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(8, 'Est repudiandae perspiciatis dolorum id.', 'Ut id at perspiciatis unde. Accusamus in libero molestias qui id at alias. Dolorem sint aperiam omnis sint delectus fugit. Quam et vel officia fugit repellat. Ducimus officiis voluptas provident ut et quia dolorem sed.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(9, 'Odio culpa esse qui soluta hic.', 'Et fugit fugit minus voluptatem ipsam. Doloribus dolor omnis hic et recusandae. Ad eos vel cum adipisci culpa. Perspiciatis laborum quis optio sunt qui. Itaque ab illo laboriosam beatae. Impedit dicta dicta voluptatem tempore qui. Voluptas aspernatur rem dicta qui laboriosam est et qui.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(10, 'Qui voluptatem velit et non.', 'Qui officia inventore accusamus id similique dicta dolores. Eos pariatur est qui nobis quasi. Culpa vel et suscipit. Ea neque minus ut id. Ut aut ratione rerum vitae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(11, 'Tenetur assumenda unde et voluptas.', 'Quia aliquam culpa quos sint a. Consequatur nisi expedita quia atque nemo ut. Exercitationem corporis tempore delectus. Autem ipsam reprehenderit qui quo. Nihil earum aut voluptas quas.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(12, 'Qui sequi reprehenderit eos odio et quis.', 'Et dolor cupiditate quis iusto quis laborum. Repellat sed aliquid ut nihil. Eos et enim aut. Corrupti quia voluptas eaque vel molestiae autem placeat. Dolores fuga quo voluptas nihil recusandae sunt.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(13, 'Laudantium facilis enim omnis distinctio.', 'Est explicabo ab sit explicabo fuga. Voluptas deserunt enim cum dolor iure et. Ea et sapiente dignissimos blanditiis. Reiciendis aspernatur quaerat et illum.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(14, 'Itaque et velit harum aliquid labore.', 'Nobis modi nobis laudantium odio quam delectus ea. Et quis nemo ducimus velit architecto nesciunt sunt. Ullam et voluptate modi delectus dolor. Pariatur reiciendis rerum voluptatem ipsa voluptatum. Inventore et et unde officiis aut quia aut. Cupiditate autem dolorem fugiat quae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(15, 'Nihil id error temporibus quo accusantium ratione.', 'Est ipsam omnis modi est omnis. Deserunt accusantium voluptatem id tempore ut. Ea vel qui enim officiis quis repellendus. Quos nemo voluptas cum esse beatae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(16, 'Vel harum harum qui ullam dicta.', 'Rerum veritatis aspernatur autem sed et sed. Non itaque qui tempora odit commodi. Et nesciunt quasi a nulla magni ducimus. Provident eum nesciunt optio ducimus. Quasi quis exercitationem rem odio.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(17, 'Dolorum labore a deleniti voluptatem illum.', 'Inventore quos expedita quasi eaque neque. Exercitationem voluptatem voluptatem itaque minima. Suscipit magni odit molestiae debitis quia labore delectus. Harum nobis voluptatem quae facere animi maxime.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(18, 'Optio et accusantium fugit sit molestias quis.', 'Sed sapiente harum vitae est ad consequatur. Magni dolores voluptatem atque occaecati est. Eos eos molestias laboriosam sapiente qui repudiandae ratione qui. Sapiente soluta maxime sit rem inventore consequatur.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(19, 'Voluptas laborum omnis est.', 'Dolorem illum ipsam a. Et ut iure odit voluptas illum possimus sequi voluptatibus. Ut aut et sunt qui ratione. Voluptate omnis amet animi earum itaque omnis temporibus. Sit perferendis quod quia. Deleniti molestiae repudiandae eaque odit rem.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(20, 'Inventore necessitatibus fuga facilis ut sint aut.', 'Exercitationem atque atque fugiat esse. Eligendi nam laudantium recusandae quos odio tempore nemo tempora. Ducimus iure voluptates nostrum aut. Nesciunt et autem voluptatum similique.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(21, 'Atque voluptatem perferendis corporis aliquam consequatur eos.', 'Voluptates aut aliquam officia. Velit ullam reprehenderit enim laboriosam velit accusamus esse. Voluptatem adipisci voluptates facere impedit ab quisquam numquam. Voluptatibus consequuntur nemo qui qui tempore beatae quisquam fugit. Quis dolores consequuntur explicabo iure.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(22, 'Odit libero quas omnis doloremque.', 'Est ab recusandae molestiae dolore quisquam voluptas. Est ipsa nemo et quod quisquam. Magni eum autem laboriosam esse harum omnis architecto. Ut officiis est et in facere non voluptate et. Ratione repudiandae aliquid ipsa ullam quam. Distinctio ipsum qui ea excepturi fuga quibusdam quae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(23, 'Quis facilis deserunt in corrupti itaque.', 'Voluptatem a et repellat. Placeat ut ut occaecati soluta cum est. Molestiae nam provident ea nemo et. Sed ut omnis dolores ut molestiae et. Qui recusandae quis reprehenderit quaerat deleniti accusamus. Quasi eligendi autem explicabo veniam sit maxime.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(24, 'Eos labore quae sint.', 'Dolores sint non atque. Pariatur ullam nobis necessitatibus provident natus recusandae. Esse mollitia excepturi et ea molestiae. Est a omnis officia.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(25, 'Laudantium quidem minus nam dolores aliquid ipsum qui.', 'Et error accusamus et aut exercitationem ut aut quibusdam. Minima laborum ut expedita laborum et labore. Sed reprehenderit necessitatibus nam dolorem. Ea ipsa earum dolorem quibusdam sed veniam error. Quam qui aut asperiores modi totam molestias rerum fugiat. Itaque similique unde repellat iste accusamus et. Praesentium voluptatem aut laborum quisquam.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(26, 'Sequi eos cumque veniam delectus.', 'Recusandae rerum unde autem voluptates commodi. Ducimus voluptatem voluptates deleniti accusantium dicta. Cupiditate labore harum facere possimus. Nihil odio et molestiae quisquam reprehenderit fuga placeat.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(27, 'Alias tenetur blanditiis quo.', 'Est et sed cumque magnam ducimus iste sint. Odio quas iste nihil totam. Accusamus mollitia odio eos velit soluta. Suscipit consequatur autem accusantium odio. Sint rerum repellendus molestias quam et vitae. Iure dolores necessitatibus numquam consequuntur natus.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(28, 'Assumenda et rerum fuga doloribus provident minima.', 'Voluptas libero a repellat id odit quod voluptatem. Non placeat vel sapiente accusantium in quo dolorum. Architecto magnam voluptates quam sed vel eligendi ratione odit. Ducimus sequi in quidem blanditiis aut. Distinctio ut sit aut rerum animi harum. Quia distinctio dolor iusto quas quas consequatur provident. Officia eum ut assumenda. Quod sequi aliquid consequatur vitae quod fugiat et voluptatem.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(29, 'Exercitationem voluptates accusamus molestiae esse praesentium aliquam.', 'Non incidunt laudantium ut quo. Accusantium quisquam quaerat rerum quia modi. Facere mollitia illum aut magni ducimus illum. Autem reiciendis ut harum omnis sit. Voluptatem deserunt et sed sint aut ut. Molestias dolores sint rerum nam porro repudiandae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(30, 'Autem quisquam beatae qui eaque in.', 'Quisquam inventore vel non illum tempore repellendus tenetur. Est eveniet vel ratione aut rerum. Delectus necessitatibus ab distinctio rem optio. Dolor facere modi consequatur et ratione doloribus. Ea est amet et. Ratione ut deleniti accusantium voluptatibus.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(31, 'Quaerat vel et est.', 'Pariatur sit nihil accusantium enim alias. Ullam adipisci reprehenderit numquam atque. Adipisci sunt ratione ad harum. Voluptates et officia assumenda ullam officiis. Dolorem aut perferendis dolor saepe delectus quis. Ipsa omnis accusamus tenetur quas quae recusandae occaecati. Quis perferendis voluptas dolorem et repellendus perspiciatis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(32, 'Ipsum labore officiis facere laudantium.', 'Explicabo iusto ex nesciunt incidunt. Earum quisquam reprehenderit aut quod vel qui at. Tempora enim blanditiis temporibus quaerat unde rerum voluptatem. Est est ipsam eius sint. Doloribus nisi rem quia mollitia in qui dolore.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(33, 'Distinctio molestiae aspernatur sit reiciendis.', 'Repellat quia optio culpa. Veniam ad iusto est voluptatem voluptate omnis nihil. Ad velit repudiandae velit repellat. Sapiente repudiandae tempora voluptas nemo et aut. Possimus ut veritatis a eum ipsam. Mollitia fugiat id quia ullam debitis quos fugit.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(34, 'Ut earum animi non porro quis.', 'Repudiandae explicabo et ex quia. Optio exercitationem et recusandae rerum similique veniam. Consequatur et corporis alias aut rerum. Excepturi voluptate rem aspernatur sint. Exercitationem soluta libero id ipsa sapiente praesentium quis. Maxime labore eaque et eum. Laudantium et quo consequuntur laudantium voluptates.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(35, 'Assumenda veritatis ipsum vero eum culpa.', 'Id et porro quo facere placeat ut. Quo maiores illo aut asperiores deserunt exercitationem. Atque ea aut qui a sit mollitia delectus. Exercitationem earum omnis esse modi excepturi earum. Expedita dolores omnis molestiae quo modi possimus.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(36, 'Consequatur sed qui voluptatum asperiores odit non.', 'Sequi excepturi quisquam sed nihil ut et id. Excepturi quod et quia aliquam. Consequuntur omnis et nihil molestiae et. Delectus dolores eveniet nesciunt nihil non explicabo. Autem et sed corporis esse quaerat cumque.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(37, 'Rem cupiditate consectetur distinctio.', 'Quaerat similique vero doloremque expedita. Ipsam alias quis quidem repellat. Minus aspernatur velit libero neque. Est et facere quaerat velit ullam vitae quia voluptas. Aut repellendus necessitatibus modi ut voluptas.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(38, 'Aut assumenda blanditiis minima inventore.', 'Assumenda dolore maxime modi qui assumenda libero. Ipsa enim fuga aspernatur consequatur amet consequatur. Aut qui dignissimos libero est ipsam odit laborum unde. Et quo et cupiditate alias. Aut quae voluptatem harum reprehenderit quo rerum deleniti. Assumenda ipsum illum in pariatur exercitationem quisquam.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(39, 'Autem omnis quidem soluta tempora corrupti sapiente.', 'Asperiores adipisci aperiam sed atque. Voluptatem ipsum voluptatem et. Repellat incidunt facilis exercitationem perspiciatis voluptas. Molestiae maiores aut nostrum sapiente voluptas. Ducimus officia in dicta et nisi nulla beatae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(40, 'Illo quo beatae tenetur.', 'Ut minus doloremque adipisci esse et. Non voluptatem reprehenderit inventore modi rerum. Expedita eos ut et iure repellat dolorem. Animi sit nihil nulla voluptatibus hic. Eos dolorem quia perspiciatis ducimus illum voluptate. Necessitatibus numquam et qui eligendi.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(41, 'Qui velit consectetur enim recusandae deserunt.', 'Vel quam laudantium omnis aut voluptatem autem porro. Quidem placeat quia aut blanditiis. Eveniet natus fuga ab. Et dicta consequatur minima ab dignissimos sapiente. Quis dolore dolorem optio quod voluptates veniam aperiam. Deserunt quia fugit et ipsam et architecto corporis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(42, 'Eos tempore aut blanditiis ut fugit.', 'Praesentium repellendus aut omnis minus. Non consequatur dolor sit nam similique aperiam. Nam placeat cumque totam et nobis fugit et rerum. Qui et vel vitae ut quidem natus iusto. Et incidunt molestiae perferendis aut accusamus voluptatem. Officiis aut laborum dolorum aliquam. Voluptas eos omnis qui.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(43, 'Perspiciatis ut ea dolor.', 'Consequatur voluptatem qui voluptates pariatur dolores. Repellendus voluptatem earum non voluptas mollitia consequatur non. Dignissimos voluptas aperiam magnam qui rerum enim. Neque voluptatem odio sint voluptas in voluptatem. Quaerat quaerat voluptas quam laborum omnis. Id facere dignissimos incidunt magnam harum exercitationem quis.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(44, 'Explicabo enim consequatur voluptatem amet illo dolores.', 'Asperiores fuga voluptatem non amet rerum molestias. Ratione est fuga minus officia. Nihil nam omnis nobis praesentium nesciunt ut et. Accusantium consequatur aut praesentium at voluptatem non perferendis. Earum earum amet rem animi. Amet neque odit qui doloremque nemo repellendus praesentium.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(45, 'Cumque dolor omnis ut aut.', 'Optio molestiae expedita excepturi dignissimos. Sed repellat ut aut. Saepe dolorem eum voluptatum reiciendis dolores. Cupiditate sit nihil cumque iusto cumque quos consequuntur hic.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(46, 'Qui et aperiam fugit eos aut.', 'Eaque a qui doloribus nostrum minus culpa in. Iure non tempora rem voluptates itaque. Odio optio sit non sit adipisci corporis sunt nostrum. Beatae rerum quidem veniam omnis fugit molestias molestias. Expedita a totam rem vitae.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(47, 'Et beatae culpa repudiandae est ut voluptatem.', 'Non non est vero quis dolore est et. Dolorem eius asperiores soluta quia earum qui. Omnis voluptatem sapiente delectus inventore soluta et eligendi eos. Iusto rem vel doloremque laborum blanditiis. Illum consequatur dolor earum aut. Ad et accusantium voluptatem. Sunt tempore eos accusamus necessitatibus ipsa eos.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '2018-05-29 04:15:01', '', NULL, NULL),
(48, 'Quia eos eius autem officia blanditiis excepturi.', 'Voluptatem aut velit qui nemo quis. Eos omnis expedita et quisquam voluptas. Sint ab omnis asperiores dolorem et sint eveniet. Laborum ut dolor mollitia suscipit omnis assumenda voluptas. Ratione fugit omnis enim illo est fugiat. Ipsam ut dignissimos molestias praesentium. Voluptatem aut amet natus aut quo saepe est.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(49, 'Est cupiditate doloremque eius temporibus et optio.', 'Ipsam voluptas tempore qui et accusantium. Velit quae voluptatibus odit ut ut expedita. Repellendus assumenda nihil suscipit culpa itaque. Aut est aut similique. Ut ea in amet dignissimos repellat quia. Minima sed non minus optio alias nostrum. Aut dolor iste laudantium quam voluptas aut ducimus.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL),
(50, 'Ipsa totam nihil nihil voluptatem.', 'Ea enim iure accusantium est exercitationem. Ea voluptatibus nesciunt architecto quis voluptatem. Quo rerum praesentium nostrum magnam ut et dolor. Quis nemo rem incidunt quia itaque laboriosam. Sint earum corporis culpa asperiores voluptatem maxime.', '2018-05-29 04:15:01', '2018-05-29 04:15:01', NULL, '', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `qiwi_transactions`
--

CREATE TABLE `qiwi_transactions` (
  `id` int(10) NOT NULL,
  `payment_order_id` int(10) NOT NULL COMMENT 'Заказ на оплату',
  `txn_id` varchar(50) DEFAULT NULL COMMENT 'Идетификатор платежа',
  `txn_date` datetime DEFAULT NULL COMMENT 'Дата платежа',
  `sum` decimal(10,2) DEFAULT NULL COMMENT 'Сумма платежа',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `qiwi_transactions`
--

INSERT INTO `qiwi_transactions` (`id`, `payment_order_id`, `txn_id`, `txn_date`, `sum`, `created_at`, `updated_at`) VALUES
(1, 0, NULL, '1970-01-01 00:00:00', NULL, '2018-07-11 08:34:58', '2018-07-11 08:34:58'),
(2, 16, NULL, '1970-01-01 00:00:00', '1.00', '2018-07-11 08:37:15', '2018-07-11 08:37:15'),
(3, 16, NULL, '1970-01-01 00:00:00', '1.00', '2018-07-11 08:38:20', '2018-07-11 08:38:20'),
(4, 16, NULL, '1970-01-01 00:00:00', '1.00', '2018-07-11 08:42:52', '2018-07-11 08:42:52'),
(5, 16, NULL, '1970-01-01 00:00:00', '1.00', '2018-07-11 08:42:58', '2018-07-11 08:42:58'),
(6, 16, NULL, '1970-01-01 00:00:00', '1.00', '2018-07-11 08:43:07', '2018-07-11 08:43:07'),
(7, 18, NULL, '1970-01-01 00:00:00', '1000.00', '2018-07-11 08:44:16', '2018-07-11 08:44:16'),
(8, 18, NULL, '1970-01-01 00:00:00', '1000.00', '2018-07-11 08:44:37', '2018-07-11 08:44:37'),
(9, 18, NULL, '1970-01-01 00:00:00', '5000.00', '2018-07-11 08:44:41', '2018-07-11 08:44:41'),
(10, 18, NULL, '1970-01-01 00:00:00', '5000.00', '2018-07-11 08:49:28', '2018-07-11 08:49:28'),
(11, 18, NULL, '1970-01-01 00:00:00', '5000.00', '2018-07-11 08:49:33', '2018-07-11 08:49:33'),
(12, 18, NULL, '1970-01-01 00:00:00', '5000.00', '2018-07-11 08:54:29', '2018-07-11 08:54:29'),
(13, 20, NULL, '1970-01-01 00:00:00', NULL, '2018-07-11 08:58:13', '2018-07-11 08:58:13'),
(14, 20, NULL, '1970-01-01 00:00:00', NULL, '2018-07-11 09:11:49', '2018-07-11 09:11:49'),
(15, 20, NULL, '1970-01-01 00:00:00', '1.00', '2018-07-11 09:12:38', '2018-07-11 09:12:38');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `label`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrator', '2018-05-29 04:15:01', '2018-05-29 04:15:01'),
(2, 'manager', 'Manager', '2018-05-29 04:15:01', '2018-05-29 04:15:01');

-- --------------------------------------------------------

--
-- Структура таблицы `role_user`
--

CREATE TABLE `role_user` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `role_user`
--

INSERT INTO `role_user` (`role_id`, `user_id`) VALUES
(1, 1),
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `salary_obtaining_methods`
--

CREATE TABLE `salary_obtaining_methods` (
  `id` int(2) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Способ получения зарплаты';

--
-- Дамп данных таблицы `salary_obtaining_methods`
--

INSERT INTO `salary_obtaining_methods` (`id`, `text`) VALUES
(1, 'Наличными в кассе'),
(2, 'На карту');

-- --------------------------------------------------------

--
-- Структура таблицы `some_another_models`
--

CREATE TABLE `some_another_models` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `some_another_models`
--

INSERT INTO `some_another_models` (`id`, `language_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Name', NULL, NULL),
(2, 1, 'Name 1', NULL, NULL),
(3, 1, 'Name 2', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `some_models`
--

CREATE TABLE `some_models` (
  `id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `some_models`
--

INSERT INTO `some_models` (`id`, `language_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Name', NULL, NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `time_stay_options`
--

CREATE TABLE `time_stay_options` (
  `id` int(2) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Время проживания по текущему адресу';

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`, `avatar`) VALUES
(1, 'admin', 'admin@avansplus.kz', '$2y$10$L6Fww9MZ3u.FQno1UkQmH./bWAqlWR7KvMRgJf6GjP94gga1yUzUC', 'HZ5n7TsuFfKOfW13PVmXeQNWc7nNWMy03Wz88z484VXdI5l8qWecjNCeBUH1', '2018-05-29 04:15:00', '2018-05-29 04:15:00', ''),
(2, 'manager', 'manager@site______.com', '$2y$10$kP9QI9bkmjoSVIFhoHKgsO9Q98UI/lBP2DPsN5LCnBB9U5DpLbruh', NULL, '2018-05-29 04:15:01', '2018-05-29 04:15:01', '');

-- --------------------------------------------------------

--
-- Структура таблицы `verified_debtors`
--

CREATE TABLE `verified_debtors` (
  `id` int(10) NOT NULL,
  `IIN` varchar(12) DEFAULT NULL COMMENT 'ИИН',
  `html_result` text COMMENT 'Резальтат в html таблице',
  `is_verified` tinyint(1) DEFAULT '0' COMMENT 'Прошел проверку'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `verified_debtors`
--

INSERT INTO `verified_debtors` (`id`, `IIN`, `html_result`, `is_verified`) VALUES
(1, '810305400330', '\n <thead><tr>\n<th>Должник</th>\n<th>Дата исполнительного производства</th>\n<th>Сущность требований</th>\n<th>Орган исполнительонго пр-ва, судебный исполнитель</th>\n<th>Орган, вынесший исполнительный документ</th> </tr></thead>\n<tbody>\n <tr class=\"odd\">\n<td><a href=\"/ru/disaerd/57DCDF51C012795EE0530501A8C0282F/nojs\" class=\"use-ajax disa_ajax_link\">КОНОНЕНКО ОЛЕСЯ ПАВЛОВНА, 05.03.1981</a></td>\n<td>29.08.2017</td>\n<td>Взыскать с КОНОНЕНКО ОЛЕСЯ ПАВЛОВНА  за нарушение ч.1 ст.437 КоАП РК сумму в размере 11345.0 тенге.</td>\n<td> Частные судебные исполнители г.Алматы. Байкушикова Гульжан Серикбаевна 87023030526</td>\n<td>(ОДП УВД БОСТАНДЫКСКОГО РАЙОНА Г.АЛМАТЫ                                )</td> </tr>\n</tbody>\n', 1),
(2, '930426301494', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `verified_restricted`
--

CREATE TABLE `verified_restricted` (
  `id` int(10) NOT NULL,
  `IIN` varchar(12) DEFAULT NULL COMMENT 'ИИН',
  `html_result` text COMMENT 'Резальтат в html таблице',
  `is_verified` tinyint(1) DEFAULT '0' COMMENT 'Прошел проверку'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `verified_restricted`
--

INSERT INTO `verified_restricted` (`id`, `IIN`, `html_result`, `is_verified`) VALUES
(1, '810305400330', NULL, 1),
(2, '930426301494', NULL, 1);

-- --------------------------------------------------------

--
-- Структура таблицы `verified_taxpayers`
--

CREATE TABLE `verified_taxpayers` (
  `id` int(10) NOT NULL,
  `IIN` varchar(12) DEFAULT NULL COMMENT 'ИИН',
  `html_result` text COMMENT 'Резальтат в html таблице',
  `is_verified` tinyint(1) DEFAULT '0' COMMENT 'Прошел проверку'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `verified_taxpayers`
--

INSERT INTO `verified_taxpayers` (`id`, `IIN`, `html_result`, `is_verified`) VALUES
(1, '810305400330', '\n			<thead>\n				<tr>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						№\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						Наименование\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						Тип\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						РНН\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						ИИН\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						Дата регистрации\n					</td>\n				</tr>\n			</thead>\n			<tbody>\n							<tr>\n								<td align=\"center\" style=\"vertical-align:middle;text-align:center\">\n									1\n								</td>\n								<td style=\"vertical-align:middle;\">\n									КОНОНЕНКО ОЛЕСЯ ПАВЛОВНА\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									ФЛ\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									600311331578\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									810305400330\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									11.06.1998\n								</td>\n							</tr>\n</tbody>\n', 1),
(2, '930426301494', '\n			<thead>\n				<tr>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						№\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						Наименование\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						Тип\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						РНН\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						ИИН\n					</td>\n					<td align=\"center\" style=\"font-weight:bold; vertical-align: middle\">\n						Дата регистрации\n					</td>\n				</tr>\n			</thead>\n			<tbody>\n							<tr>\n								<td align=\"center\" style=\"vertical-align:middle;text-align:center\">\n									1\n								</td>\n								<td style=\"vertical-align:middle;\">\n									ЯКОВЛЕВ АЛЕКСАНДР АНАТОЛЬЕВИЧ\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									ФЛ\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									270120281106\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									930426301494\n								</td>\n								<td align=\"center\" style=\"vertical-align:middle;\">\n									10.03.2004\n								</td>\n							</tr>\n</tbody>\n', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `work_experience_options`
--

CREATE TABLE `work_experience_options` (
  `id` int(2) NOT NULL,
  `text` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Общий стаж работы';

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `borrowers`
--
ALTER TABLE `borrowers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `phone_number` (`phone_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `firstname` (`firstname`),
  ADD KEY `lastname` (`lastname`),
  ADD KEY `fathername` (`fathername`),
  ADD KEY `borrower_status_id` (`borrower_status_id`);

--
-- Индексы таблицы `borrower_addresses`
--
ALTER TABLE `borrower_addresses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `borrower_id` (`borrower_id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `borrower_address_documents`
--
ALTER TABLE `borrower_address_documents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `borrower_id` (`borrower_id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `borrower_bank_accounts`
--
ALTER TABLE `borrower_bank_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- Индексы таблицы `borrower_bank_cards`
--
ALTER TABLE `borrower_bank_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `FKborrower_b912181` (`bank_id`),
  ADD KEY `FKborrower_b185473` (`borrower_id`);

--
-- Индексы таблицы `borrower_employments`
--
ALTER TABLE `borrower_employments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- Индексы таблицы `borrower_existing_loans`
--
ALTER TABLE `borrower_existing_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `FKborrower_e56941` (`borrower_id`),
  ADD KEY `FKborrower_e330232` (`bank_id`),
  ADD KEY `FKborrower_e892195` (`loan_type_id`),
  ADD KEY `FKborrower_e621432` (`currency_id`);

--
-- Индексы таблицы `borrower_identification_cards`
--
ALTER TABLE `borrower_identification_cards`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `IIN` (`IIN`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- Индексы таблицы `borrower_id_card_documents`
--
ALTER TABLE `borrower_id_card_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `borrower_id` (`borrower_id`);

--
-- Индексы таблицы `borrower_loans`
--
ALTER TABLE `borrower_loans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `dealy_days` (`dealy_days`),
  ADD KEY `loan_status_id` (`loan_status_id`),
  ADD KEY `loan_status_category_id` (`loan_status_category_id`),
  ADD KEY `is_active` (`is_active`);

--
-- Индексы таблицы `borrower_loan_agreement_documents`
--
ALTER TABLE `borrower_loan_agreement_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `borrower_pension_documents`
--
ALTER TABLE `borrower_pension_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `borrower_relative_contacts`
--
ALTER TABLE `borrower_relative_contacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `borrower_id` (`borrower_id`),
  ADD KEY `full_name` (`full_name`),
  ADD KEY `phone_number` (`phone_number`);

--
-- Индексы таблицы `borrower_statuses`
--
ALTER TABLE `borrower_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_title_unique` (`title`);

--
-- Индексы таблицы `company_contact`
--
ALTER TABLE `company_contact`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_pair` (`contact_id`,`company_id`),
  ADD KEY `company_contact_company_id_index` (`company_id`),
  ADD KEY `company_contact_contact_id_index` (`contact_id`);

--
-- Индексы таблицы `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `countries_title_unique` (`title`);

--
-- Индексы таблицы `currencies`
--
ALTER TABLE `currencies`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dialing_statuses`
--
ALTER TABLE `dialing_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `document_check_statuses`
--
ALTER TABLE `document_check_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `education_options`
--
ALTER TABLE `education_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `employment_period_options`
--
ALTER TABLE `employment_period_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `home_ownership_period_options`
--
ALTER TABLE `home_ownership_period_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `issued_authorities`
--
ALTER TABLE `issued_authorities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Индексы таблицы `loan_history_events`
--
ALTER TABLE `loan_history_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Индексы таблицы `loan_statuses`
--
ALTER TABLE `loan_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`),
  ADD KEY `FKloan_statu976049` (`loan_status_category_id`);

--
-- Индексы таблицы `loan_status_categories`
--
ALTER TABLE `loan_status_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `loan_types`
--
ALTER TABLE `loan_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `marital_statuses`
--
ALTER TABLE `marital_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `monthly_income_options`
--
ALTER TABLE `monthly_income_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Индексы таблицы `payment_orders`
--
ALTER TABLE `payment_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `permission_role`
--
ALTER TABLE `permission_role`
  ADD PRIMARY KEY (`permission_id`,`role_id`);

--
-- Индексы таблицы `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `qiwi_transactions`
--
ALTER TABLE `qiwi_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`role_id`,`user_id`);

--
-- Индексы таблицы `salary_obtaining_methods`
--
ALTER TABLE `salary_obtaining_methods`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `some_another_models`
--
ALTER TABLE `some_another_models`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `some_models`
--
ALTER TABLE `some_models`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `time_stay_options`
--
ALTER TABLE `time_stay_options`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Индексы таблицы `verified_debtors`
--
ALTER TABLE `verified_debtors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `iin` (`IIN`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `verified_restricted`
--
ALTER TABLE `verified_restricted`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IIN` (`IIN`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `verified_taxpayers`
--
ALTER TABLE `verified_taxpayers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `IIN` (`IIN`),
  ADD KEY `id` (`id`);

--
-- Индексы таблицы `work_experience_options`
--
ALTER TABLE `work_experience_options`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `banks`
--
ALTER TABLE `banks`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `borrowers`
--
ALTER TABLE `borrowers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `borrower_addresses`
--
ALTER TABLE `borrower_addresses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `borrower_address_documents`
--
ALTER TABLE `borrower_address_documents`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `borrower_bank_accounts`
--
ALTER TABLE `borrower_bank_accounts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Банковские карты заёмщика', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `borrower_bank_cards`
--
ALTER TABLE `borrower_bank_cards`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Банковские карты заёмщика';

--
-- AUTO_INCREMENT для таблицы `borrower_employments`
--
ALTER TABLE `borrower_employments`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `borrower_existing_loans`
--
ALTER TABLE `borrower_existing_loans`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `borrower_identification_cards`
--
ALTER TABLE `borrower_identification_cards`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `borrower_id_card_documents`
--
ALTER TABLE `borrower_id_card_documents`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `borrower_loans`
--
ALTER TABLE `borrower_loans`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `borrower_loan_agreement_documents`
--
ALTER TABLE `borrower_loan_agreement_documents`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `borrower_pension_documents`
--
ALTER TABLE `borrower_pension_documents`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `borrower_relative_contacts`
--
ALTER TABLE `borrower_relative_contacts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Контакты друзей и родсвтенников';

--
-- AUTO_INCREMENT для таблицы `borrower_statuses`
--
ALTER TABLE `borrower_statuses`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT для таблицы `company_contact`
--
ALTER TABLE `company_contact`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `currencies`
--
ALTER TABLE `currencies`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `dialing_statuses`
--
ALTER TABLE `dialing_statuses`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `document_check_statuses`
--
ALTER TABLE `document_check_statuses`
  MODIFY `id` tinyint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `education_options`
--
ALTER TABLE `education_options`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `employment_period_options`
--
ALTER TABLE `employment_period_options`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT для таблицы `genders`
--
ALTER TABLE `genders`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `home_ownership_period_options`
--
ALTER TABLE `home_ownership_period_options`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `issued_authorities`
--
ALTER TABLE `issued_authorities`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `loan_history_events`
--
ALTER TABLE `loan_history_events`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `loan_statuses`
--
ALTER TABLE `loan_statuses`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT для таблицы `loan_status_categories`
--
ALTER TABLE `loan_status_categories`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `loan_types`
--
ALTER TABLE `loan_types`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `marital_statuses`
--
ALTER TABLE `marital_statuses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT для таблицы `monthly_income_options`
--
ALTER TABLE `monthly_income_options`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `payment_orders`
--
ALTER TABLE `payment_orders`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT для таблицы `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT для таблицы `qiwi_transactions`
--
ALTER TABLE `qiwi_transactions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `salary_obtaining_methods`
--
ALTER TABLE `salary_obtaining_methods`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `some_another_models`
--
ALTER TABLE `some_another_models`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `some_models`
--
ALTER TABLE `some_models`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `time_stay_options`
--
ALTER TABLE `time_stay_options`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `verified_debtors`
--
ALTER TABLE `verified_debtors`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `verified_restricted`
--
ALTER TABLE `verified_restricted`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `verified_taxpayers`
--
ALTER TABLE `verified_taxpayers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `work_experience_options`
--
ALTER TABLE `work_experience_options`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `borrower_bank_cards`
--
ALTER TABLE `borrower_bank_cards`
  ADD CONSTRAINT `FKborrower_b185473` FOREIGN KEY (`borrower_id`) REFERENCES `borrowers` (`id`),
  ADD CONSTRAINT `FKborrower_b912181` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`);

--
-- Ограничения внешнего ключа таблицы `borrower_existing_loans`
--
ALTER TABLE `borrower_existing_loans`
  ADD CONSTRAINT `FKborrower_e330232` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`),
  ADD CONSTRAINT `FKborrower_e56941` FOREIGN KEY (`borrower_id`) REFERENCES `borrowers` (`id`),
  ADD CONSTRAINT `FKborrower_e621432` FOREIGN KEY (`currency_id`) REFERENCES `currencies` (`id`),
  ADD CONSTRAINT `FKborrower_e892195` FOREIGN KEY (`loan_type_id`) REFERENCES `loan_types` (`id`);

--
-- Ограничения внешнего ключа таблицы `loan_statuses`
--
ALTER TABLE `loan_statuses`
  ADD CONSTRAINT `FKloan_statu976049` FOREIGN KEY (`loan_status_category_id`) REFERENCES `loan_status_categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
