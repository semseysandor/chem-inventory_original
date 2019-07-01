SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Dumping data for table `leltar_loc_lab`
--

INSERT INTO `leltar_loc_lab` (`loc_lab_id`, `name`, `last_mod_by`, `last_mod_time`) VALUES
(8, 'Raktár', '', '2019-06-13 15:33:38'),
(9, 'Előkészítő', '', '2019-06-13 15:34:08'),
(10, 'Analitika', '', '2019-06-13 15:34:29'),
(11, 'Félüzem', '', '2019-06-13 15:34:39');

--
-- Dumping data for table `leltar_loc_place`
--

INSERT INTO `leltar_loc_place` (`loc_place_id`, `name`, `last_mod_by`, `last_mod_time`) VALUES
(15, 'Szekrény', '', '2019-06-13 15:34:48'),
(16, 'Polc', '', '2019-06-13 15:34:57'),
(17, 'Fiók', '', '2019-06-27 08:02:59'),
(18, 'Hűtő', '', '2019-06-27 08:03:21'),
(19, 'Savszekrény', '', '2019-06-27 09:21:30');

--
-- Dumping data for table `leltar_loc_sub`
--

INSERT INTO `leltar_loc_sub` (`loc_sub_id`, `name`, `last_mod_by`, `last_mod_time`) VALUES
(23, '1', '', '2019-06-13 15:35:13'),
(24, '2', '', '2019-06-13 15:35:16'),
(25, '3', '', '2019-06-27 08:03:33'),
(26, '4', '', '2019-06-27 08:03:36'),
(27, 'A', '', '2019-06-27 08:03:40'),
(28, 'B', '', '2019-06-27 08:03:43'),
(29, 'C', '', '2019-06-27 08:03:47'),
(30, '', '', '2019-06-27 08:04:21');

--
-- Dumping data for table `leltar_location`
--

INSERT INTO `leltar_location` (`location_id`, `loc_lab_id`, `loc_place_id`, `loc_sub_id`, `last_mod_by`, `last_mod_time`) VALUES
(62, 8, 15, 23, '', '2019-06-13 15:35:25'),
(63, 8, 15, 25, '', '2019-06-27 08:05:46'),
(64, 8, 15, 24, '', '2019-06-27 08:06:18'),
(65, 9, 16, 30, '', '2019-06-27 08:06:44'),
(66, 9, 17, 27, '', '2019-06-27 08:07:14'),
(67, 9, 17, 28, '', '2019-06-27 08:07:23'),
(68, 9, 17, 29, '', '2019-06-27 08:07:30'),
(69, 9, 18, 23, '', '2019-06-27 08:08:01'),
(70, 9, 18, 24, '', '2019-06-27 08:08:08'),
(71, 9, 18, 25, '', '2019-06-27 08:08:15'),
(72, 10, 16, 23, '', '2019-06-27 08:41:09'),
(73, 10, 16, 24, '', '2019-06-27 08:41:19'),
(74, 11, 15, 27, '', '2019-06-27 08:42:26'),
(75, 11, 15, 28, '', '2019-06-27 08:42:34'),
(76, 10, 18, 30, '', '2019-06-27 08:43:29'),
(77, 9, 19, 30, '', '2019-06-27 09:22:44');

--
-- Dumping data for table `leltar_manfac`
--

INSERT INTO `leltar_manfac` (`manfac_id`, `name`, `is_frequent`, `last_mod_by`, `last_mod_time`) VALUES
(49, 'Solvent Company', 1, '', '2019-06-13 15:27:16'),
(50, 'Another Solvent Co', 0, '', '2019-06-27 08:43:58'),
(51, 'CHEM Supplier', 1, '', '2019-06-27 08:44:27'),
(52, 'AllChemicals ', 1, '', '2019-06-27 08:44:48'),
(53, 'BioLab', 0, '', '2019-06-27 08:45:17'),
(54, 'PharmaLab', 0, '', '2019-06-27 08:45:42'),
(55, 'ChemLab', 0, '', '2019-06-27 08:46:47'),
(56, 'Another Chem Company', 0, '', '2019-06-27 08:47:31');

--
-- Dumping data for table `leltar_category`
--

INSERT INTO `leltar_category` (`category_id`, `name`, `last_mod_by`, `last_mod_time`) VALUES
(5, 'Általános', '', '2019-06-13 08:42:45'),
(6, 'Tablettázási segédanyag', '', '2019-06-13 08:44:49'),
(7, 'Oldószer', '', '2019-06-13 08:45:00'),
(8, 'API', '', '2019-06-13 08:45:06');

