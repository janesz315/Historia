-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2025. Ápr 19. 09:56
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `historia`
--
CREATE DATABASE IF NOT EXISTS `testing-historia` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `testing-historia`;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `answers`
--

DROP TABLE IF EXISTS `answers`;
CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `questionId` int(11) NOT NULL,
  `rightAnswer` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=264 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `answers`
--

INSERT INTO `answers` (`id`, `answer`, `questionId`, `rightAnswer`, `created_at`, `updated_at`) VALUES
(1, '1311', 1, 0, '2025-04-10 06:14:17', '2025-04-10 06:42:38'),
(2, '1309', 1, 0, '2025-04-10 06:14:17', '2025-04-10 06:42:50'),
(3, '1312', 1, 1, '2025-04-10 06:14:17', '2025-04-10 06:42:59'),
(4, '1313', 1, 0, '2025-04-10 06:14:17', '2025-04-10 06:43:08'),
(5, 'Qui aliquam suscipit enim repudiandae sed nisi similique.', 2, 1, '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(6, 'Commodi aut aut eligendi natus aliquid ut recusandae.', 2, 0, '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(7, 'Quaerat nam voluptatum aliquid maxime vel.', 2, 0, '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(8, 'Dolor totam cumque et quibusdam.', 2, 0, '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(9, '1302', 3, 0, '2025-04-10 06:14:17', '2025-04-10 06:45:30'),
(10, '1301', 3, 1, '2025-04-10 06:14:17', '2025-04-10 06:45:37'),
(11, '1301-1321', 4, 0, '2025-04-10 06:26:55', '2025-04-10 06:27:01'),
(12, '1290-1301', 4, 1, '2025-04-10 06:27:27', '2025-04-10 06:27:35'),
(13, '1280-1295', 4, 0, '2025-04-10 06:27:38', '2025-04-10 06:27:45'),
(14, '1310-1330', 4, 0, '2025-04-10 06:28:03', '2025-04-10 06:28:10'),
(15, 'Borsa Kopasz', 5, 0, '2025-04-10 06:29:25', '2025-04-10 06:29:30'),
(16, 'Aba Amádé', 5, 0, '2025-04-10 06:29:31', '2025-04-10 06:29:37'),
(17, 'Csák Máté', 5, 1, '2025-04-10 06:29:40', '2025-04-10 06:29:46'),
(18, 'László Károly', 5, 0, '2025-04-10 06:29:48', '2025-04-10 06:29:54'),
(19, '1301', 6, 1, '2025-04-10 06:30:31', '2025-04-10 06:30:36'),
(20, '1309', 6, 0, '2025-04-10 06:30:39', '2025-04-10 06:30:46'),
(21, '1321', 6, 0, '2025-04-10 06:30:50', '2025-04-10 06:30:53'),
(22, '1310', 6, 0, '2025-04-10 06:30:55', '2025-04-10 06:31:00'),
(23, 'Károly Róbert', 7, 1, '2025-04-10 06:31:47', '2025-04-10 06:31:52'),
(24, 'Borsa Kopasz', 7, 0, '2025-04-10 06:31:54', '2025-04-10 06:31:59'),
(25, 'Aba Amádé', 7, 0, '2025-04-10 06:32:05', '2025-04-10 06:32:09'),
(26, 'Csák Máté', 7, 0, '2025-04-10 06:32:16', '2025-04-10 06:32:19'),
(27, 'Székesfehérvár', 8, 1, '2025-04-10 06:34:45', '2025-04-10 06:43:39'),
(28, 'Pécs', 8, 0, '2025-04-10 06:34:51', '2025-04-10 06:34:56'),
(29, 'Esztergom', 8, 0, '2025-04-10 06:35:00', '2025-04-10 06:43:35'),
(30, 'Pest', 8, 0, '2025-04-10 06:35:13', '2025-04-10 06:35:17'),
(31, 'Török Imre', 9, 0, '2025-04-10 06:36:00', '2025-04-10 06:36:06'),
(32, 'Nekcsei Demeter', 9, 1, '2025-04-10 06:36:09', '2025-04-10 06:36:15'),
(33, 'Borsa Kopasz', 9, 0, '2025-04-10 06:36:17', '2025-04-10 06:36:23'),
(34, 'László Károly', 9, 0, '2025-04-10 06:36:42', '2025-04-10 06:36:47'),
(35, 'Az uralkodó birtokainak növekedése', 10, 0, '2025-04-10 06:37:23', '2025-04-10 06:37:28'),
(36, 'A kincstár haszna a pénzrontásból', 10, 1, '2025-04-10 06:37:32', '2025-04-10 06:37:39'),
(37, 'A városok adója', 10, 0, '2025-04-10 06:37:41', '2025-04-10 06:37:47'),
(38, 'A királyi bandérium költségei', 10, 0, '2025-04-10 06:37:50', '2025-04-10 06:37:53'),
(39, 'A nemesfémek exportjának ösztönzése', 11, 0, '2025-04-10 06:38:21', '2025-04-10 06:38:24'),
(40, 'Az udvar költségeinek fedezése', 11, 0, '2025-04-10 06:38:27', '2025-04-10 06:38:34'),
(41, 'A jobbágyok pénzben történő adózása', 11, 1, '2025-04-10 06:38:28', '2025-04-10 06:38:42'),
(42, 'A földbirtokosok gazdasági felelőssége', 11, 0, '2025-04-10 06:38:46', '2025-04-10 06:38:50'),
(43, 'Erzsébet lengyel hercegnő', 12, 0, '2025-04-10 06:39:39', '2025-04-10 06:39:55'),
(44, 'Mária cseh hercegnő', 12, 0, '2025-04-10 06:39:59', '2025-04-10 06:40:05'),
(45, 'Hedvig királyné', 12, 0, '2025-04-10 06:40:00', '2025-04-10 06:40:11'),
(46, 'Lokietek Ulászló lánya', 12, 1, '2025-04-10 06:40:00', '2025-04-10 06:40:18'),
(47, 'András', 13, 0, '2025-04-10 06:40:46', '2025-04-10 06:41:11'),
(48, 'Lajos', 13, 1, '2025-04-10 06:40:56', '2025-04-10 06:41:24'),
(49, 'Miklós', 13, 0, '2025-04-10 06:40:57', '2025-04-10 06:41:20'),
(50, 'Károly', 13, 0, '2025-04-10 06:40:59', '2025-04-10 06:41:30'),
(51, 'A király által vert arany érmék, amelyek az ország pénzügyi alapját képezték', 14, 1, '2025-04-10 07:18:07', '2025-04-10 07:18:16'),
(52, 'A királyi birtokok területi egysége', 14, 0, '2025-04-10 07:18:18', '2025-04-10 07:18:24'),
(53, 'A nemesfémbányászat által generált jövedelem', 14, 0, '2025-04-10 07:18:27', '2025-04-10 07:18:33'),
(54, 'A királyi udvar hivatalos pénzneme', 14, 0, '2025-04-10 07:18:37', '2025-04-10 07:18:45'),
(55, 'A főpapok által beszedett adó', 15, 0, '2025-04-10 07:20:40', '2025-04-10 07:20:43'),
(56, 'A jobbágyok portánként fizetett adója', 15, 1, '2025-04-10 07:20:45', '2025-04-10 07:20:53'),
(57, 'A királyi birtokok jövedelme', 15, 0, '2025-04-10 07:20:56', '2025-04-10 07:21:01'),
(58, 'A kereskedelmi adó, amely a városokból származott', 15, 0, '2025-04-10 07:21:05', '2025-04-10 07:21:09'),
(59, 'Olyan főnemes, aki királyi jogokkal rendelkezett egy adott területen', 16, 1, '2025-04-10 07:21:50', '2025-04-10 07:22:00'),
(60, 'A királyi család egyik tagja', 16, 0, '2025-04-10 07:22:02', '2025-04-10 07:22:08'),
(61, 'Egyházi méltóság', 16, 0, '2025-04-10 07:22:11', '2025-04-10 07:22:15'),
(62, 'A királyi udvar legfőbb tanácsadója', 16, 0, '2025-04-10 07:22:19', '2025-04-10 07:22:23');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `category` varchar(255) NOT NULL,
  `level` varchar(10) NOT NULL,
  `text` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=327 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `categories`
--

INSERT INTO `categories` (`id`, `category`, `level`, `text`, `created_at`, `updated_at`) VALUES
(1, 'Károly Róbert uralkodásának jellemzői', 'közép', '<p><strong style=\"color: windowtext; background-color: transparent;\">1. Károly Róbert (1308/1310-1342) uralkodásának jellemzői</strong><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p><strong style=\"color: windowtext; background-color: transparent;\">A tartományúri hatalom kibontakozása</strong><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\">III. András (1290-1301): ügyes politikával és az egyház támogatásával sikerült hatalmát elfogadtatnia, de igazából komolyan függött a báróktól, akik egész országrészek felett uralkodtak ekkor már. Udvartartással, hadsereggel rendelkeztek, területükön uralkodói jogokkal bírtak, ezért nevezzük őket </span><strong style=\"color: windowtext; background-color: transparent;\">tartományuraknak</strong><span style=\"color: windowtext; background-color: transparent;\">, </span><strong style=\"color: windowtext; background-color: transparent;\">kiskirályoknak</strong><span style=\"color: windowtext; background-color: transparent;\">. Országos főméltóságokat is megszereztek, pl. nádor, vajda, bán. A legnagyobb közülük Csák Máté, a Felvidék északnyugati részét birtokló tartományúr volt. András 1301-ben bekövetkezett váratlan halálával férfiágon kihalt az Árpád-ház. Óriási trónharcok indultak.  </span></p><p class=\"ql-align-justify\"><strong style=\"color: windowtext; background-color: transparent;\">A tartományúri hatalom felszámolása</strong><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\">Számos leányági követelő jelentkezett, azonban a harcokból – az Árpádok </span><em style=\"color: windowtext; background-color: transparent;\">oldalági</em><span style=\"color: windowtext; background-color: transparent;\"> </span><em style=\"color: windowtext; background-color: transparent;\">rokonságából</em><span style=\"color: windowtext; background-color: transparent;\"> származó (V. István lányának, Máriának és Anjou II. Károlynak az unokája) - nápolyi Anjou-házhoz tartozó Károly Róbert (I. Károly) került ki győztesen. Már 1301-ben megkoronáztatta magát Esztergomban. A tartományúri hatalom felszámolásában érdekelt tartományurakra, főpapokra támaszkodott. Néhány kiskirály – országos tisztségek fejében – elismerte hatalmát, úgy mint Borsa Kopasz és Aba Amádé, majd később Csák Máté is. 1308: Pesten királlyá választották, 1309-ben pedig meg is koronázták. A </span><strong style=\"color: windowtext; background-color: transparent;\">végső koronázás 1310-ben következett be</strong><span style=\"color: windowtext; background-color: transparent;\">: a korszakban hivatalossá vált koronázási rend szerint került fejére a Szent Korona Székesfehérvárott, a ceremóniát az esztergomi érsek végezte el. </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\">Fokozatosan, a tartományúri hatalom felszámolásával tudta uralmát megszilárdítani, majd kiterjeszteni az egész országra. Támogatói között voltak a főpapok, nemesek, néhány város polgársága. A legerősebb tartományurat, Csák Mátét nem háborgatta, de a többieket egymás ellen játszotta ki. A </span><strong style=\"color: windowtext; background-color: transparent;\">rozgonyi</strong><span style=\"color: windowtext; background-color: transparent;\"> </span><strong style=\"color: windowtext; background-color: transparent;\">csatában</strong><span style=\"color: windowtext; background-color: transparent;\"> (1312) legyőzte a szász polgárokkal összefogva az Abákat. Fegyverrel törte meg a Kőszegiek és Borsa Kopasz uralmát is. Csák Máté terültére azonban annak halála után tehette a kezét, 1321-ben. </span></p><p class=\"ql-align-justify\"><strong style=\"color: windowtext; background-color: transparent;\">Belpolitikája</strong><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\">A tartományurak leverésével növelte a királyi birtokállományt, és személyéhez hű bárói réteget hozott létre: Garaiak, Laczfiak. A bárók jövedelme nagy mértékben a király kegyétől függött. Károly ugyanis méltóságokat (pl. nádor, vajda, tárnokmester) adományozott nekik. E címekkel együtt járt bizonyos királyi várak és földek (honorbirtokok) birtoklása is. Károly erős királyi </span><strong style=\"color: windowtext; background-color: transparent;\">bandériumot</strong><span style=\"color: windowtext; background-color: transparent;\"> tartott fenn hatalmának biztosítására. A bandérium (zászló, zászlóalj) az egy zászló alatt felvonuló katonák egységét jelentette. A legfőbb egyházi és világi méltóságok jogot kaptak saját zászlóik alatt felvonultatni fegyvereseiket, mely szintén hozzájárult az ország haderejéhez.  </span></p><p class=\"ql-align-justify\"><strong style=\"color: windowtext; background-color: transparent;\">Gazdasági reformja</strong><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\">Károly Róbert volt az első olyan magyar király, aki átgondoltan foglalkozott </span><strong style=\"color: windowtext; background-color: transparent;\">gazdaságpolitikával</strong><span style=\"color: windowtext; background-color: transparent;\">. Kidolgozója Nekcsei Demeter tárnokmester volt. Az udvar és a haderő költségei a jövedelmek növelésére ösztönözték Károlyt. A </span><strong style=\"color: windowtext; background-color: transparent;\">regálékból</strong><span style=\"color: windowtext; background-color: transparent;\"> származó bevételek emelését az ország gazdasági fejlődése és ásványkincsekben való gazdagsága (a világ aranytermelésének a magyar arany adta az 1/3-adát, ezüst) tette lehetővé. A </span><strong style=\"color: windowtext; background-color: transparent;\">nemesfémbányászat</strong><span style=\"color: windowtext; background-color: transparent;\"> fellendülése érdekében bányászokat telepített az országba, és a földbirtokosokat is érdekeltté tette a bányászatban. Régebben az a terület, melyen bányát fedeztek fel, csere útján a királyé lett. Ezen változtat Károly: engedélyezte a földesúri birtokokon az ércbányák nyitását. A birtokosok a bányáikból megkapták a királynak fizetett bányabér (</span><strong style=\"color: windowtext; background-color: transparent;\">úrbura</strong><span style=\"color: windowtext; background-color: transparent;\">) 1/3-adát. A legnagyobb hasznot a királyi nemesfém-monopólium jelentette: nemesfémmel csak a király kereskedhetett, a kitermelt nemesfémet nyers állapotban kellett a királyhoz beszolgáltatni, ezért cserébe vert pénzt adtak. Ezek a pénzek azonban akár 40-50%-kal kevesebb nemesfémet tartalmaztak a beszolgáltatottnál, ez volt a pénzrontás. (A kincstári hasznot </span><em style=\"color: windowtext; background-color: transparent;\">kamara</em><span style=\"color: windowtext; background-color: transparent;\"> </span><em style=\"color: windowtext; background-color: transparent;\">haszná</em><span style=\"color: windowtext; background-color: transparent;\">nak nevezték.) Magyarország aranytermelésben élvonalon járt, az uralkodó hatalmas jövedelemre tette szert: </span><strong style=\"color: windowtext; background-color: transparent;\">értékálló</strong><span style=\"color: windowtext; background-color: transparent;\"> </span><strong style=\"color: windowtext; background-color: transparent;\">aranypénzt</strong><span style=\"color: windowtext; background-color: transparent;\"> (aranyforint) veretett firenzei mintára, ezzel megszűnt a kötelező pénzbeváltás. A kamara haszna kiesett jövedelmeit pótlandó bevezették a </span><strong style=\"color: windowtext; background-color: transparent;\">kapuadót</strong><span style=\"color: windowtext; background-color: transparent;\">: kapunként, vagyis portánként kellett fizetni akkor is, ha több jobbágycsalád élt a portán.) Így a jobbágy pénzben történő adózásának bevezetése is megtörtént. A kibontakozó árutermelés a </span><strong style=\"color: windowtext; background-color: transparent;\">városok</strong><span style=\"color: windowtext; background-color: transparent;\"> </span><strong style=\"color: windowtext; background-color: transparent;\">fejlődéséhez</strong><span style=\"color: windowtext; background-color: transparent;\"> is hozzájárult: kevés olyan nyugati város jött létre, melyekben megjelentek a céhek. Ezek fallal körülvett szabad királyi városok voltak, bányavárosok. A városok zöme földesúri joghatóság alatt álló mezővárosok voltak, melyek egy összegben adóztak. A távolsági kereskedelmet megadóztató </span><strong style=\"color: windowtext; background-color: transparent;\">harmincadvám</strong><span style=\"color: windowtext; background-color: transparent;\"> is jelentős bevételeket hozott: a szövetekért, fegyverekért, fémárukért aranypénzzel, élelmiszerekkel (bor, élő marha) fizettek. </span></p><p class=\"ql-align-justify\"><strong style=\"color: windowtext; background-color: transparent;\">Külpolitikája, utódlás</strong><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\">Igyekezett rokoni kapcsolatokat kiépíteni uralkodócsaládokkal: a lengyel Lokietek Ulászló lányát, Erzsébetet vette feleségül. Az </span><strong style=\"color: windowtext; background-color: transparent;\">1335-ös visegrádi királytalálkozón</strong><span style=\"color: windowtext; background-color: transparent;\"> megbékítette Lokietek Kázmér lengyel, és Luxemburgi János cseh uralkodókat. A lengyel uralkodó elismerte Szilézia elvesztését, a cseh uralkodóval pedig megegyezett, az árumegállító joggal rendelkező Bécset kikerülő kereskedelmi útvonal megnyitásáról. Lajos – elsőszülött fia – a lengyel trónt is örökölte, a kisebbik fia, András igényét a nápolyi trónra házassági szerződéssel alapozta meg.  </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p class=\"ql-align-justify\"><span style=\"color: windowtext; background-color: transparent;\"> </span></p><p><br></p>', '2025-04-10 06:14:17', '2025-04-10 06:17:24'),
(2, 'A nagy földrajzi felfedezések', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(3, 'A klasszikus ipari forradalom', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(4, 'A középkori város', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(5, 'Nemzetiségek a dualizmusban', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(6, 'Géza és Szent István tevékenysége', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(7, 'Széchenyi István és Kossuth Lajos reformtörekvései', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(8, 'Gulyáskommunizmus', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(9, 'A tatárjárás', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(10, 'Az athéni demokrácia működése', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(11, 'A kétpólusú világrend kialakulása', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(12, 'Az ellenforradalmi rendszer konszolidációjának legfontosabb lépései', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(13, 'Mária Terézia és II. József', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(14, 'A nemzetszocializmus hatalomra jutása és működési mechanizmusa', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(15, 'A kiegyezés', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(16, 'Az 1956-os forradalom és szabadságharc Magyarországon', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(17, 'A mohácsi csatavesztés és következményei', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(18, 'A szövetségi rendszerek kialakulása', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(19, 'Az első világháborút lezáró békerendszer', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(20, 'Magyarország háborúba lépése, és részvételét és a Szovjetúnió elleni háborúban betöltött szerepe', 'közép', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(21, 'Mezőgazdasági és ipari termelés, kereskedelem a középkorban és a kora újkori Európában', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(22, 'Magyarország gazdasága a 14–15. században', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(23, 'A világgazdaság a 20–21. században (világgazdasági válság, európai integráció, a világgazdaság átalakulása az ezredfordulón: hagyományos és új centrumok, a globális gazdaság)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(24, 'Magyarország gazdasága 1867-től napjainkig (gazdasági változások a dualizmus korában, a trianoni béke gazdasági hatásai, gazdasági konszolidáció az 1920-as években és a gazdasági válság, Rákosi- és Kádár-korszak gazdasága, gazdasági rendszerváltoztatás)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(25, 'Erdély etnikai és vallási helyzete a 16–18. században', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(26, 'Demográfiai és etnikai változások, népesedés- és nemzetiségi politika Magyarországon a 18–20. században', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(27, 'Az ipari forradalmak társadalmi háttere és hatásai', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(28, 'Az életmód és a mindennapok Magyarországon a 20. században (Horthy-korszak, második világháború, Rákosi-korszak, Kádár-korszak)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(29, 'A határon túli magyarok, a magyarországi nemzetiségek és a cigányság helyzete napjainkban', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(30, 'A keresztény államalapítás a Kárpát-medencében és az Árpád-kor', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(31, 'Rendi fejlődés Magyarországon (Aranybulla, az 1351. évi törvények, Hunyadi Mátyás központosított rendi állama)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(32, 'A középkor és a kora újkor kultúrája Európában és Magyarországon (egyházi és lovagi kultúra, középkori egyetemek, román, gótikus és reneszánsz építészet, barokk)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(33, 'Rendi és abszolutista törekvések Magyarországon, a magyar rendek és a Habsburg udvar konfliktusai és együttműködése a 17–18. században', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(34, 'Az emberi jogok és a jogegyenlőség elve, az állampolgári jogok, kötelességek (felvilágosodás, az Emberi és polgári jogok nyilatkozata, Magyarország Alaptörvénye)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(35, 'A modern demokráciák 17–18. századi gyökerei (angol alkotmányos monarchia, az Egyesült Államok alkotmánya)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(36, 'A polgári átalakulás programja és megvalósulása a 19. századi Magyarországon (a reformkor fő kérdései, Széchenyi és Kossuth reformprogramja, áprilisi törvények, kiegyezés)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(37, 'A polgári nemzetállam jellemzői, alkotmányosság és jogegyenlőség (Németország, az Egyesült Államok, Magyarország)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(38, 'Az Európai Unió és Magyarország az Európai Unióban', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(39, 'A mai Magyarország politikai intézményrendszere és választási rendszere', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(40, 'Ókori államberendezkedések (Athén, Róma – Julius Caesar egyeduralmi kísérlete, Augustus principátusa)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(41, 'Vallások (politeizmus az ókori Keleten, monoteista vallások)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(42, 'A reformáció és a katolikus megújulás Európában és Magyarországon', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(43, 'Magyarország a Habsburg Birodalomban a 18. században (Pragmatica Sanctio, kormányzat)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(44, 'A 19. század eszméi', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(45, 'Politikai eszmék és pártrendszer a dualizmus kori Magyarországon', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(46, 'Kommunista hatalomátvételek (bolsevik hatalomátvétel Oroszországban, a magyar Tanácsköztársaság, Magyarország szovjetizálása 1945–1949)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(47, 'Magyar–török küzdelmek a 15–17. században', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(48, 'Az első világháború és következményei (nagyhatalmi érdekek és konfliktusok az imperializmus korában, kirobbanása, hadviselők, nyugati front, a háború jellege, a világháborút lezáró békerendszer)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(49, 'A második világháború (egyetemes és magyar történelem)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(50, 'A kétpólusú világrend (kialakulása, jellemzői, a két blokk berendezkedése, konfliktusok, felbomlása)', 'emelt', '', '2025-04-10 06:14:17', '2025-04-10 06:14:17');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=1365 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_00_00000_create_roles_table', 1),
(2, '0001_01_01_000000_create_users_table', 1),
(3, '0001_01_01_000001_create_cache_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '2024_11_25_101625_create_personal_access_tokens_table', 1),
(6, '2025_01_31_105257_create_categories_table', 1),
(7, '2025_02_04_081959_create_sources_table', 1),
(8, '2025_02_04_083546_create_question_types_table', 1),
(9, '2025_02_04_093314_create_questions_table', 1),
(10, '2025_02_04_095044_create_answers_table', 1),
(11, '2025_02_04_101600_create_user_tests_table', 1),
(12, '2025_02_04_101700_create_test_questions_table', 1);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 1, 'access', '46032a6d3af359cf4ba062a0235afca751f6b80d7e1bbdc5c929b3c2afa59bd5', '[\"*\"]', NULL, NULL, '2025-04-10 06:15:44', '2025-04-10 06:15:44'),
(2, 'App\\Models\\User', 1, 'access', '4c9617b9edd49b6711dffd014ed73c866a8059c115ca67ede06771b1b363e9b8', '[\"*\"]', NULL, NULL, '2025-04-17 06:03:28', '2025-04-17 06:03:28');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `questionTypeId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=1024 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `questions`
--

INSERT INTO `questions` (`id`, `question`, `questionTypeId`, `categoryId`, `created_at`, `updated_at`) VALUES
(1, 'Mikor volt a Rozgonyi csata?', 1, 1, NULL, NULL),
(2, 'Mit jelent az asszimiláció', 2, 2, NULL, NULL),
(3, 'Mikor halt ki az Árpád-ház?', 1, 1, NULL, '2025-04-10 07:12:01'),
(4, 'Mikor uralkodott III. András?', 1, 1, '2025-04-10 06:26:51', '2025-04-10 06:26:51'),
(5, 'Ki volt a legnagyobb tartományúr III. András idején?', 3, 1, '2025-04-10 06:29:13', '2025-04-10 06:29:13'),
(6, 'Mikor halt meg III. András?', 1, 1, '2025-04-10 06:30:21', '2025-04-10 06:30:21'),
(7, 'Ki lett a győztes a trónharcokban 1301 után?', 3, 1, '2025-04-10 06:31:34', '2025-04-10 06:31:34'),
(8, 'Hol koronázták meg Károly Róbertet 1310-ben?', 4, 1, '2025-04-10 06:34:40', '2025-04-10 06:43:30'),
(9, 'Kinek volt szerepe Károly Róbert gazdasági reformjaiban?', 3, 1, '2025-04-10 06:35:55', '2025-04-10 06:35:55'),
(10, 'Mi volt a \"kamara haszna\"?', 2, 1, '2025-04-10 06:37:18', '2025-04-10 06:37:18'),
(11, 'Mi volt a célja a kapuadónak?', 2, 1, '2025-04-10 06:38:13', '2025-04-10 06:38:13'),
(12, 'Kivel házasodott Károly Róbert, hogy megerősítse rokoni kapcsolatait?', 3, 1, '2025-04-10 06:39:17', '2025-04-10 06:39:17'),
(13, 'Ki örökölte a lengyel trónt Károly Róbert fia közül?', 3, 1, '2025-04-10 06:40:41', '2025-04-10 06:40:41'),
(14, 'Mi az \"aranyforint\"?', 2, 1, '2025-04-10 07:18:02', '2025-04-10 07:18:02'),
(15, 'Mi a \"kapuadó\"?', 2, 1, '2025-04-10 07:20:36', '2025-04-10 07:20:36'),
(16, 'Mi a \"kiskirály\"?', 2, 1, '2025-04-10 07:21:46', '2025-04-10 07:21:46');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `question_types`
--

DROP TABLE IF EXISTS `question_types`;
CREATE TABLE `question_types` (
  `id` int(11) NOT NULL,
  `questionCategory` varchar(30) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=4096 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `question_types`
--

INSERT INTO `question_types` (`id`, `questionCategory`, `created_at`, `updated_at`) VALUES
(1, 'Évszám', NULL, '2025-04-10 06:33:41'),
(2, 'Fogalom', NULL, '2025-04-10 06:33:48'),
(3, 'Személy', NULL, '2025-04-10 06:33:52'),
(4, 'Helyszín', '2025-04-10 06:34:13', '2025-04-10 06:34:13');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `roles`
--

INSERT INTO `roles` (`id`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'user', NULL, NULL);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `sources`
--

DROP TABLE IF EXISTS `sources`;
CREATE TABLE `sources` (
  `id` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `sourceLink` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=321 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `sources`
--

INSERT INTO `sources` (`id`, `categoryId`, `sourceLink`, `note`, `created_at`, `updated_at`) VALUES
(1, 22, 'http://gusikowski.com/molestiae-hic-asperiores-voluptatem-inventore', 'Accusamus aut sint ducimus soluta.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(2, 2, 'https://bernhard.com/nemo-hic-rerum-quas-vitae-dolorum-nostrum-quis.html', 'Maxime ipsam officia adipisci non dignissimos.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(3, 18, 'http://hintz.biz/error-officiis-et-corporis-totam', 'Enim et perferendis exercitationem aliquam.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(4, 32, 'http://block.info/libero-vel-et-qui-doloremque-sit', 'Eligendi tempore culpa tempore nulla distinctio velit.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(5, 6, 'http://murphy.com/voluptatum-quis-odio-sunt-quasi-explicabo.html', 'Quibusdam quis rerum laudantium.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(6, 4, 'http://www.zulauf.biz/perferendis-iure-fuga-molestias-iusto-quidem', 'Inventore neque neque iste qui esse.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(7, 15, 'http://kshlerin.com/voluptatibus-illum-eligendi-accusantium-quae-quam-ad-iusto', 'Repudiandae sit est animi a voluptate earum voluptas.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(8, 6, 'http://spencer.com/vero-distinctio-id-mollitia-assumenda-et-maiores-sunt-cumque.html', 'Enim reprehenderit quis quas non officia ut ullam.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(9, 45, 'http://mertz.biz/', 'Quia odio dolorem sunt consequatur sit voluptatum vitae.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(10, 10, 'http://koss.com/', 'Quaerat dignissimos ea maiores maxime.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(11, 14, 'http://schmitt.com/error-fugiat-magnam-beatae-tempore-rerum-ut', 'Eum voluptatem itaque a optio id quibusdam pariatur ut.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(12, 30, 'http://www.goyette.com/quae-minima-ratione-assumenda-nam-pariatur', 'Odio sit quia aut eum et.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(13, 11, 'http://www.wolff.com/', 'Eos doloremque recusandae voluptatibus non voluptatem.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(14, 20, 'http://stark.com/tenetur-non-nam-totam-incidunt-cupiditate-dicta-non.html', 'Quas placeat explicabo ipsam molestias sint.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(15, 49, 'http://www.luettgen.com/ad-alias-explicabo-veritatis-illum-culpa-exercitationem-minima', 'Dolores est ullam repellendus facere architecto nemo.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(16, 40, 'http://larson.com/in-optio-vero-nam', 'Consequatur sint quia autem eum.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(17, 37, 'http://waters.biz/minus-culpa-ullam-iste-similique', 'Facere error omnis et ducimus quibusdam.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(18, 31, 'http://weissnat.com/doloremque-magnam-facilis-cupiditate-quia-ullam-quibusdam', 'Voluptatum perferendis rerum molestiae quos velit expedita.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(19, 9, 'http://kub.com/ut-qui-sed-cum-accusamus-laboriosam-sit', 'Eveniet quae ullam nobis laborum qui nobis non.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(20, 8, 'http://www.oconner.net/debitis-accusantium-et-molestias-blanditiis-in-accusantium-quis.html', 'Nisi id rem magni quae maxime.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(21, 19, 'http://cummerata.net/et-expedita-vitae-quia-voluptatibus-sunt-ipsam-quibusdam', 'Rerum qui non dolorem porro quia iste impedit quas.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(22, 40, 'http://www.upton.com/', 'Quo cupiditate explicabo ut in similique libero quia.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(23, 39, 'http://bartell.org/consequuntur-voluptate-quia-dolorem-facilis-voluptates-et-mollitia-cupiditate', 'Reprehenderit dolor explicabo enim facilis.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(24, 19, 'http://kihn.com/dolor-aliquid-et-sit-facilis', 'Necessitatibus voluptates nulla libero cum.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(25, 24, 'https://www.hodkiewicz.com/quos-corporis-est-dolor-tenetur', 'Ea at autem enim odio reprehenderit.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(26, 27, 'http://www.pfannerstill.biz/', 'Officiis nam non laborum temporibus ullam et dolorem.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(27, 20, 'http://www.weimann.info/dolores-blanditiis-quisquam-iusto-mollitia', 'Libero illo excepturi esse minima omnis impedit sed.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(28, 25, 'http://www.durgan.com/', 'Similique corporis velit nihil sed.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(29, 33, 'http://wolf.info/', 'Veritatis vitae et error quisquam asperiores dolores aut.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(30, 11, 'http://www.tremblay.org/', 'Suscipit aliquam id ratione repellat inventore repellat.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(31, 7, 'http://dicki.net/ut-eos-dolorum-et-sit-unde-esse-qui', 'Dolores quis aliquid non a animi quis.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(32, 39, 'http://www.schmitt.net/aut-nisi-voluptatem-similique-et-inventore-nostrum-alias', 'Qui quis laborum deserunt porro qui.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(33, 46, 'http://renner.net/mollitia-tempore-reprehenderit-distinctio-nam-ullam', 'Eveniet animi fugiat suscipit voluptas nemo eius placeat.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(34, 35, 'http://www.hilpert.biz/', 'Commodi et quos laborum vero.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(35, 23, 'http://www.frami.org/', 'Amet aspernatur repellendus qui iusto quidem molestiae quidem.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(36, 39, 'https://waters.org/id-asperiores-mollitia-ut-nesciunt-vel-accusamus-occaecati.html', 'Quaerat et magni cupiditate qui perspiciatis ex.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(37, 36, 'https://tromp.com/at-sit-sapiente-assumenda-neque.html', 'Mollitia velit et totam et consequatur.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(38, 4, 'http://www.funk.net/facilis-id-ea-ut-saepe-quibusdam.html', 'Corporis ipsam deleniti in quia.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(39, 39, 'http://www.gleason.info/', 'Accusamus non earum corporis culpa ad temporibus porro veniam.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(40, 27, 'http://rolfson.com/rerum-omnis-in-mollitia-eveniet-rerum-tenetur', 'Atque voluptas magni neque debitis libero maiores.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(41, 25, 'http://murphy.com/quis-cupiditate-magni-molestias-quod-totam.html', 'Quaerat et iste aut voluptas reprehenderit minus ad.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(42, 38, 'http://waelchi.com/', 'Adipisci ab sequi voluptatem ipsum ex sint velit.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(43, 45, 'http://www.mann.org/adipisci-fugit-iusto-quaerat-facere-est-nam-pariatur', 'Doloribus voluptatem debitis maiores consequatur perferendis et deserunt.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(44, 28, 'http://www.ondricka.com/voluptate-at-consectetur-ab-et-ipsa-sed-in.html', 'Quod perspiciatis pariatur labore non ut architecto.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(45, 6, 'http://buckridge.biz/cumque-corrupti-ad-dolorem-rerum-voluptatem-in-enim-corrupti', 'Facere odio tenetur est autem recusandae voluptatem sit.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(46, 10, 'https://www.oconner.com/non-hic-qui-quam-nemo-magni', 'Aut rerum soluta quos exercitationem.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(47, 1, 'https://tudasbazis.sulinet.hu/hu/tarsadalomtudomanyok/tortenelem/a-kozepkor-tortenete-476-1492/magyarorszag-a-14-15-szazadban/karoly-robert-uralkodasa', 'Károly Róbert küzdelme az oligarchákkal', '2025-04-10 06:14:17', '2025-04-10 06:21:53'),
(48, 21, 'http://www.grady.com/ipsam-voluptatibus-accusantium-autem-vel-suscipit-cumque', 'Quisquam voluptatum quibusdam numquam consectetur maiores facilis.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(49, 2, 'https://larkin.com/facere-sed-facere-reprehenderit-sit-consequatur.html', 'Fuga voluptas minima quibusdam earum.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(50, 42, 'https://www.hudson.org/ut-consequatur-beatae-accusantium-aut-quaerat', 'In sapiente consequatur accusamus et nihil dolore.', '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(51, 1, 'https://tudasbazis.sulinet.hu/hu/tarsadalomtudomanyok/tortenelem/a-kozepkor-tortenete-476-1492/karoly-robert-uralkodasa/karoly-robert-reformjai', 'Károly Róbert reformjai', '2025-04-10 06:22:15', '2025-04-10 06:22:15');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `test_questions`
--

DROP TABLE IF EXISTS `test_questions`;
CREATE TABLE `test_questions` (
  `id` int(11) NOT NULL,
  `questionId` int(11) NOT NULL DEFAULT 1,
  `answerId` int(11) DEFAULT NULL,
  `userTestId` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=5461 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `test_questions`
--

INSERT INTO `test_questions` (`id`, `questionId`, `answerId`, `userTestId`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 1, '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(2, 2, 3, 1, '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(3, 3, 8, 1, '2025-04-10 06:14:17', '2025-04-10 06:14:17');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roleId` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=16384 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`id`, `name`, `roleId`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'test', 1, 'test@example.com', NULL, '$2y$12$EZeazignVEvACiPAhWaS.et9L08uimeBIump9Jo4RFBPn/qjCo6Mm', NULL, '2025-04-10 06:14:16', '2025-04-10 06:14:16');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `user_tests`
--

DROP TABLE IF EXISTS `user_tests`;
CREATE TABLE `user_tests` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `testName` varchar(255) NOT NULL,
  `score` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AVG_ROW_LENGTH=8192 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- A tábla adatainak kiíratása `user_tests`
--

INSERT INTO `user_tests` (`id`, `userId`, `testName`, `score`, `created_at`, `updated_at`) VALUES
(1, 1, 'Teszt 1', 85.5, '2025-04-10 06:14:17', '2025-04-10 06:14:17'),
(2, 1, 'Teszt 2', 92.3, '2025-04-10 06:14:17', '2025-04-10 06:14:17');

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `answers_questionid_foreign` (`questionId`);

--
-- A tábla indexei `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- A tábla indexei `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- A tábla indexei `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- A tábla indexei `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- A tábla indexei `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- A tábla indexei `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- A tábla indexei `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `questions_categoryid_foreign` (`categoryId`),
  ADD KEY `questions_questiontypeid_foreign` (`questionTypeId`);

--
-- A tábla indexei `question_types`
--
ALTER TABLE `question_types`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- A tábla indexei `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`),
  ADD KEY `sessions_user_id_index` (`user_id`);

--
-- A tábla indexei `sources`
--
ALTER TABLE `sources`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sources_categoryid_foreign` (`categoryId`);

--
-- A tábla indexei `test_questions`
--
ALTER TABLE `test_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `test_questions_answerid_foreign` (`answerId`),
  ADD KEY `test_questions_questionid_foreign` (`questionId`),
  ADD KEY `test_questions_usertestid_foreign` (`userTestId`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_roleid_foreign` (`roleId`);

--
-- A tábla indexei `user_tests`
--
ALTER TABLE `user_tests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_tests_userid_foreign` (`userId`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT a táblához `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT a táblához `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT a táblához `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT a táblához `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT a táblához `question_types`
--
ALTER TABLE `question_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT a táblához `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT a táblához `sources`
--
ALTER TABLE `sources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT a táblához `test_questions`
--
ALTER TABLE `test_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT a táblához `user_tests`
--
ALTER TABLE `user_tests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_questionid_foreign` FOREIGN KEY (`questionId`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_categoryid_foreign` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `questions_questiontypeid_foreign` FOREIGN KEY (`questionTypeId`) REFERENCES `question_types` (`id`);

--
-- Megkötések a táblához `sources`
--
ALTER TABLE `sources`
  ADD CONSTRAINT `sources_categoryid_foreign` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `test_questions`
--
ALTER TABLE `test_questions`
  ADD CONSTRAINT `test_questions_answerid_foreign` FOREIGN KEY (`answerId`) REFERENCES `answers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `test_questions_questionid_foreign` FOREIGN KEY (`questionId`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `test_questions_usertestid_foreign` FOREIGN KEY (`userTestId`) REFERENCES `user_tests` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_roleid_foreign` FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Megkötések a táblához `user_tests`
--
ALTER TABLE `user_tests`
  ADD CONSTRAINT `user_tests_userid_foreign` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
