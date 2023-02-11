			<span class="tester"><h3>Main Menu</h3></span>
				<div>
					<ul id="browser" class="filetree">
						<!--
						<li><span class="folder">Master Data</span>
							<ul>
							
								<li><span class="file" id="address" rel="<?php echo BASE_URL ?>pages/master/alamat.php">Address</span></li>
								<li><span class="file" id="armada" rel="<?php echo BASE_URL ?>pages/master/armada.php">Armada</span></li>
								<li><span class="file" id="barang" rel="<?php echo BASE_URL ?>pages/master/barang.php">Barang</span></li>
								<li><span class="file" id="jenis_barang" rel="<?php echo BASE_URL ?>pages/master/jenisbarang.php">Jenis / Tipe Barang</span></li>
								<li><span class="file" id="pelanggan" rel="<?php echo BASE_URL ?>pages/master/pelanggan.php">Pelanggan</span></li>
								<li><span class="file" id="gudang" rel="<?php echo BASE_URL ?>pages/master/mstgudang.php">Gudang</span></li>
								<li><span class="file" id="supplier" rel="<?php echo BASE_URL ?>pages/master/supplier.php">Supplier</span></li>
								
							</ul>
						</li>
						-->
						<li><span class="folder">Online Database</span>
							<ul>
								<li><span class="file" id="address" rel="<?php echo BASE_URL ?>pages/master_online/alamat.php">Address (Alamat) </span></li>
								<li><span class="file" id="category" rel="<?php echo BASE_URL ?>pages/master_online/category.php">Category </span></li>
								<li><span class="file" id="mstcolour" rel="<?php echo BASE_URL ?>pages/master_online/colour.php">Colour (Warna) </span></li>
								<li><span class="file" id="dropshipper" rel="<?php echo BASE_URL ?>pages/master_online/dropshipper.php">Dropshipper </span></li>
								
								<li><span class="file" id="expedition" rel="<?php echo BASE_URL ?>pages/master_online/expedition.php">Expedition </span></li>							
								<li><span class="file" id="products" rel="<?php echo BASE_URL ?>pages/master_online/products.php">Products </span></li>							
							</ul>
						</li>
						<li><span class="folder">Online Transaction</span>
							<ul>
								<li><span class="file" id="depositpelanggan" rel="<?php echo BASE_URL ?>pages/sales_online/deposit_dropshipper.php">Dropshipper Deposit </span></li>
								<li><span class="file" id="trdeposittoko" rel="<?php echo BASE_URL ?>pages/sales_online/trolndeposit.php">Deposit Transaction </span></li>
								<li><span class="file" id="onlinesales" rel="<?php echo BASE_URL ?>pages/sales_online/trolnso.php">Online Sales </span></li>
								<li><span class="file" id="onlinesalescr" rel="<?php echo BASE_URL ?>pages/sales_online/trolnsocr.php">Online Credit </span></li>
								<li><span class="file" id="onlineunservice" rel="<?php echo BASE_URL ?>pages/sales_online/troln_unservice.php">Pending Order</span></li>
								<li><span class="file" id="onlinecancel" rel="<?php echo BASE_URL ?>pages/sales_online/troln_cancel.php">Cancel Order (Online)</span></li>
								<li><span class="file" id="onlinedo" rel="<?php echo BASE_URL ?>pages/sales_online/trolndo.php">Online Delivery</span></li>							
							</ul>
						</li>
						
						<li><span class="folder">Online Summary</span>
							<ul>
								<li><span class="file" id="smoln" rel="<?php echo BASE_URL ?>pages/summary_online/trolnso_sum.php">Online Summary </span></li>
								<li><span class="file" id="dp_oln" rel="<?php echo BASE_URL ?>pages/summary_online/dp_sumidx.php">Dropshipper Statistik </span></li>
								<li><span class="file" id="olndropshipper" rel="<?php echo BASE_URL ?>pages/summary_online/oln_dropshipperidx.php">Sales Online Dropshipper</span></li>
								<li><span class="file" id="smunpaid" rel="<?php echo BASE_URL ?>pages/summary_online/oln_unpaid.php">Unpaid Online</span></li>
								<li><span class="file" id="smbill" rel="<?php echo BASE_URL ?>pages/summary_online/piutang.php">Bill </span></li>
														
							</ul>
						</li>
						<li><span class="folder">Report</span>
							<ul>
								<li><span class="file" id="rptexpedition" rel="<?php echo BASE_URL ?>pages/report_online/rpt_expedtionidx.php">Expedition</span></li>
								<li><span class="file" id="rptprint" rel="<?php echo BASE_URL ?>pages/report_online/rpt_printorderidx.php">Print Order</span></li>
							</ul>
						</li>
						<!--
						<li><span class="folder">Pabrik</span>
							<ul>
								<li><span class="file" id="depositpelangganpb" rel="<?php echo BASE_URL ?>pages/pabrik/deposit_pelangganpb.php">Data Deposit Pelanggan Pabrik </span></li>
								<li><span class="file" id="trdepositpb" rel="<?php echo BASE_URL ?>pages/pabrik/trdepositpb.php">Transaksi Deposit Pabrik </span></li>
								<li><span class="file" id="jual_pabrik" rel="<?php echo BASE_URL ?>pages/pabrik/jual_pabrik.php">Penjualan Pabrik </span></li>
								<li><span class="file" id="jual_pabrikblmbyr" rel="<?php echo BASE_URL ?>pages/pabrik/jualblmbyr_pabrik.php">Penjualan Belum Bayar Pabrik </span></li>
								<li><span class="file" id="piutangpb" rel="<?php echo BASE_URL ?>pages/pabrik/piutangpb.php">Pelunasan Piutang</span></li>
								<li><span class="file" id="piutangpbdm" rel="<?php echo BASE_URL ?>pages/laporan/report_piutangdmidxpb.php">Lap.Pelunasan Piutang Pabrik</span></li>
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
						<li><span class="folder">Toko</span>
							<ul>
								<li><span class="file" id="datapelanggantoko" rel="<?php echo BASE_URL ?>pages/kasir/pelanggan_toko.php">Data Pelanggan Toko </span></li>
								<li><span class="file" id="depositpelanggan" rel="<?php echo BASE_URL ?>pages/kasir/deposit_pelanggan.php">Data Deposit Pelanggan Toko </span></li>
								<li><span class="file" id="trdeposittoko" rel="<?php echo BASE_URL ?>pages/kasir/trdeposit.php">Transaksi Deposit Pelanggan Toko </span></li>
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
								
								<li><span class="file" id="stok_gudang" rel="<?php echo BASE_URL ?>pages/laporan/report_gudang.php">Laporan Stok Gudang ARROW</span></li>
								
								<li><span class="file" id="stok_gudangtc" rel="<?php echo BASE_URL ?>pages/laporan/report_gudangtc.php">Laporan Stok Gudang TC</span></li>								
								
								<li><span class="file" id="report_jualbrg" rel="<?php echo BASE_URL ?>pages/laporan/report_jualbrgidx.php">Lap.Penjualan Barang (dot matrik)</span></li>
                                								
								<li><span class="file" id="report_jualdm" rel="<?php echo BASE_URL ?>pages/laporan/report_jualdmidx.php">Lap.Penjualan Toko (dot matrik)</span></li>
								
								<li><span class="file" id="report_piutang" rel="<?php echo BASE_URL ?>pages/laporan/report_piutangdmidx.php">Lap.Pelunasan Piutang(dot matrik)</span></li>
								
								<li><span class="file" id="stok_toko" rel="<?php echo BASE_URL ?>pages/laporan/report_stok.php">Laporan Stok Toko</span></li>								
							</ul>
						</li>
						<li><span class="folder">Laporan Accounting</span>
							<ul>
								<li><span class="file" id="accjual" rel="<?php echo BASE_URL ?>pages/laporan/accreport_jualdmidx.php">Laporan Penjualan</span></li>
								<li><span class="file" id="accjual" rel="<?php echo BASE_URL ?>pages/laporan/accreport_jualdetailidx.php">Laporan Penjualan Detail</span></li>
								<li><span class="file" id="report_jualpelanggan" rel="<?php echo BASE_URL ?>pages/laporan/report_jual_pelangganidx.php">Lap.Penjualan Per Pelanggan</span></li>
								<li><span class="file" id="rekapjual" rel="<?php echo BASE_URL ?>pages/laporan/rekap_buku_penjualanidx.php">Rekap Buku Penjualan</span></li>
								<li><span class="file" id="report_jualblmlns" rel="<?php echo BASE_URL ?>pages/laporan/accreport_jualblmbyridx.php">Lap.Penjualan Belum Lunas</span>
								</li>
								<li><span class="file" id="report_jualkprekap" rel="<?php echo BASE_URL ?>pages/laporan/accreport_kartupiutangrekapidx.php">Kartu Piutang Rekap</span>
								</li>
								<li><span class="file" id="report_jualkp" rel="<?php echo BASE_URL ?>pages/laporan/accreport_kartupiutangidx.php">Kartu Piutang Detail</span>
								</li>			
							</ul>
						</li>
