&nbsp;
<table id="table_list_sparepart" class="table table_list table-striped table-hover table-bordered dataTable">
<thead>
<tr>
<th style="width: 2%;">No</th>
<th style="width: 10%;">Sparepart Code</th>
<th style="width: 20%;">Sparepart Name</th>
<th style="width: 8%;">Price</th>
<th style="width: 8%;">Disc (%)</th>
<th style="width: 3%;">Qty</th>
<th style="width: 10%;">Subtotal</th>
</tr>
</thead>
<tbody><!--?php for($i=0; $i&lt;10; $i++): ?-->
<tr data-rowid="&lt;?php echo $i; ?&gt;">
<td align="center">
<div class="text-center"></div></td>
<td><input id="sparepart_code_&lt;?php echo $i; ?&gt;" class="sparepart_code span12" name="sparepart_code_&lt;?php echo $i; ?&gt;" type="hidden" value="" data-rowid="&lt;?php echo $i; ?&gt;" />
<input id="sparepart_id_&lt;?php echo $i; ?&gt;" class="sparepart_id" name="sparepart_id_&lt;?php echo $i; ?&gt;" type="hidden" value="" data-rowid="&lt;?php echo $i; ?&gt;" /></td>
<td><input id="sparepart_name_&lt;?php echo $i; ?&gt;" class="sparepart_name span12" name="sparepart_name_&lt;?php echo $i; ?&gt;" readonly="readonly" type="text" value="" /></td>
<td><input id="sparepart_hargajual_&lt;?php echo $i; ?&gt;" class="sparepart_hargajual span12" name="sparepart_hargajual_&lt;?php echo $i; ?&gt;" readonly="readonly" type="text" value="" data-rowid="&lt;?php echo $i; ?&gt;" /></td>
<td><input id="sparepart_discount_&lt;?php echo $i; ?&gt;" class="sparepart_discount inputnumber span12" name="sparepart_discount_&lt;?php echo $i; ?&gt;" type="text" value="" data-rowid="&lt;?php echo $i; ?&gt;" /></td>
<td><input id="sales_item_qty_&lt;?php echo $i; ?&gt;" class="sales_item_qty inputnumber span12" name="sales_item_qty_&lt;?php echo $i; ?&gt;" type="text" value="" data-rowid="&lt;?php echo $i; ?&gt;" /></td>
<td><input id="subtotal_&lt;?php echo $i; ?&gt;" class="subtotal span12" name="subtotal_&lt;?php echo $i; ?&gt;" readonly="readonly" type="text" value="" data-rowid="&lt;?php echo $i; ?&gt;" /></td>
</tr>
<!--?php endfor; ?--></tbody>
</table>