<?php
   include 'Menu.php';
?>
<aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="<?php echo base_url('assets/AdminBSB');?>/images/<?php echo $this->session->userdata('foto'); ?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo $this->session->userdata('nama_pegawai');?></div>
                    <div class="email"><?php echo $tgl_hari . ", " . $tgl_indo; ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="<?php echo site_url('/Beranda_Controller/ComingSoon');?>"><i class="material-icons">person</i>Profil</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo site_url('/Beranda_Controller/logout');?>"><i class="material-icons">input</i>Keluar</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li <?php echo $beranda;?>>
                        <a href="<?php echo site_url('/Beranda_Controller/index'); ?>">
                            <i class="material-icons">home</i>
                            <span>Beranda</span>
                        </a>
                    </li>
                    <?php if($this->session->userdata('id_posisi') == "SKU" || $this->session->userdata('id_posisi') == "PMK" || $this->session->userdata('id_posisi') == "KPG"){ ?>
                    <li class="header">MENU MASTER DATA</li>
                    <?php if($this->session->userdata('id_posisi') == "SKU"){ ?>
                    <li <?php echo $akun?>>
                        <a href="<?php echo site_url('/Akun_Controller/index'); ?>">
                            <i class="material-icons">local_atm</i>
                            <span>Akun</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "PMK"){ ?>
                    <li <?php echo $produk; ?>>
                        <a href="<?php echo site_url('/Produk_Controller/index'); ?>">
                            <i class="material-icons">school</i>
                            <span>Produk</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "SKU"){?>
                    <li <?php echo $beban; ?>>
                        <a href="<?php echo site_url('/Beban_Controller/index'); ?>">
                            <i class="material-icons">bug_report</i>
                            <span>Beban-Beban</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "SKU"){?>
                     <li <?php echo $tarifposisi; ?>>
                        <a href="<?php echo site_url('/TarifPosisi_Controller/index'); ?>">
                            <i class="material-icons">assignment_ind</i>
                            <span>Tarif Posisi</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "KPG"){?>
                    <li <?php echo $bahan; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">local_activity</i>
                            <span>Bahan</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $bahanutama; ?>>
                                <a href="<?php echo site_url('/BahanUtama_Controller/index'); ?>">Bahan Utama</a>
                            </li>
                            <li <?php echo $bahanpenolong; ?>>
                                <a href="<?php echo site_url('/BahanPenolong_Controller/index'); ?>">Bahan Penolong</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "SKU"){?>
                    <li <?php echo $pegawai; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">supervisor_account</i>
                            <span>Pegawai</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $pegawaitetap; ?>>
                                <a href="<?php echo site_url('/PegawaiTetap_Controller/index'); ?>">Pegawai Tetap</a>
                            </li>
                            <li <?php echo $pegawaikontrak; ?>>
                                <a href="<?php echo site_url('/PegawaiKontrak_Controller/index'); ?>">Pegawai Kontrak</a>
                            </li>
                            <li <?php echo $pegawaikeluar; ?>>
                                <a href="<?php echo site_url('/Pegawai_Controller/PegawaiKeluar'); ?>">Pegawai Keluar</a>
                            </li>
                            <li <?php echo $pindahjabatan; ?>>
                                <a href="<?php echo site_url('/Pegawai_Controller/PindahJabatan'); ?>">Pindah Jabatan</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "PMK"){ ?> 
                     <li <?php echo $bom; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">extension</i>
                            <span>Bill Of Material</span>
                        </a>
                         <ul class="ml-menu">
                            <li <?php echo $bombahantambah; ?>>
                                <a href="<?php echo site_url('/Bom_Controller/index'); ?>">Tambah BOM Produk (Bahan)</a>
                            </li>
                            <li <?php echo $bombebantambah; ?>>
                                <a href="<?php echo site_url('/Bom_Controller/BomProduk'); ?>">Tambah BOM Produk (Beban)</a>
                            </li>
                            <li <?php echo $bomlihat; ?>>
                                <a href="<?php echo site_url('/Bom_Controller/LihatBom'); ?>">Lihat Bill Of Material</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                <?php } ?>
                    <li class="header">MENU TRANSAKSI</li>
                    <?php if($this->session->userdata('id_posisi') == "KPP"){ ?>
                    <li <?php echo $konfirmasi?>>
                        <a href="<?php echo site_url('/Pesanan_Controller/KonfirmasiPilihPesanan'); ?>">
                            <i class="material-icons">assignment_turned_in</i>
                            <span>Konfirmasi Pesanan</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "PMK" || $this->session->userdata('id_posisi') == "SKU"){?>
                     <li <?php echo $absensi; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">how_to_reg</i>
                            <span>Kehadiran</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $lihatkehadiran; ?>>
                                <a href="<?php echo site_url('/Presensi_Controller/LihatPresensi'); ?>">Lihat Kehadiran</a>
                            </li>
                            <li <?php echo $lihatjumlah; ?>>
                                <a href="<?php echo site_url('//Presensi_Controller/LihatJumlahPresensi'); ?>">Lihat Jumlah Kehadiran</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "STP"){?>
                     <li <?php echo $pesanan; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">rate_review</i>
                            <span>Pesanan</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $tambahpesanan; ?>>
                                <a href="<?php echo site_url('/Pesanan_Controller/index'); ?>">Tambah Pesanan</a>
                            </li>
                            <li <?php echo $lihatpesanan; ?>>
                                <a href="<?php echo site_url('/Pesanan_Controller/LihatPesanan'); ?>">Lihat Pesanan</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "KPG"){?>
                    <li <?php echo $pembelian; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">redeem</i>
                            <span>Pembelian</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $tambahpembelian; ?>>
                                <a href="<?php echo site_url('/Pembelian_Controller/index'); ?>">Tambah Pembelian</a>
                            </li>
                            <li <?php echo $lihatpembelian; ?>>
                                <a href="<?php echo site_url('/Pembelian_Controller/LihatPembelian'); ?>">Lihat Pembelian</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "KPG"){?>
                    <li <?php echo $penyerahan; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">local_shipping</i>
                            <span>Penyerahan Bahan</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $tambahpenyerahan; ?>>
                                <a href="<?php echo site_url('/Penyerahan_Controller/index'); ?>">Tambah Penyerahan Bahan</a>
                            </li>
                            <li <?php echo $lihatpenyerahan; ?>>
                                <a href="<?php echo site_url('/Penyerahan_Controller/LihatPenyerahan'); ?>">Lihat Penyerahan Bahan</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "STP"){?>
                    <li <?php echo $penjualan; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">shopping_basket</i>
                            <span>Penjualan</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $tambahpenjualan; ?>>
                                <a href="<?php echo site_url('/Penjualan_Controller/index'); ?>">Tambah Penjualan Produk</a>
                            </li>
                            <li <?php echo $lihatpenjualan; ?>>
                                <a href="<?php echo site_url('/Penjualan_Controller/LihatPenjualan'); ?>">Lihat Penjualan Produk</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "SKU"){?>
                    <li <?php echo $gaji_upah; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">contact_mail</i>
                            <span>Penggajian & Pengupahan</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $tambahgajiupah; ?>>
                                <a href="<?php echo site_url('/GajiUpah_Controller/index'); ?>">Bayar Gaji & Upah</a>
                            </li>
                            <li <?php echo $lihatgaji; ?>>
                                <a href="<?php echo site_url('/GajiUpah_Controller/LihatGaji'); ?>">Lihat Gaji</a>
                            </li>
                            <li <?php echo $lihatupah; ?>>
                                <a href="<?php echo site_url('/GajiUpah_Controller/LihatUpah'); ?>">Lihat Upah</a>
                            </li>
                        </ul>
                    </li>
                    <?php }?>
                    <?php if($this->session->userdata('id_posisi') == "SKU"){ ?>
                    <li <?php echo $pembayaranbeban; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">local_library</i>
                            <span>Pembayaran Beban</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $tambahpembayaranbeban; ?>>
                                <a href="<?php echo site_url('/PembayaranBeban_Controller/index'); ?>">Tambah Pembayaran Beban</a>
                            </li>
                            <li <?php echo $lihatpembayaranbeban; ?>>
                                <a href="<?php echo site_url('/PembayaranBeban_Controller/LihatPembayaranBeban'); ?>">Lihat Pembayaran Beban</a>
                            </li>
                        </ul>
                    </li>
                    <li <?php echo $setoran; ?>>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">money</i>
                            <span>Setoran Modal</span>
                        </a>
                        <ul class="ml-menu">
                            <li <?php echo $tambahsetoran; ?>>
                                <a href="<?php echo site_url('/Setoran_Controller/index'); ?>">Tambah Setoran Modal</a>
                            </li>
                            <li <?php echo $lihatsetoran; ?>>
                                <a href="<?php echo site_url('/Setoran_Controller/LihatSetoran'); ?>">Lihat Setoran Modal</a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "PMK" || $this->session->userdata('id_posisi') == "SKU"){?>
                    <li class="header">MENU LAPORAN KEPEGAWAIAN</li>
                    </li>
                    <li <?php echo $laporangaji;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/LaporanGaji'); ?>">
                            <i class="material-icons">card_membership</i>
                            <span>Penggajian</span>
                        </a>
                    </li>
                    <li <?php echo $laporanupah;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/LaporanUpah'); ?>">
                            <i class="material-icons">receipt</i>
                            <span>Pengupahan</span>
                        </a>
                    </li>
                    <?php } ?>
                    <?php if($this->session->userdata('id_posisi') == "PMK" || $this->session->userdata('id_posisi') == "KPP" || $this->session->userdata('id_posisi') == "SKU"){?>
                    <li class="header">MENU LAPORAN KEUANGAN</li>
                    <?php if($this->session->userdata('id_posisi') == "PMK" || $this->session->userdata('id_posisi') == "SKU"){ ?>
                    <li <?php echo $jurnal;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/Jurnal'); ?>">
                            <i class="material-icons">assignment</i>
                            <span>Jurnal Umum</span>
                        </a>
                    </li> 
                    <li <?php echo $bukubesar;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/BukuBesar'); ?>">
                            <i class="material-icons">book</i>
                            <span>Buku Besar</span>
                        </a>
                    </li>
                    <li <?php echo $hpproduksi;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/HargaPokokProduksi'); ?>">
                            <i class="material-icons">assignment_late</i>
                            <span>Harga Pokok Produksi</span>
                        </a>
                    </li>
                    <li <?php echo $hppenjualan;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/HargaPokokPenjualan'); ?>">
                            <i class="material-icons">assignment_return</i>
                            <span>Harga Pokok Penjualan</span>
                        </a>
                    </li>
                    <li <?php echo $labarugi;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/LabaRugi'); ?>">
                            <i class="material-icons">description</i>
                            <span>Laba Rugi</span>
                        </a>
                <?php }else if($this->session->userdata('id_posisi') == "KPP"){ ?>
                    <li <?php echo $hpproduksi;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/HargaPokokProduksi'); ?>">
                            <i class="material-icons">assignment_late</i>
                            <span>Harga Pokok Produksi</span>
                        </a>
                    </li>
                    <li <?php echo $hppenjualan;?>>
                        <a href="<?php echo site_url('/Laporan_Controller/HargaPokokPenjualan'); ?>">
                            <i class="material-icons">assignment_return</i>
                            <span>Harga Pokok Penjualan</span>
                        </a>
                    </li>
                <?php } ?>
                <?php } ?>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy;2019 <a href="javascript:void(0);">PeKR</a>.
                </div>
                <div class="version">
                    <b>Versi:</b> 1.0.0
                </div>
            </div>
            <!-- #Footer -->
        </aside>