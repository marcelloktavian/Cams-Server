			<span class="tester"><h3>Main Menu</h3></span>
				<div>
					<ul id="browser" class="filetree">
						
						<li><span class="folder">Online Database</span>
							<ul>
								<li><span class="file" id="address" rel="<?php echo BASE_URL ?>pages/master_online/alamat.php">Address (Alamat) </span></li>
								<li><span class="file" id="category" rel="<?php echo BASE_URL ?>pages/master_online/category.php">Category </span></li>
								<li><span class="file" id="mstcolour" rel="<?php echo BASE_URL ?>pages/master_online/colour.php">Colour (Warna) </span></li>
								<li><span class="file" id="dropshipper" rel="<?php echo BASE_URL ?>pages/master_online/dropshipper.php">Dropshipper </span></li>
								
								<li><span class="file" id="expeditioncat" rel="<?php echo BASE_URL ?>pages/master_online/expeditioncat.php">Expedition Category </span></li>
								
								<li><span class="file" id="expedition" rel="<?php echo BASE_URL ?>pages/master_online/expedition.php">Expedition </span></li>							
								<li><span class="file" id="products" rel="<?php echo BASE_URL ?>pages/master_online/products.php">Products </span></li>							
							</ul>
						</li>
						<li><span class="folder">Online Import</span>
							<ul>
								<li><a href="javascript: void(0)" onclick="window.open('pages/import_XLS/importcamou.php');">Import CAMOU </a></li>
								<li><span class="file" id="presales" rel="<?php echo BASE_URL ?>pages/import_XLS/olnpreso.php">Pre SALES </span></li>							
															
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
								<li><span class="file" id="onlinearchive" rel="<?php echo BASE_URL ?>pages/sales_online/troln_archive.php">Archive Order</span></li>								
								<li><span class="file" id="onlinereturn" rel="<?php echo BASE_URL ?>pages/sales_online/troln_return.php">Online Return</span></li>
								<li><span class="file" id="olnreturn_cf" rel="<?php echo BASE_URL ?>pages/sales_online/troln_return_confirmed.php">Return Confirmed</span></li>								
							</ul>
						</li>
						
						<li><span class="folder">Online Summary</span>
							<ul>
								<li><span class="file" id="smoln" rel="<?php echo BASE_URL ?>pages/summary_online/trolnso_sum.php">Online Summary </span></li>
								<li><span class="file" id="smolncash" rel="<?php echo BASE_URL ?>pages/summary_online/trolnso_sumcash.php">Summary Cash </span></li>
								<li><span class="file" id="smolncr" rel="<?php echo BASE_URL ?>pages/summary_online/trolnso_sumcr.php">Summary Credit </span></li>
								<li><span class="file" id="dp_oln" rel="<?php echo BASE_URL ?>pages/summary_online/dp_sumidx.php">Dropshipper Statistik </span></li>
								<li><span class="file" id="olndropshipper" rel="<?php echo BASE_URL ?>pages/summary_online/oln_dropshipperidx.php">Sales Online Dropshipper</span></li>
								<li><span class="file" id="smunpaid" rel="<?php echo BASE_URL ?>pages/summary_online/oln_unpaid.php">Unpaid Online</span></li>
								<li><span class="file" id="smbill" rel="<?php echo BASE_URL ?>pages/summary_online/piutang.php">Bill </span></li>
														
							</ul>
						</li>
						<li><span class="folder">Report Online</span>
							<ul>
								<li><span class="file" id="rptexpedition" rel="<?php echo BASE_URL ?>pages/report_online/rpt_expedtionidx.php">Expedition</span></li>
								<li><span class="file" id="rptprint" rel="<?php echo BASE_URL ?>pages/report_online/rpt_printorderidx.php">Print Order</span></li>
								<li><span class="file" id="rpttrouble" rel="<?php echo BASE_URL ?>pages/report_online/rpt_productidx.php">Product Sold</span></li>
								<li><span class="file" id="rpttrouble" rel="<?php echo BASE_URL ?>pages/report_online/rpt_trouble.php">Trouble Order</span></li>
							</ul>
						</li>
						
						<li><span class="folder">B2B databases</span>
							<ul>
								<li><span class="file" id="composition" rel="<?php echo BASE_URL ?>pages/master_b2b/composition.php">1_Composition Products </span></li>
								<li><span class="file" id="b2bproducts" rel="<?php echo BASE_URL ?>pages/master_b2b/b2bproducts.php">2_B2B Products </span></li>
								<li><span class="file" id="b2bproductsgrp" rel="<?php echo BASE_URL ?>pages/master_b2b/b2bproductsgrp.php">3_B2B Products Group </span></li>
								<li><span class="file" id="b2bcustomer" rel="<?php echo BASE_URL ?>pages/master_b2b/b2bcustomer.php">4_B2B Customer </span></li>
								<li><span class="file" id="b2bexpedition" rel="<?php echo BASE_URL ?>pages/master_b2b/b2bexpedition.php">5_B2B Expedition </span></li>
								<li><span class="file" id="b2bsalesman" rel="<?php echo BASE_URL ?>pages/master_b2b/b2bsalesman.php">6_B2B Salesman </span></li>
								
							</ul>
						</li>
						
						<li><span class="folder">B2B Transactions</span>
							<ul>
								<li><span class="file" id="trb2bso_add" rel="<?php echo BASE_URL ?>pages/sales_b2b/trb2bso_add.php">Add Sales B2B </span></li>
								<li><span class="file" id="trb2bso" rel="<?php echo BASE_URL ?>pages/sales_b2b/trb2bso.php">Sales B2B </span></li>
								<li><span class="file" id="trb2bso_confirmed" rel="<?php echo BASE_URL ?>pages/sales_b2b/trb2bso_confirmed.php">Confirmed Sales </span>
								<li><span class="file" id="trb2bdo" rel="<?php echo BASE_URL ?>pages/sales_b2b/trb2bdo.php">Delivery Order B2B </span>
								</li>
								<li><span class="file" id="trb2bcomp" rel="<?php echo BASE_URL ?>pages/sales_b2b/trb2bso_composition.php">Product Sold Compositions </span>
								</li>								
							</ul>
						</li>
                         						
						<li><span class="folder">B2B Summary</span>
							<ul>
								<li><span class="file" id="do_b2b" rel="<?php echo BASE_URL ?>pages/summary_b2b/trb2bdo_idx.php">Summary Delivery B2B</span></li>
								</ul>
						</li>
                        <li><span class="folder">INVENTORY</span>
							<ul>
								<li><span class="file" id="mutasi_masuk" rel="<?php echo BASE_URL ?>pages/inventory/invmasuk.php">MUTASI MASUK GUDANG</span></li>
								
								<li><span class="file" id="mutasi_keluar" rel="<?php echo BASE_URL ?>pages/inventory/invkeluar.php">MUTASI KELUAR GUDANG</span></li>
								<li><span class="file" id="inventory" rel="<?php echo BASE_URL ?>pages/inventory/invstok.php">INVENTORY</span></li>
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
				