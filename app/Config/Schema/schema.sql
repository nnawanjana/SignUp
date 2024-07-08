--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` mediumint(8) unsigned NOT NULL,
  `role` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `phone_ext` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `timezone` varchar(255) NOT NULL DEFAULT 'Asia/Hong_Kong',
  `password` varchar(64) NOT NULL,
  `password_temp` varchar(64) DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `login` datetime DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;