<li><span class="folder">Laporan Pabrik</span>
							<ul>
								<li><span class="file" id="accjualpb" rel="<?php echo BASE_URL ?>pages/laporan_pabrik/pbaccreport_jualdmidx.php">Laporan Penjualan Pabrik</span></li>
								<li><span class="file" id="accjualdetailpb" rel="<?php echo BASE_URL ?>pages/laporan_pabrik/pbaccreport_jualdetailidx.php">Laporan Penjualan Pabrik Detail</span></li>
								<li><span class="file" id="report_jualpelangganpb" rel="<?php echo BASE_URL ?>pages/laporan_pabrik/report_jualpb_pelangganidx.php">Lap.Penjualan Pabrik Per Pelanggan</span></li>
								<li><span class="file" id="rekapjualpb" rel="<?php echo BASE_URL ?>pages/laporan_pabrik/rekap_buku_penjualanpbidx.php">Rekap Buku Penjualan Pabrik</span></li>
								<li><span class="file" id="report_jualblmlnspb" rel="<?php echo BASE_URL ?>pages/laporan_pabrik/accreport_jualpbblmbyridx.php">Lap.Penjualan Belum Lunas Pabrik</span>
								</li>
								<li><span class="file" id="report_jualkprekappb" rel="<?php echo BASE_URL ?>pages/laporan_pabrik/pbkartupiutangrekapidx.php">Kartu Piutang Rekap Pabrik</span>
								</li>
								<li><span class="file" id="report_jualkppb" rel="<?php echo BASE_URL ?>pages/laporan_pabrik/pbkartupiutangidx.php">Kartu Piutang Detail</span>
								</li>
							</ul>
						</li>
						<li><span class="folder">Statistik</span>
							<ul>
								<li><span class="file" id="statjual" rel="<?php echo BASE_URL ?>pages/statistik/stat_jualidx.php">Rata rata Penjualan</span>
								</li>								
							</ul>
						</li>
						-->
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
				