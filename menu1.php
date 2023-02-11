			<span class="tester"><h3>Main Menu</h3></span>
				<div>
					<ul id="browser" class="filetree">
						<li><span class="folder">Master Data</span>
							<ul>
								<li><span class="file" id="armada" rel="<?php echo BASE_URL ?>pages/master/armada.php">Armada</span></li>
								<li><span class="file" id="barang" rel="<?php echo BASE_URL ?>pages/master/barang.php">Barang</span></li>
								<li><span class="file" id="jenis_barang" rel="<?php echo BASE_URL ?>pages/master/jenisbarang.php">Jenis / Tipe Barang</span></li>
								<li><span class="file" id="pelanggan" rel="<?php echo BASE_URL ?>pages/master/pelanggan.php">Pelanggan</span></li>
								<li><span class="file" id="gudang" rel="<?php echo BASE_URL ?>pages/master/mstgudang.php">Gudang</span></li>
								<li><span class="file" id="supplier" rel="<?php echo BASE_URL ?>pages/master/supplier.php">Supplier</span></li>
								
							</ul>
						</li>
						
						<li><span class="folder">Transaksi</span>
							<ul>
								<li><span class="file" id="trgudang" rel="<?php echo BASE_URL ?>pages/gudang/gudang.php">Penerimaan Gudang </span></li>
								<li><span class="file" id="beli" rel="<?php echo BASE_URL ?>pages/kirim/beli.php">Pengiriman Barang </span></li>
								<li><span class="file" id="terima_brg" rel="<?php echo BASE_URL ?>pages/terima_barang/terima_barang.php">Penerimaan Toko </span></li>
								<li><span class="file" id="jual" rel="<?php echo BASE_URL ?>pages/jual/jual.php">Penjualan</span></li>
								<li><span class="file" id="jualblmbyr" rel="<?php echo BASE_URL ?>pages/jual/jualblmbyr.php">Penjualan Belum Lunas</span></li>
								<li><span class="file" id="piutang" rel="<?php echo BASE_URL ?>pages/piutang/piutang.php">Pelunasan Piutang</span></li>
								
							</ul>
						</li>						
						<li><span class="folder">Kasir</span>
							<ul>
								<li><span class="file" id="kasir" rel="<?php echo BASE_URL ?>pages/kasir/kasir_pelanggan.php">Kasir </span></li>							
							    <li><span class="file" id="kasirblmbyr" rel="<?php echo BASE_URL ?>pages/jual/jualblmbyr.php">Penjualan Belum Lunas</span>
							    </li>
								<li><span class="file" id="kasirpiutang" rel="<?php echo BASE_URL ?>pages/piutang/piutang.php">Pelunasan Piutang</span></li>
								<li><span class="file" id="kasirlpjual" rel="<?php echo BASE_URL ?>pages/laporan/report_jualbrgidx.php">Laporan Penjualan Barang </span></li>	
							    <li><span class="file" id="kasirlpbarang" rel="<?php echo BASE_URL ?>pages/laporan/report_jualdmidx.php">Laporan Penjualan Kasir</span></li>
								<li><span class="file" id="kasirlppiutang" rel="<?php echo BASE_URL ?>pages/laporan/report_piutangdmidx.php">Lap.Pelunasan Piutang</span></li>
						        </ul>
						</li>						
						<li><span class="folder">Laporan</span>
							<ul>
								<li><span class="file" id="report_project" rel="<?php echo BASE_URL ?>pages/report_project.php">Report Project</span></li>								
								<li><span class="file" id="report_beli" rel="<?php echo BASE_URL ?>pages/laporan/report_beli.php">Laporan Pengiriman Barang</span></li>
								
								<li><span class="file" id="stok_gudang" rel="<?php echo BASE_URL ?>pages/laporan/report_gudang.php">Laporan Stok Gudang</span></li>								
								<li><span class="file" id="report_jual" rel="<?php echo BASE_URL ?>pages/laporan/report_jual.php">Lap.Penjualan Toko (pdf)</span></li>
								
								<li><span class="file" id="report_jualbrg" rel="<?php echo BASE_URL ?>pages/laporan/report_jualbrgidx.php">Lap.Penjualan Barang (dot matrik)</span></li>								
								<li><span class="file" id="report_jualdm" rel="<?php echo BASE_URL ?>pages/laporan/report_jualdmidx.php">Lap.Penjualan Toko (dot matrik)</span></li>
								
								<li><span class="file" id="report_piutang" rel="<?php echo BASE_URL ?>pages/laporan/report_piutangdmidx.php">Lap.Pelunasan Piutang(dot matrik)</span></li>
								
								<li><span class="file" id="stok_toko" rel="<?php echo BASE_URL ?>pages/laporan/report_stok.php">Laporan Stok Toko</span></li>								
							</ul>
						</li>
						<li><span class="folder">Statistik</span>
							<ul>
								<li><span class="file" id="outgudang" rel="<?php echo BASE_URL ?>pages/statistik/outgudang.php">Pengeluaran Gudang</span></li>							
							</ul>
						</li>
					</ul>
				</div>
				<span class="tester"><h3>Setting</h3></span>
				<div>
					<ul id="browser2" class="filetree">
						<li><span class="folder">Profile</span>
							<ul>
								<li><span class="file" style="cursor: pointer" id="ganti_password" rel="<?php echo BASE_URL ?>pages/change_password.php">Change Password</span></li>
								<!--
								<li><span class="logout" rel=""><a href="<?php echo BASE_URL ?>logout.php">Logout</a></span></li>
								-->
							</ul>
						</li>												
					</ul>
				</div>
				