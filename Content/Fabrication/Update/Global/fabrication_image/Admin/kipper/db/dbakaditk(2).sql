-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2014 at 10:27 AM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbakaditk`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_berita`
--

CREATE TABLE IF NOT EXISTS `m_berita` (
`id_berita` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `tgl_posting` date NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `m_berita`
--

INSERT INTO `m_berita` (`id_berita`, `judul`, `isi`, `gambar`, `tgl_posting`, `row_status`) VALUES
(1, 'aku edit', '<p>ddddd</p>\r\n', 'ddddd', '2014-11-03', 1),
(2, 'ggg', '<p>ggg</p>\r\n', 'ggg', '0000-00-00', 1),
(3, 'sdfsdf', '<p>sss</p>\r\n', 'sdfsdf', '0000-00-00', 1),
(4, 'afdafaf', '<p>afafafaf</p>\r\n', 'asdfsda', '0000-00-00', 1),
(5, 'asdasd', '<blockquote>\r\n<p>asasdasd</p>\r\n</blockquote>\r\n', '', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `m_bursa_kerja`
--

CREATE TABLE IF NOT EXISTS `m_bursa_kerja` (
`id_bursa_kerja` int(11) NOT NULL,
  `nama_bursa` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `tgl_posting` date NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `m_bursa_kerja`
--

INSERT INTO `m_bursa_kerja` (`id_bursa_kerja`, `nama_bursa`, `keterangan`, `tgl_posting`, `row_status`) VALUES
(1, 'jshjkshajkdhsa edit', '<p>jsahdjkahh edit</p>\r\n', '0000-00-00', 1),
(2, 'snsnana', '<p>sjkahkj</p>\r\n', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_download`
--

CREATE TABLE IF NOT EXISTS `m_download` (
`id_download` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `file` varchar(255) NOT NULL,
  `tgl_posting` date NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `m_download`
--

INSERT INTO `m_download` (`id_download`, `nama`, `isi`, `file`, `tgl_posting`, `row_status`) VALUES
(1, 'jsajksjk', '<p>jksajksahdhdah</p>\r\n', '', '0000-00-00', 0),
(2, 'jkdskjs edit', '<p>sadsakjlnhsakl edit</p>\r\n', '', '0000-00-00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `m_fakultas`
--

CREATE TABLE IF NOT EXISTS `m_fakultas` (
`id_fakultas` int(11) NOT NULL,
  `nama_fakultas` varchar(100) NOT NULL,
  `singkatan` varchar(50) NOT NULL,
  `ket_fakultas` text NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `m_fakultas`
--

INSERT INTO `m_fakultas` (`id_fakultas`, `nama_fakultas`, `singkatan`, `ket_fakultas`, `gambar`, `row_status`) VALUES
(1, 'Fakultas Sains dan Teknologi', 'FST', '<p>gggg</p>\r\n', 'jjjj', 1),
(2, 'Fakultas Matematika dan Ilmu Pengetahuan Alam', 'FMIPA', 'nnnn', 'mmmm', 0),
(3, 'Fakultas Teknologi Kelautan', 'FTK', 'kkkkk', 'mmmmm', 0),
(4, 'asd', 'asd', 'asd', 'asd', 0),
(5, 'asdas', 'asdas', 'aaaaaaaaaa', 'asdasd', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_informasi`
--

CREATE TABLE IF NOT EXISTS `m_informasi` (
`id_informasi` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `posisi_foto` int(11) NOT NULL,
  `isi` text NOT NULL,
  `tgl_posting` date NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `m_informasi`
--

INSERT INTO `m_informasi` (`id_informasi`, `judul`, `posisi_foto`, `isi`, `tgl_posting`, `gambar`, `row_status`) VALUES
(1, 'hsaghjag', 2, '<p>sdsdsdgfdg</p>\r\n', '0000-00-00', '', 1),
(2, 'dsfsdfsdgf ', 2, '<p>dsfsdfs</p>\r\n', '0000-00-00', '', 1),
(3, 'dds', 2, '<p>dsfdssfs</p>\r\n', '0000-00-00', '', 0),
(4, 'ghhgg', 2, '<p>hghggh</p>\r\n', '0000-00-00', '', 0),
(5, 'sdssd', 2, '<p>dsfdffds</p>\r\n', '0000-00-00', '', 0),
(6, 'dfdfsdf', 2, '<p>dfdsfds</p>\r\n', '0000-00-00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_jurusan`
--

CREATE TABLE IF NOT EXISTS `m_jurusan` (
`id_jur` int(11) NOT NULL,
  `id_fakultas` int(11) NOT NULL,
  `nama_jur` varchar(100) NOT NULL,
  `ket_jur` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `m_jurusan`
--

INSERT INTO `m_jurusan` (`id_jur`, `id_fakultas`, `nama_jur`, `ket_jur`, `gambar`, `row_status`) VALUES
(1, 1, 'Teknik Elektro', 'aaa', 'aaaa', 1),
(2, 1, 'Teknik Mesin', '<p>asdahsdhafjfh</p>\r\n\r\n<p>hkljk</p>\r\n\r\n<p>kjlhjkl</p>\r\n', 'Teknik Mesin', 1),
(3, 2, 'ffff', 'ffff', 'fff', 0),
(4, 1, 'asd', 'asd', 'asd', 0),
(5, 1, 'Matematika', '<p>Program Sarjana Matematika (PSM) FMIPA ITS didirikan sejak tahun 1965. PSM ITS telah menjalin kerjasama dengan Delft University of Technology Belanda untuk studi lanjut dan penyempurnaan kurikulum. Kurikulum selalu di-update setiap empat tahun disesuaikan dengan perkembangan iptek. Jurusan Matematika mempunyai visi sebagai institusi unggulan dalam pendidikan, penelitian, pengembangan dan penerapan matematika yang bereputasi internasional dan berorientasi pada teknologi yang berwawasan lingkungan.</p>\r\n\r\n<p>Saat ini, kurikulum 2009 yang berbasis kompetensi dengan konsentrasi pada bidang minat yang diimpelementasikan dalam empat Rumpun Mata Kuliah, yaitu Analisis dan Aljabar, Pemodelan dan Simulasi, Riset Operasi dan Pengolahan Data, serta Ilmu Komputer. Fasilitas laboratorium yang disediakan Jurusan Matematika ITS adalah Lab Komputasi dan Sistem Informasi, Lab Pemodelan dan Simulasi, Lab Analisis dan Aljabar, Lab Ilmu Komputer, serta Lab Riset Operasi.</p>\r\n', '  ', 1),
(6, 1, 'asdas', '<p>bbbbbbbbbbbb</p>\r\n', 'asdasd', 1),
(7, 1, 'aaa', '<p><strong>Apollo 11</strong> was the spaceflight that landed the first humans, Americans <a href="http://en.wikipedia.org/wiki/Neil_Armstrong" title="Neil Armstrong">Neil Armstrong</a> and <a href="http://en.wikipedia.org/wiki/Buzz_Aldrin" title="Buzz Aldrin">Buzz Aldrin</a>, on the Moon on July 20, 1969, at 20:18 UTC. Armstrong became the first to step onto the lunar surface 6 hours later on July 21 at 02:56 UTC.</p>\r\n\r\n<p>Armstrong spent about <s>three and a half</s> two and a half hours outside the spacecraft, Aldrin slightly less; and together they collected 47.5 pounds (21.5&nbsp;kg) of lunar material for return to Earth. A third member of the mission, <a href="http://en.wikipedia.org/wiki/Michael_Collins_%28astronaut%29" title="Michael Collins (astronaut)">Michael Collins</a>, piloted the <a href="http://en.wikipedia.org/wiki/Apollo_Command/Service_Module" title="Apollo Command/Service Module">command</a> spacecraft alone in lunar orbit until Armstrong and Aldrin returned to it for the trip back to Earth.</p>\r\n\r\n<p>Broadcast on live TV to a world-wide audience, Armstrong stepped onto the lunar surface and described the event as:</p>\r\n\r\n<blockquote>\r\n<p>One small step for [a] man, one giant leap for mankind.</p>\r\n</blockquote>\r\n\r\n<p>Apollo 11 effectively ended the <a href="http://en.wikipedia.org/wiki/Space_Race" title="Space Race">Space Race</a> and fulfilled a national goal proposed in 1961 by the late U.S. President <a href="http://en.wikipedia.org/wiki/John_F._Kennedy" title="John F. Kennedy">John F. Kennedy</a> in a speech before the United States Congress:</p>\r\n\r\n<blockquote>\r\n<p>[...] before this decade is out, of landing a man on the Moon and returning him safely to the Earth.</p>\r\n</blockquote>\r\n', 'kkakaka', 1),
(8, 1, 'asdas', '<p>asdad</p>\r\n', 'asdasd', 1);

-- --------------------------------------------------------

--
-- Table structure for table `m_kategoti_jur`
--

CREATE TABLE IF NOT EXISTS `m_kategoti_jur` (
`id_kat_jur` int(11) NOT NULL,
  `nama_kat_jur` varchar(100) NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_menu`
--

CREATE TABLE IF NOT EXISTS `m_menu` (
`id_menu` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `menu_order` int(11) NOT NULL,
  `aktif` char(1) NOT NULL,
  `letak` char(1) NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `m_pejabat`
--

CREATE TABLE IF NOT EXISTS `m_pejabat` (
`id_pejabat` int(11) NOT NULL,
  `nama_pejabat` varchar(255) NOT NULL,
  `jabatan` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `m_pejabat`
--

INSERT INTO `m_pejabat` (`id_pejabat`, `nama_pejabat`, `jabatan`, `keterangan`, `gambar`, `row_status`) VALUES
(1, 'jsdsahsajkhj edit', 'jdjksdjksd edit', '<p>njnsajkskjdkds edit</p>\r\n', '', 1),
(2, 'shjkak', 'dksklj', '<p>wjejiejie</p>\r\n', '', 0),
(3, 'ade', 'staff', '', '', 1),
(4, 'asa', 'asda', '<p>asdasd</p>\r\n', '', 1),
(5, 'jsdsahsajkhj', 'asda', '', '', 0),
(6, 'bb', '', '', '', 0),
(7, 'ade', 'dksklj', '', '', 0),
(8, 'aaa', 'aaa', '<p>aaaa</p>\r\n', '', 1),
(9, 'ggg', 'ggg', '<p>ggg</p>\r\n', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `m_pengumuman`
--

CREATE TABLE IF NOT EXISTS `m_pengumuman` (
`id_pengumuman` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tgl_posting` date NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `m_pengumuman`
--

INSERT INTO `m_pengumuman` (`id_pengumuman`, `judul`, `isi`, `tgl_posting`, `row_status`) VALUES
(1, 'fffff edit', '<p>hhjytd edit</p>\r\n', '2014-11-05', 1),
(2, 'eertrt', '<p>dfdgrgt</p>\r\n', '0000-00-00', 0),
(3, 'eertrt', '<p>ttty</p>\r\n', '0000-00-00', 0),
(4, 'sadasd', '<p>sdafasf</p>\r\n', '0000-00-00', 1),
(5, 'ssa', '<p>asdas</p>\r\n', '0000-00-00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_slide`
--

CREATE TABLE IF NOT EXISTS `m_slide` (
`id_slide` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `m_slide`
--

INSERT INTO `m_slide` (`id_slide`, `judul`, `keterangan`, `gambar`, `row_status`) VALUES
(1, 'rerete', '<p>adfdsfd</p>\r\n', '', 0),
(2, 'fdjkhfkds', '<p>dfdg</p>\r\n', '', 0),
(3, 'fgfdg', '<p>gdfggh</p>\r\n', '', 0),
(4, 'sfsf', '<p>dsfsf</p>\r\n', '', 0),
(5, 'dsfsd', '<p>sdfsd</p>\r\n', '', 1),
(6, 'dfdsfsd', 'dfsdfs', '', 1),
(7, 'ssad', 'sdsda', '', 1),
(8, 'yuyu', 'yyu', '', 1),
(9, 'fsd', 'dfds', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_staff`
--

CREATE TABLE IF NOT EXISTS `m_staff` (
`id_staff` int(11) NOT NULL,
  `nama_staff` varchar(255) NOT NULL,
  `bagian` varchar(255) NOT NULL,
  `keterangan` text NOT NULL,
  `gambar` varchar(255) NOT NULL,
  `row_status` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `m_staff`
--

INSERT INTO `m_staff` (`id_staff`, `nama_staff`, `bagian`, `keterangan`, `gambar`, `row_status`) VALUES
(1, 'sansklansa edit', 'nklscnkln edit', '<p>kkklnklnnk edit</p>\r\n', '', 0),
(2, 'knklnnlnl', 'dssidjisdii', '<p>uhuhuuyu</p>\r\n', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `w_level`
--

CREATE TABLE IF NOT EXISTS `w_level` (
  `id_level` int(11) NOT NULL,
  `nama_level` varchar(50) NOT NULL,
  `created_on` datetime NOT NULL,
  `created_by` varchar(50) NOT NULL,
  `updated_on` datetime NOT NULL,
  `updated_by` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `w_mapping`
--

CREATE TABLE IF NOT EXISTS `w_mapping` (
  `id_maping` bigint(20) NOT NULL,
  `id_level` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `w_menu`
--

CREATE TABLE IF NOT EXISTS `w_menu` (
`id_menu` int(11) NOT NULL,
  `id_parent` int(11) NOT NULL,
  `sub_level` int(11) NOT NULL,
  `nama_menu` varchar(100) NOT NULL,
  `url` varchar(100) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `letak_menu` varchar(10) NOT NULL,
  `urutan_menu` int(11) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `w_menu`
--

INSERT INTO `w_menu` (`id_menu`, `id_parent`, `sub_level`, `nama_menu`, `url`, `icon`, `letak_menu`, `urutan_menu`) VALUES
(1, 0, 1, 'Dashboard', '?page=dashboard', 'fa fa-home', 'kiri', 1),
(2, 0, 1, 'Master', '#', 'fa fa-home', 'kiri', 2),
(3, 2, 2, 'Fakultas', '?page=fakultas', 'fa fa-home', 'kiri', 1),
(4, 2, 2, 'Jurusan ', '?page=jurusan', 'fa fa-home', 'kiri', 2),
(5, 2, 2, 'Kategori Jurusan', '?page=kat_jur', 'fa fa-home', 'kiri', 3),
(6, 0, 1, 'Content Web', '#', 'fa fa-home', 'kiri', 3),
(7, 6, 2, 'Berita', '?page=berita', 'fa fa-home', 'kiri', 1),
(8, 6, 2, 'Pengumuman', '?page=pengumuman', 'fa fa-home', 'kiri', 2),
(9, 6, 2, 'Slide', '?page=slide', 'fa fa-home', 'kiri', 3),
(10, 6, 2, 'Informasi', '?page=informasi', 'fa fa-home', 'kiri', 4),
(11, 6, 2, 'Bursa Kerja', '?page=bursakerja', 'fa fa-home', 'kiri', 5),
(12, 6, 2, 'Download', '?page=download', 'fa fa-home', 'kiri', 6),
(13, 6, 2, 'Pejabat', '?page=pejabat', 'fa fa-home', 'kiri', 7),
(14, 6, 2, 'Staff', '?page=staff', 'fa fa-home', 'kiri', 8),
(16, 6, 2, 'Menu', '?page=m_menu', 'fa fa-home', 'kiri', 9);

-- --------------------------------------------------------

--
-- Table structure for table `w_user`
--

CREATE TABLE IF NOT EXISTS `w_user` (
  `id_user` varchar(50) NOT NULL,
  `nama_user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_level` int(11) NOT NULL,
  `created_on` datetime DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `updated_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `w_user`
--

INSERT INTO `w_user` (`id_user`, `nama_user`, `password`, `id_level`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
('admin', 'Hadi Hermawan', 'admin', 1, '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_berita`
--
ALTER TABLE `m_berita`
 ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `m_bursa_kerja`
--
ALTER TABLE `m_bursa_kerja`
 ADD PRIMARY KEY (`id_bursa_kerja`);

--
-- Indexes for table `m_download`
--
ALTER TABLE `m_download`
 ADD PRIMARY KEY (`id_download`);

--
-- Indexes for table `m_fakultas`
--
ALTER TABLE `m_fakultas`
 ADD PRIMARY KEY (`id_fakultas`);

--
-- Indexes for table `m_informasi`
--
ALTER TABLE `m_informasi`
 ADD PRIMARY KEY (`id_informasi`);

--
-- Indexes for table `m_jurusan`
--
ALTER TABLE `m_jurusan`
 ADD PRIMARY KEY (`id_jur`);

--
-- Indexes for table `m_kategoti_jur`
--
ALTER TABLE `m_kategoti_jur`
 ADD PRIMARY KEY (`id_kat_jur`);

--
-- Indexes for table `m_menu`
--
ALTER TABLE `m_menu`
 ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `m_pejabat`
--
ALTER TABLE `m_pejabat`
 ADD PRIMARY KEY (`id_pejabat`);

--
-- Indexes for table `m_pengumuman`
--
ALTER TABLE `m_pengumuman`
 ADD PRIMARY KEY (`id_pengumuman`);

--
-- Indexes for table `m_slide`
--
ALTER TABLE `m_slide`
 ADD PRIMARY KEY (`id_slide`);

--
-- Indexes for table `m_staff`
--
ALTER TABLE `m_staff`
 ADD PRIMARY KEY (`id_staff`);

--
-- Indexes for table `w_menu`
--
ALTER TABLE `w_menu`
 ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `w_user`
--
ALTER TABLE `w_user`
 ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_berita`
--
ALTER TABLE `m_berita`
MODIFY `id_berita` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `m_bursa_kerja`
--
ALTER TABLE `m_bursa_kerja`
MODIFY `id_bursa_kerja` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `m_download`
--
ALTER TABLE `m_download`
MODIFY `id_download` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `m_fakultas`
--
ALTER TABLE `m_fakultas`
MODIFY `id_fakultas` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `m_informasi`
--
ALTER TABLE `m_informasi`
MODIFY `id_informasi` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `m_jurusan`
--
ALTER TABLE `m_jurusan`
MODIFY `id_jur` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `m_kategoti_jur`
--
ALTER TABLE `m_kategoti_jur`
MODIFY `id_kat_jur` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m_menu`
--
ALTER TABLE `m_menu`
MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `m_pejabat`
--
ALTER TABLE `m_pejabat`
MODIFY `id_pejabat` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `m_pengumuman`
--
ALTER TABLE `m_pengumuman`
MODIFY `id_pengumuman` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `m_slide`
--
ALTER TABLE `m_slide`
MODIFY `id_slide` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `m_staff`
--
ALTER TABLE `m_staff`
MODIFY `id_staff` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `w_menu`
--
ALTER TABLE `w_menu`
MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