--
-- Dumping data for table `leltar_sub_category`
--

INSERT INTO `leltar_sub_category` (`sub_category_id`, `category_id`, `name`, `last_mod_by`, `last_mod_time`) VALUES
(9, 5, 'Szerves', '', '2019-06-13 08:50:51'),
(10, 5, 'Szervetlen', '', '2019-06-13 08:50:59'),
(11, 5, 'Polimer', '', '2019-06-13 08:51:08'),
(12, 6, 'Töltőanyag', '', '2019-06-13 09:27:04'),
(13, 6, 'Szétejtő', '', '2019-06-13 09:27:13'),
(14, 6, 'Csúsztató', '', '2019-06-13 09:27:32'),
(15, 6, 'Antioxidáns', '', '2019-06-13 09:27:58'),
(16, 7, 'Oldószer', '', '2019-06-13 15:37:08'),
(17, 8, 'API', '', '2019-06-13 15:37:15');

--
-- Dumping data for table `main_users`
--

INSERT INTO `main_users` (`user_id`, `name`, `right_level_leltar`, `right_level_api`, `last_mod_by`, `last_mod_time`) VALUES
(27, 'jurkov', 3, 3, 'c12406jurkov', '2019-06-13 08:38:19');

--
-- Dumping data for table `leltar_compound`
--

