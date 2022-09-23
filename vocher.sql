-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 23. Sep 2022 um 22:22
-- Server-Version: 10.4.24-MariaDB
-- PHP-Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `vocher`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `vocher`
--

CREATE TABLE `vocher` (
  `id` int(11) NOT NULL,
  `vkey` varchar(50) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `ap_option` int(5) NOT NULL DEFAULT 15,
  `date` date NOT NULL,
  `url` varchar(100) NOT NULL,
  `img_path` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Daten für Tabelle `vocher`
--

INSERT INTO `vocher` (`id`, `vkey`, `status`, `ap_option`, `date`, `url`, `img_path`) VALUES
(1, '87FhGvOm4Ru2k8IkPRSSnfMvE', 3, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=87FhGvOm4Ru2k8IkPRSSnfMvE', 'output/whc_gc_0_15.jpg'),
(2, 'etnqT4jiqyWixYPFPljdpPLxQ', 0, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=etnqT4jiqyWixYPFPljdpPLxQ', 'output/whc_gc_2_15.jpg'),
(3, 'YgAj5zzdNOBc8i3iBUGcGNSW8', 2, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=YgAj5zzdNOBc8i3iBUGcGNSW8', 'output/whc_gc_4_15.jpg'),
(4, 'CiMbcZMLDpHmEYgHMXKggp5J8', 0, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=CiMbcZMLDpHmEYgHMXKggp5J8', 'output/whc_gc_6_15.jpg'),
(6, 'Vyc2aZReGi2L2cXC8GTo8kMbW', 3, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=Vyc2aZReGi2L2cXC8GTo8kMbW', 'output/whc_gc_10_15.jpg'),
(7, 'oWvy7h9iLB7qh6CXDAp7xKizg', 3, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=oWvy7h9iLB7qh6CXDAp7xKizg', 'output/whc_gc_12_15.jpg'),
(8, 'XUc1JBlTbaYXaq3YEPiIq5VQZ', 1, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=XUc1JBlTbaYXaq3YEPiIq5VQZ', 'output/whc_gc_14_15.jpg'),
(9, 'F8SSYXL29qsbcvI3gW7SvEeID', 1, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=F8SSYXL29qsbcvI3gW7SvEeID', 'output/whc_gc_16_15.jpg'),
(10, 'zqEhv8AJBjg0STEG03z43EAMT', 1, 15, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=zqEhv8AJBjg0STEG03z43EAMT', 'output/whc_gc_18_15.jpg'),
(11, '0OojbC62JuQj3pNsyf39ivm58', 1, 30, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=0OojbC62JuQj3pNsyf39ivm58', 'output/whc_gc_10_30.jpg'),
(12, '7blbvpZrCYbaAV96DbLxoXsLF', 1, 30, '2022-09-21', 'http://localhost/php-vouchersystem/validate.php?vocherid=7blbvpZrCYbaAV96DbLxoXsLF', 'output/whc_gc_12_30.jpg'),
(16, 'UP8W0GG11SALNVLB1XG0BAZMS', 3, 0, '2022-09-22', 'http://localhost/php-vouchersystem/validate.php?vocherid=UP8W0GG11SALNVLB1XG0BAZMS', 'output/whc_gc_custom_DS6OXCTHLH0.jpg'),
(26, 'VD0ZHARIZ1M4OSMSJWKFQ7SRU', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=VD0ZHARIZ1M4OSMSJWKFQ7SRU', 'output/whc_gc_custom_8T2Y7GJBMA0.jpg'),
(27, 'PWVRI17Q8AIF2WB5VOCAGQ5OO', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=PWVRI17Q8AIF2WB5VOCAGQ5OO', 'output/whc_gc_custom_4B4UJSV2IU0.jpg'),
(33, 'YMGLAZ9BJYJGBIPL7OY2ZRK5U', 3, 30, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=YMGLAZ9BJYJGBIPL7OY2ZRK5U', 'output/whc_gc_custom_54WOT6HA0B30.jpg'),
(34, 'Z2E0LGK5MNICDNFMJGUUZDMUD', 3, 15, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=Z2E0LGK5MNICDNFMJGUUZDMUD', 'output/whc_gc_custom_UPVWANT1HD15.jpg'),
(35, '0TOKUJXTG8AFJ53CFZ3KVPE2T', 3, 30, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=0TOKUJXTG8AFJ53CFZ3KVPE2T', 'output/whc_gc_custom_GJSKD18VTY30.jpg'),
(36, 'VTG8M1PE335AC3JNXWAHXQFFR', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=VTG8M1PE335AC3JNXWAHXQFFR', 'output/whc_gc_custom_VTG8M1PE335AC3JNXWAHXQFFR.jpg'),
(37, 'WV4IBA4FCTMUMU0LNVDJCK0KJ', 3, 15, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=WV4IBA4FCTMUMU0LNVDJCK0KJ', 'output/whc_gc_custom_WV4IBA4FCTMUMU0LNVDJCK0KJ.jpg'),
(38, '1F8Q636SSI211TPCX485POBB9', 3, 30, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=1F8Q636SSI211TPCX485POBB9', 'output/whc_gc_custom_1F8Q636SSI211TPCX485POBB9.jpg'),
(39, '4FYD5JUHRWPK0H6AHZXYAEDNR', 3, 15, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=4FYD5JUHRWPK0H6AHZXYAEDNR', 'output/whc_gc_custom_4FYD5JUHRWPK0H6AHZXYAEDNR.jpg'),
(40, 'RRZBZF8QRZDYUXPD5RD5A8Z1G', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=RRZBZF8QRZDYUXPD5RD5A8Z1G', 'output/whc_gc_custom_RRZBZF8QRZDYUXPD5RD5A8Z1G.jpg'),
(41, 'K0A3OB2EVUR5N3AOWOIBOY188', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=K0A3OB2EVUR5N3AOWOIBOY188', 'output/whc_gc_custom_K0A3OB2EVUR5N3AOWOIBOY188.jpg'),
(42, 'E2XP0PKNA69I4U8TCD2NOJEGL', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=E2XP0PKNA69I4U8TCD2NOJEGL', 'output/whc_gc_custom_E2XP0PKNA69I4U8TCD2NOJEGL.jpg'),
(43, 'U8XCW05FCX1Q6F480TYTHYUA1', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=U8XCW05FCX1Q6F480TYTHYUA1', 'output/whc_gc_custom_U8XCW05FCX1Q6F480TYTHYUA1.jpg'),
(44, 'J51JIL0W9EX2CE3EXOIGYINF8', 3, 0, '2022-09-23', 'http://localhost/php-vouchersystem/validate.php?vocherid=J51JIL0W9EX2CE3EXOIGYINF8', 'output/whc_gc_custom_J51JIL0W9EX2CE3EXOIGYINF8.jpg');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `vocher`
--
ALTER TABLE `vocher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `vocher`
--
ALTER TABLE `vocher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
