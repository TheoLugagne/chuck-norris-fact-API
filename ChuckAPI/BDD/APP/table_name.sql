CREATE TABLE `chuckn_facts` (
                                `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                `phrase` text CHARACTER SET latin1 NOT NULL,
                                `vote` int(11) DEFAULT NULL,
                                `date_ajout` datetime DEFAULT NULL,
                                `date_modif` datetime DEFAULT NULL,
                                `faute` tinyint(1) DEFAULT NULL,
                                `signalement` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;