INSERT INTO `leltar_compound` (`compound_id`, `name`, `name_alt`, `abbrev`, `chemical_name`, `iupac_name`, `chem_formula`, `cas`, `smiles`, `sub_category_id`, `oeb`, `mol_weight`, `melting_point`, `note`, `last_mod_by`, `last_mod_time`) VALUES
(584, 'Metanol', NULL, 'MeOH', 'Metil-alkohol', 'methanol', 'CH4O', '67-56-1', 'CO', 16, NULL, 32.042, NULL, NULL, 'jurkov', '2019-06-27 08:48:09'),
(585, 'Sodium hydroxide', 'Nátrium-hidroxid', NULL, NULL, 'sodium hydroxide', 'NaOH', '1310-73-2', '[OH-].[Na+]', 10, NULL, 39.9971, NULL, NULL, 'jurkov', '2019-06-27 09:28:12'),
(586, '9-Bromo-1-nonanol', 'Bróm-nonanol', NULL, NULL, '9-bromononan-1-ol', 'C9H19BrO', '55362-80-6', 'OCCCCCCCCCBr', 9, NULL, 223.1525, NULL, NULL, 'jurkov', '2019-06-27 08:53:54'),
(587, '2-Azidoacetic acid', 'Azidoecetsav', NULL, NULL, '2-azidoacetic acid', 'C2H3N3O2', '18523-48-3', 'OC(=O)CN=[N+]=[N-]', 9, NULL, 101.0646, NULL, NULL, 'jurkov', '2019-06-27 08:59:30'),
(588, 'N,N''-Dicyclohexylmethanediimine', NULL, 'DCC', NULL, 'N,N''-dicyclohexylmethanediimine', 'C13H22N2', '538-75-0', 'C1CCC(CC1)N=C=NC2CCCCC2', 9, NULL, 206.3302, NULL, NULL, 'jurkov', '2019-06-27 09:02:18'),
(589, 'N-Boc-ethylenediamine', NULL, NULL, NULL, 'tert-Butyl N-(2-aminoethyl)carbamate', 'C7H16N2O2', '57260-73-8', 'CC(C)(C)OC(=O)NCCN', 9, NULL, 160.2156, NULL, NULL, 'jurkov', '2019-06-27 09:05:07'),
(590, 'Triphenylphosphine', 'Tri-fenil-foszfin', 'Ph3P', NULL, 'tri(phenyl)phosphane', 'C18H15P', '603-35-0', 'c1ccc(cc1)P(c2ccccc2)c3ccccc3', 9, NULL, 262.2903, NULL, NULL, 'jurkov', '2019-06-27 15:56:30'),
(591, 'Hydrogen chloride', 'Sósav', NULL, NULL, 'hydrogen chloride\nhydron chloride', 'ClH', '7647-01-0', '[H+].[Cl-]', 10, NULL, 36.4609, NULL, NULL, 'jurkov', '2019-06-27 09:20:27'),
(592, 'Potassium acetate', 'Kálium-acetát', NULL, NULL, 'Potassium acetate', 'C2H3KO2', '127-08-2', '[K+].CC([O-])=O', 10, NULL, 98.1425, NULL, NULL, 'jurkov', '2019-06-27 09:24:45'),
(593, 'Copper (I) chloride', 'Réz(I)-klorid', NULL, NULL, 'copper(+1) cation chloride', 'ClCu', '7758-89-6', '[Cl-].[Cu+]', 10, NULL, 98.999, NULL, NULL, 'jurkov', '2019-06-27 09:32:38'),
(594, 'Magnesium', 'Magnézium forgács', NULL, NULL, 'magnesium(+2) cation', 'Mg', '7439-95-4', '[Mg++]', 10, NULL, 24.305, NULL, NULL, 'jurkov', '2019-06-27 09:35:30'),
(595, 'Ethylcellulose', 'Etilcellulóz', 'EC', NULL, NULL, NULL, '9004-57-3', NULL, 11, NULL, NULL, NULL, NULL, 'jurkov', '2019-06-27 09:38:59'),
(596, 'Polymethacrylate', 'Polimetakrilát', 'PMA', NULL, 'methyl 2-methylprop-2-enoate; 2-methylprop-2-enoic acid', 'C9H14O4', '25086-15-1', 'COC(=O)C(C)=C.CC(=C)C(O)=O', 11, NULL, 186.2072, NULL, NULL, 'jurkov', '2019-06-27 09:42:35'),
(597, 'Polyvinyl alcohol', 'Polivinil-alkohol', 'PVA', NULL, 'Ethenol', 'C2H4O', '9002-89-5', 'OC=C', 11, NULL, 44.053, NULL, NULL, 'jurkov', '2019-06-27 09:47:00'),
(598, 'D-α-Tocopherol polyethylene glycol succinate', 'Vitamin E', 'TPGS', NULL, '[2,5,7,8-tetramethyl-2-(4,8,12-trimethyltridecyl)chroman-6-yl] 2-hydroxyethyl butanedioate', 'C35H58O6', '9002-96-4', 'CC(C)CCCC(C)CCCC(C)CCCC1(C)CCc2c(C)c(OC(=O)CCC(=O)OCCO)c(C)c(C)c2O1', 11, NULL, 574.8396, NULL, NULL, 'jurkov', '2019-06-27 15:57:00'),
(599, 'Povidone', 'Polyvinylpyrrolidone', 'PVP', NULL, '1-ethenylpyrrolidin-2-one', 'C6H9NO', '9003-39-8', 'C=CN1CCCC1=O', 11, NULL, 111.1432, NULL, NULL, 'jurkov', '2019-06-27 09:50:39'),
(600, 'Lactose monohydrate', 'Laktóz monohidrát', NULL, NULL, '(2R,3R,4S,5R,6S)-2-(hydroxymethyl)-6-[(2R,3S,4R,5R)-4,5,6-trihydroxy-2-(hydroxymethyl)oxan-3-yl]oxyoxane-3,4,5-triol hydrate', 'C12H24O12', '64044-51-5', 'O.OC[C@H]1O[C@@H](O[C@H]2[C@H](O)[C@@H](O)C(O)O[C@@H]2CO)[C@H](O)[C@@H](O)[C@H]1O', 12, NULL, 360.3144, NULL, NULL, 'jurkov', '2019-06-27 10:08:53'),
(601, 'Microcrystalline Cellulose', 'Mikrokristályos cellulóz', 'MCC', NULL, NULL, NULL, '9004-34-6', NULL, 12, NULL, NULL, NULL, NULL, 'jurkov', '2019-06-27 10:10:21'),
(602, 'Calcium Silicate', 'Kalcium-szilikát', NULL, NULL, 'calcium dioxido-oxosilane', 'CaO3Si', '1344-95-2', '[Ca++].[O-][Si]([O-])=O', 12, NULL, 116.1642, NULL, NULL, 'jurkov', '2019-06-27 10:12:24'),
(603, 'Crospovidone', 'Poly(vinylpolypyrrolidone)', 'PVPP', NULL, '1-ethenylpyrrolidin-2-one', 'C6H9NO', '9003-39-8', 'C=CN1CCCC1=O', 13, NULL, 111.1432, NULL, NULL, 'jurkov', '2019-06-27 10:13:56'),
(604, 'Stearic acid', 'Sztearinsav', NULL, NULL, 'Octadecanoic acid', 'C18H36O2', '57-11-4', 'CCCCCCCCCCCCCCCCCC(O)=O', 14, NULL, 284.4812, NULL, NULL, 'jurkov', '2019-06-27 10:15:54'),
(605, 'Sucrose', 'Szacharóz', NULL, NULL, '(2R,3R,4S,5S,6R)-2-[(2S,3S,4S,5R)-3,4-dihydroxy-2,5-bis(hydroxymethyl)oxolan-2-yl]oxy-6-(hydroxymethyl)oxane-3,4,5-triol', 'C12H22O11', '57-50-1', 'OC[C@H]1O[C@H](O[C@]2(CO)O[C@H](CO)[C@@H](O)[C@@H]2O)[C@H](O)[C@@H](O)[C@@H]1O', 12, NULL, 342.2992, NULL, NULL, 'jurkov', '2019-06-27 10:20:02'),
(606, 'Sodium carboxymethyl cellulose', 'Nátrium-karboximetil-cellulóz', 'CMC', NULL, 'acetic acid; 2,3,4,5,6-pentahydroxyhexanal; sodium', 'C8H16NaO8', '9004-32-4', '[Na].CC(O)=O.OCC(O)C(O)C(O)C(O)C=O', 13, NULL, 263.1994, NULL, NULL, 'jurkov', '2019-06-27 10:22:08'),
(607, 'Starch', 'keményítő', NULL, NULL, NULL, NULL, '9005-25-8', NULL, 13, NULL, NULL, NULL, NULL, 'jurkov', '2019-06-27 10:24:20'),
(608, 'Talc', 'Talkum', NULL, NULL, NULL, NULL, NULL, NULL, 14, NULL, NULL, NULL, NULL, 'jurkov', '2019-06-27 10:26:14'),
(609, 'Ascorbic acid', 'Aszkorbinsav', NULL, NULL, '2-(1,2-dihydroxyethyl)-4,5-dihydroxyfuran-3-one', 'C6H8O6', '50-81-7', 'OCC(O)C1OC(=C(O)C1=O)O', 15, NULL, 176.1256, NULL, NULL, 'jurkov', '2019-06-27 11:09:29'),
(610, 'DL-Tartaric acid', 'DL-borkősav', NULL, NULL, '2,3-Dihydroxybutanedioic acid', 'C4H6O6', '133-37-9', 'OC(C(O)C(O)=O)C(O)=O', 15, NULL, 150.0878, NULL, NULL, 'jurkov', '2019-06-27 11:11:06'),
(611, 'Butylated hydroxytoluene', '2,6-Di-terc-butil-p-krezol', 'BHT', NULL, '2,6-ditert-butyl-4-methylphenol', 'C15H24O', '128-37-0', 'Cc1cc(c(O)c(c1)C(C)(C)C)C(C)(C)C', 15, NULL, 220.354, NULL, NULL, 'jurkov', '2019-06-27 11:14:12'),
(612, 'Butylated hydroxyanisole', '2-terc-butil-4-hidroxianizol', 'BHA', NULL, '2-tert-Butyl-4-methoxyphenol', 'C11H16O2', '25013-16-5', 'COc1ccc(O)c(c1)C(C)(C)C', 15, NULL, 180.2462, NULL, NULL, 'jurkov', '2019-06-27 11:15:43'),
(613, 'Benzyl alcohol', 'Benzil-alkohol', NULL, NULL, 'phenylmethanol', 'C7H8O', '100-51-6', 'OCc1ccccc1', 16, NULL, 108.1396, NULL, NULL, 'jurkov', '2019-06-27 12:11:29'),
(614, '2-propanol', 'Izo-propanol', 'IPA', NULL, 'Propan-2-ol', 'C3H8O', '67-63-0', 'CC(C)O', 16, NULL, 60.0956, NULL, NULL, 'jurkov', '2019-06-27 12:15:15'),
(615, 'Dimethyl sulfoxide', 'Dimetil-szulfoxid', 'DMSO', NULL, 'Methylsulfinylmethane', 'C2H6OS', '67-68-5', 'C[S](C)=O', 16, NULL, 78.1288, NULL, NULL, 'jurkov', '2019-06-27 12:17:21'),
(616, 'Indomethacin', NULL, NULL, NULL, '2-[1-(4-chlorobenzoyl)-5-methoxy-2-methylindol-3-yl]acetic acid', 'C19H16ClNO4', '53-86-1', 'COc1ccc2n(c(C)c(CC(O)=O)c2c1)C(=O)c3ccc(Cl)cc3', 17, NULL, 357.7927, NULL, NULL, 'jurkov', '2019-06-27 12:23:09'),
(617, 'Aspirin', 'Aszpirin', NULL, NULL, '2-acetyloxybenzoic acid', 'C9H8O4', '50-78-2', 'CC(=O)Oc1ccccc1C(O)=O', 17, NULL, 180.1598, NULL, NULL, 'jurkov', '2019-06-27 12:25:34');

