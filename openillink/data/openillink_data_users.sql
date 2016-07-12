-- --------------------------------------------------------

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `login`, `password`, `library`, `status`, `created_ip`, `created_on`, `admin`) VALUES
(1, 'Super administrateur', 'sadmin@unixyz.com', 'sadmin', 'c5edac1b8c1d58bad90a246d8f08f53b', 'LIB1', 1, '127.0.0.1', '2012-05-24 22:12:37', 1),
(2, 'Utilisateur', 'user@unixyz.com', 'user', 'ee11cbb19052e40b07aac0ca060c23ee', 'LIB7', 1, '127.0.0.1', '2012-05-24 21:52:53', 3),
(3, 'Administrateur', 'admin@unixyz.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'LIB1', 1, '127.0.0.1', '2012-05-24 22:12:50', 2),
(4, 'Administrateur 2', 'admin2@unixyz.com', 'admin2', 'c84258e9c39059a89ab77d846ddab909', 'LIB1', 1, '127.0.0.1', '2012-05-24 22:12:20', 2);
