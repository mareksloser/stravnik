CREATE TABLE `stravnik` (
	`id` int(11) NOT NULL,
	`name` varchar(255) NOT NULL,
	`price` int(10) NOT NULL,
	`time` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Vypisuji data pro tabulku `stravnik`
--

INSERT INTO `stravnik` (`id`, `name`, `price`, `time`) VALUES
	(1, 'Dva párky a pečivo', 72, 'rano'),
	(2, 'Kachna knedlík', 205, 'poledne'),
	(5, 'Jídlo 1', 150, 'rano'),
	(6, 'Jídlo 2', 148, 'poledne'),
	(7, 'Jídlo 3', 149, 'poledne'),
	(8, 'Jídlo 4', 256, 'vecer'),
	(9, 'Jídlo 5', 112, 'rano'),
	(10, 'Jídlo 6', 69, 'poledne'),
	(11, 'Jídlo 7', 112, 'vecer'),
	(12, 'Jídlo 8', 873, 'poledne'),
	(13, 'Jídlo 9', 225, 'rano'),
	(14, 'Jídlo 10', 102, 'vecer');

--
-- Indexy pro exportované tabulky
--

--
-- Indexy pro tabulku `stravnik`
--
ALTER TABLE `stravnik`
	ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `stravnik`
--
ALTER TABLE `stravnik`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;