--
-- Dumping data for table `leltar_batch`
--

INSERT INTO `leltar_batch` (`batch_id`, `compound_id`, `manfac_id`, `name`, `lot`, `date_arr`, `date_open`, `date_exp`, `date_arch`, `note`, `is_active`, `last_mod_by`, `last_mod_time`) VALUES
(704, 584, 49, 'purum', 'BKC0001', '2019-06-13', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-13 15:27:37'),
(705, 586, 52, '95%', 'BNM1234', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 08:54:41'),
(706, 586, 55, '&gt;99.9%', '1234', '2019-06-17', '2019-06-18', NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 08:55:20'),
(707, 587, 52, '2-Azidoacetic acid', '123-LOK', '2019-06-27', NULL, '2022-06-30', NULL, NULL, 1, 'jurkov', '2019-06-27 09:00:23'),
(708, 588, 52, '99%', '1000K', '2019-04-03', '2019-04-18', NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:02:57'),
(709, 588, 52, '99%', '2000K', '2019-06-27', '2019-06-28', NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:03:54'),
(710, 589, 52, '98%', 'FFER2#22', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:05:37'),
(711, 589, 55, '95%', '32-332', '2019-06-02', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:06:40'),
(712, 590, 51, 'Reagent Grade, &gt;99.5%', '2223ASD', '2019-06-02', '2019-06-18', '2022-01-31', NULL, NULL, 1, 'jurkov', '2019-06-27 09:09:56'),
(713, 591, 49, '37%', '1123', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:20:57'),
(714, 591, 49, '1N', '11234', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:23:15'),
(715, 592, 51, 'puriss.', '32112', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:25:43'),
(716, 585, 49, 'microparticles', '443L/76', '2018-06-03', '2018-06-03', '2019-06-25', NULL, NULL, 1, 'jurkov', '2019-06-27 09:29:35'),
(717, 585, 49, '1N', '123/4', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:30:09'),
(718, 593, 54, '99.999%', 'GNU34#3', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:33:25'),
(719, 593, 52, '99%', '111985', '2019-06-11', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:34:21'),
(720, 594, 52, 'for synthesis', '3487VCR-H', '2019-06-09', '2019-06-11', '2023-06-06', NULL, NULL, 1, 'jurkov', '2019-06-27 09:36:13'),
(721, 595, 55, 'EthylCel EC 50', '432', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:39:41'),
(722, 595, 55, 'EthylCel EC 20', '456', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:40:37'),
(723, 596, 52, 'PMA 25', 'OLK-345', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:43:52'),
(724, 596, 52, 'PMA 35', 'OLK-6633', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:44:23'),
(725, 596, 52, 'PMA 75', 'OLK-6145', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:46:04'),
(726, 597, 56, 'PVA', '113', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:47:15'),
(727, 598, 51, 'NF grade', '113', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:49:01'),
(728, 598, 53, '99.99%', '6667-87/H', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:49:52'),
(729, 599, 52, 'PVP 10', '1123', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:51:00'),
(730, 599, 52, 'PVP 40', '1957', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 09:51:50'),
(731, 600, 53, 'LAC 100', '345', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:09:18'),
(732, 601, 55, 'MCC', '665', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:10:42'),
(733, 602, 52, 'CalSil', '6543', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:12:46'),
(734, 603, 52, 'CL', '334DE43', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:14:16'),
(735, 604, 54, 'Grade A, &gt;98%', '445FR', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:16:32'),
(736, 605, 51, 'purum', '44ASD', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:20:42'),
(737, 606, 51, 'Ave Mw 70000', 'FG8874', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:22:35'),
(738, 606, 52, 'Grade B', '332V', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:22:57'),
(739, 607, 56, 'soluble, purum', '7283CHE', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:24:44'),
(740, 608, 56, 'Grade AA', '6652', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 10:27:31'),
(741, 609, 53, 'puriss.', '9843-K', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 11:09:56'),
(742, 610, 53, 'for synthesis, 99.99%', '543GGFF#33', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 11:11:44'),
(743, 611, 52, '&gt;99%', '443GHE', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 11:14:34'),
(744, 612, 52, '99%', '543SD', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 11:16:02'),
(745, 613, 49, 'purum', '12SA', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:11:48'),
(746, 613, 49, 'purum', '12FD', '2019-01-08', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:12:21'),
(747, 614, 49, 'purum', '43GF', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:16:20'),
(748, 614, 50, 'purum', '453FDA', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:15:50'),
(749, 615, 49, 'for synthesis', '33DS', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:17:44'),
(750, 615, 49, 'puriss.', '443FW4', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:18:09'),
(751, 616, 54, 'Indomethacin', '44432FS', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:23:24'),
(752, 617, 54, 'Aspirin', '65GF', '2019-06-27', NULL, NULL, NULL, NULL, 1, 'jurkov', '2019-06-27 12:25:51');

--
-- Dumping data for table `leltar_pack`
--

INSERT INTO `leltar_pack` (`pack_id`, `batch_id`, `location_id`, `is_original`, `size`, `weight`, `barcode`, `note`, `is_active`, `last_mod_by`, `last_mod_time`) VALUES
(1001, 704, 62, 1, '5L', NULL, '00010016', NULL, 0, 'jurkov', '2019-06-13 15:35:54'),
(1002, 704, 62, 1, '5 L', NULL, '00010025', NULL, 1, 'jurkov', '2019-06-13 15:36:02'),
(1003, 705, 66, 1, '25 g', NULL, '00010039', NULL, 1, 'jurkov', '2019-06-27 08:56:03'),
(1004, 706, 66, 1, '5 g', NULL, '00010049', NULL, 1, 'jurkov', '2019-06-27 08:58:16'),
(1005, 707, 66, 1, '2 g', NULL, '00010054', NULL, 1, 'jurkov', '2019-06-27 09:00:42'),
(1006, 708, 66, 1, '25 g', NULL, '00010061', NULL, 1, 'jurkov', '2019-06-27 09:03:14'),
(1007, 708, 72, 0, '1 g', NULL, '00010078', NULL, 1, 'jurkov', '2019-06-27 09:03:27'),
(1008, 709, 66, 1, '5 g', NULL, '00010085', NULL, 1, 'jurkov', '2019-06-27 09:04:04'),
(1009, 710, 66, 1, '25 g', NULL, '00010092', NULL, 1, 'jurkov', '2019-06-27 09:05:52'),
(1010, 710, 72, 0, '10 g', NULL, '00010104', NULL, 1, 'jurkov', '2019-06-27 09:06:04'),
(1011, 711, 62, 1, '200 g', '256.5', '00010113', NULL, 1, 'jurkov', '2019-06-27 09:07:03'),
(1012, 711, 65, 0, '30 g', NULL, '00010122', NULL, 1, 'jurkov', '2019-06-27 09:07:20'),
(1013, 712, 66, 0, '25 g', NULL, '00010136', NULL, 1, 'jurkov', '2019-06-27 09:12:03'),
(1014, 713, 77, 1, '1 L', NULL, '00010146', NULL, 1, 'jurkov', '2019-06-27 09:22:59'),
(1015, 714, 77, 1, '2 L', NULL, '00010153', NULL, 1, 'jurkov', '2019-06-27 09:23:30'),
(1016, 714, 72, 0, '100 ml', NULL, '00010160', NULL, 1, 'jurkov', '2019-06-27 09:23:43'),
(1017, 715, 62, 1, '500 g', NULL, '00010177', NULL, 1, 'jurkov', '2019-06-27 09:25:57'),
(1018, 715, 62, 1, '500 g', NULL, '00010184', NULL, 1, 'jurkov', '2019-06-27 09:26:05'),
(1019, 715, 65, 1, '500 g', NULL, '00010191', NULL, 1, 'jurkov', '2019-06-27 09:26:29'),
(1020, 717, 77, 1, '1 L', NULL, '00010201', NULL, 1, 'jurkov', '2019-06-27 09:30:22'),
(1021, 717, 72, 0, '100 mL', NULL, '00010210', NULL, 1, 'jurkov', '2019-06-27 09:30:35'),
(1022, 716, 77, 1, '1000 g', NULL, '00010229', NULL, 1, 'jurkov', '2019-06-27 09:30:46'),
(1023, 718, 67, 0, '1 g', NULL, '00010233', NULL, 1, 'jurkov', '2019-06-27 09:33:58'),
(1024, 719, 67, 0, '100 g', NULL, '00010243', NULL, 1, 'jurkov', '2019-06-27 09:34:37'),
(1025, 720, 65, 1, '100 g', '156', '00010252', NULL, 1, 'jurkov', '2019-06-27 09:36:32'),
(1026, 721, 64, 1, '25 kg', NULL, '00010269', NULL, 1, 'jurkov', '2019-06-27 09:39:58'),
(1027, 721, 74, 0, '2 kg', NULL, '00010276', NULL, 1, 'jurkov', '2019-06-27 09:40:11'),
(1028, 722, 64, 1, '25 kg', NULL, '00010283', NULL, 1, 'jurkov', '2019-06-27 09:40:48'),
(1029, 722, 75, 0, '5 kg', NULL, '00010290', NULL, 1, 'jurkov', '2019-06-27 09:40:56'),
(1030, 723, 74, 1, '100 g', NULL, '00010308', NULL, 1, 'jurkov', '2019-06-27 09:44:04'),
(1031, 724, 74, 1, '100 g', NULL, '00010317', NULL, 1, 'jurkov', '2019-06-27 09:44:35'),
(1032, 725, 75, 0, '100 g', NULL, '00010326', NULL, 1, 'jurkov', '2019-06-27 09:46:15'),
(1033, 726, 68, 0, '300 g', NULL, '00010330', NULL, 1, 'jurkov', '2019-06-27 09:47:31'),
(1034, 727, 69, 1, '100 g', NULL, '00010340', NULL, 1, 'jurkov', '2019-06-27 09:49:11'),
(1035, 727, 76, 0, '1 g', NULL, '00010351', NULL, 1, 'jurkov', '2019-06-27 09:49:25'),
(1036, 728, 76, 0, '5 g', NULL, '00010368', NULL, 1, 'jurkov', '2019-06-27 09:50:01'),
(1037, 729, 68, 0, '100 g', NULL, '00010375', NULL, 1, 'jurkov', '2019-06-27 09:51:13'),
(1038, 729, 75, 1, '500 g', NULL, '00010382', NULL, 1, 'jurkov', '2019-06-27 09:51:26'),
(1039, 730, 75, 1, '500 g', NULL, '00010399', NULL, 1, 'jurkov', '2019-06-27 09:52:02'),
(1040, 730, 65, 0, '100 g', NULL, '00010405', NULL, 1, 'jurkov', '2019-06-27 09:52:29'),
(1041, 731, 68, 0, '100 g', NULL, '00010414', NULL, 1, 'jurkov', '2019-06-27 10:09:29'),
(1042, 732, 75, 0, '500 g', NULL, '00010423', NULL, 1, 'jurkov', '2019-06-27 10:10:55'),
(1043, 732, 68, 0, '100 g', NULL, '00010437', NULL, 1, 'jurkov', '2019-06-27 10:11:03'),
(1044, 733, 74, 0, '500 g', NULL, '00010447', NULL, 1, 'jurkov', '2019-06-27 10:12:54'),
(1045, 733, 64, 1, '5 kg', NULL, '00010450', NULL, 1, 'jurkov', '2019-06-27 10:13:07'),
(1046, 733, 65, 0, '100 g', NULL, '00010467', NULL, 1, 'jurkov', '2019-06-27 10:13:18'),
(1047, 734, 68, 0, '300 g', NULL, '00010474', NULL, 1, 'jurkov', '2019-06-27 10:14:30'),
(1048, 735, 68, 0, '100 g', NULL, '00010481', NULL, 1, 'jurkov', '2019-06-27 10:16:41'),
(1049, 735, 75, 0, '100 g', NULL, '00010498', NULL, 1, 'jurkov', '2019-06-27 10:16:54'),
(1050, 736, 68, 1, '100 g', NULL, '00010502', NULL, 1, 'jurkov', '2019-06-27 10:20:33'),
(1051, 738, 68, 0, '150 g', NULL, '00010511', NULL, 1, 'jurkov', '2019-06-27 10:23:07'),
(1052, 737, 67, 0, '100 g', NULL, '00010520', NULL, 1, 'jurkov', '2019-06-27 10:23:20'),
(1053, 739, 68, 0, '50 g', NULL, '00010534', NULL, 1, 'jurkov', '2019-06-27 10:24:55'),
(1054, 739, 74, 0, '150 g', NULL, '00010544', NULL, 1, 'jurkov', '2019-06-27 10:25:03'),
(1055, 740, 75, 0, '100 g', NULL, '00010559', NULL, 1, 'jurkov', '2019-06-27 10:27:38'),
(1056, 741, 70, 1, '100 g', NULL, '00010566', NULL, 1, 'jurkov', '2019-06-27 11:10:07'),
(1057, 741, 74, 1, '100 g', NULL, '00010573', NULL, 1, 'jurkov', '2019-06-27 11:10:21'),
(1058, 742, 71, 0, '100 g', NULL, '00010580', NULL, 1, 'jurkov', '2019-06-27 11:12:47'),
(1059, 743, 68, 1, '200 g', NULL, '00010597', NULL, 1, 'jurkov', '2019-06-27 11:14:45'),
(1060, 743, 74, 0, '100 g', NULL, '00010609', NULL, 1, 'jurkov', '2019-06-27 11:14:57'),
(1061, 744, 67, 1, '100 g', NULL, '00010618', NULL, 1, 'jurkov', '2019-06-27 11:16:10'),
(1062, 704, 65, 0, '1 L', NULL, '00010627', NULL, 1, 'jurkov', '2019-06-27 11:17:30'),
(1063, 745, 65, 1, '1 L', NULL, '00010631', NULL, 1, 'jurkov', '2019-06-27 12:12:01'),
(1064, 746, 65, 0, '1 L', NULL, '00010641', NULL, 1, 'jurkov', '2019-06-27 12:12:40'),
(1065, 747, 73, 0, '2 L', NULL, '00010658', NULL, 1, 'jurkov', '2019-06-27 12:16:00'),
(1066, 748, 64, 0, '5 L', NULL, '00010665', NULL, 1, 'jurkov', '2019-06-27 12:16:15'),
(1067, 749, 66, 1, '100 ml', NULL, '00010672', NULL, 1, 'jurkov', '2019-06-27 12:17:54'),
(1068, 750, 65, 1, '1 L', NULL, '00010689', NULL, 1, 'jurkov', '2019-06-27 12:18:20'),
(1069, 750, 65, 1, '1 L', NULL, '00010696', NULL, 1, 'jurkov', '2019-06-27 12:18:29'),
(1070, 750, 65, 1, '1 L', NULL, '00010706', NULL, 1, 'jurkov', '2019-06-27 12:18:42'),
(1071, 751, 71, 1, '10 g', NULL, '00010715', NULL, 1, 'jurkov', '2019-06-27 12:23:41'),
(1072, 752, 71, 1, '10 g', NULL, '00010724', NULL, 1, 'jurkov', '2019-06-27 12:26:06');

--
-- Dumping data for table `api_summary`
--

INSERT INTO `api_summary` (`api_id`, `name`, `form`, `crystallinity`, `particle_size`, `status_eval`, `status_dev`, `status_market`, `patent_expire`, `pri_indication`, `sec_indication`, `melting_point`, `solu_water`, `solu_hcl`, `note`, `last_mod_by`, `last_mod_time`) VALUES
(502, 'Ibuprofen', NULL, NULL, NULL, 'Jelölt', 'Pipeline-ban', 'Piacon', 'Generikus', 'Painkiller', NULL, NULL, NULL, NULL, NULL, 'jurkov', '2019-06-27 12:30:41'),
(503, 'Diclofenac', 'mono-potassium salt', NULL, NULL, 'Elutasítva - FizKém', NULL, 'Piacon', 'Generikus', 'nonsteroidal anti-inflammatory drug (NSAID)', NULL, NULL, '2370', NULL, NULL, 'jurkov', '2019-06-27 12:56:58');

--
-- Dumping data for table `api_pk_attribute`
--

INSERT INTO `api_pk_attribute` (`pk_attribute_id`, `name`, `last_mod_by`, `last_mod_time`) VALUES
(1, 'Cmax (human)', '', '2019-07-01 14:53:46'),
(2, 'tmax (fed)', '', '2019-07-01 14:53:59'),
(3, 'Cmax (rat)', '', '2019-07-01 14:54:09');

--
-- Dumping data for table `api_drug_product`
--

INSERT INTO `api_drug_product` (`drug_product_id`, `api_id`, `name`, `name_alt`, `dosage_form`, `crystallinity`, `particle_size`, `dose_unit_free_form`, `dose_unit_per_day`, `administration`, `note`, `last_mod_by`, `last_mod_time`) VALUES
(427, 502, 'Advil', NULL, 'Tablet', NULL, NULL, NULL, NULL, NULL, NULL, 'jurkov', '2019-06-27 12:32:23'),
(428, 503, 'Cataflam', NULL, 'IR tablet', NULL, NULL, '50 mg', '3', NULL, NULL, 'jurkov', '2019-06-27 13:00:44'),
(429, 503, 'Voltaren', NULL, 'delayed-release tablet', NULL, NULL, '75 mg', '3', NULL, NULL, 'jurkov', '2019-06-27 13:03:42');