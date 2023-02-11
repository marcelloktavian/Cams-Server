<?php
/*
 * By Angga Saputra (Kaskus : oBheLy)
 * http://jt3angga.wordpress.com 
 * jt3angga@gmail.com
 * Lebih lengkap cara pake nya boleh tanya2 ke email, tapi No Urgent karena ada kesibukan juga ane :D
 */
require_once 'tcpdf/tcpdf.php';
class MYPDF extends TCPDF {
	private $title1 = '';
	private $title2 = '';
	private $title3 = '';
	public function setHtmlHeader($title1, $title2, $title3) {
        $this->title1 = $title1;
		$this->title2 = $title2;
		$this->title3 = $title3;
    }
    public function Header() {
        //$image_file = dirname(__FILE__).'\..\include\images\logo.gif';
        //$this->Image($image_file, 10, 10, 30, '', 'GIF', '', 'T', false, 300, '', false, false, 0, false, false, false);		
		$this->SetFont('', '', 9);
		
		if($this->title1 == '' && $this->title2 == '' && $this->title3 == '') {
			$title = '';
		}
		else {
			$title = '<table><tr>';
			$title .= '<th colspan="2" align="left" valign="bottom">'.$this->title1.'</th>';
			$title .= '</tr>';
			$title .= '<tr>';
			$title .= '<th align="left" valign="bottom">'.$this->title2.'</th>';
			$title .= '<th align="right" valign="bottom">'.$this->title3.'</th>';
			$title .= '</tr></table>';
		}
		
		$html = '<table style="margin: 0;"><tr>';
		$html .= '<td>PT. Setia Busanatex<br />JL Cibaligo Blok Mancong No.161 Cimahi 40534<br />
										Phone : (022) 86062105 <br />
										Fax   : (022) 86062106</td>
				</tr></table><hr />'.$title;
		 
		if($title == '') {
			$this->SetMargins(15, 32, 15);	
		}
		else {
			$this->SetMargins(15, 50, 15);
		}
        $this->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'top', $autopadding = true);
    }
}

class Printing extends MYPDF {
	private $userDb = 'root';
	private $passDb = '';
	private $hostDb = 'localhost';
	private $dbName = 'sbsys';
	
	private $conn = '';
	private $link = '';
	private $dbFields  = array();
	private $lbFields  = array();
	private $table = '';
	private $join_table = array();
	private $align = array();
	private $width = array();
	private $limit = '';
	private $order = '';
	private $where = array();
	private $border = true;
	private $rownumber = true;
	private $format = array();
	private $customSql = '';
	private $htmlHeader = '';
	private $tanggal = FALSE;
	private $title1 = '';
	private $title2 = '';
	private $title3 = '';
	private $orient = 'p';
	private $mode = 'production';
	private $color = TRUE;
	function __construct() {
		$this->conn = new PDO('mysql:host='.$this->hostDb.';dbname='.$this->dbName.';charset=utf8', $this->userDb, $this->passDb);
		$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); 
	}
	
	public function color($val) {
		$this->color = $val;
	}
	
	public function orient($val) {
		$this->orient = $val;
	}
	
	public function mode($val) {
		$this->mode = $val;
	}
	
	public function table($value, $alias = '') {
		$this->table = array($value, $alias);
	}
	
	public function border($val) {
		$this->border = $val;
	}
	
	public function rownumber($val) {
		$this->rownumber = $val;
	}
	
	public function htmlHeader($header='', $desc) {
		$this->htmlHeader = array($header, $desc);
	}
	
	public function tanggal($value) {
		$this->tanggal = $value;
	}
	
	public function title1($value) {
		$this->title1 = $value;
	}
	
	public function title2($value) {
		$this->title2 = $value;
	}
	
	public function title3($value) {
		$this->title3 = $value;
	}
	
	public function JoinTable($table, $alias, $glue, $type='INNER') {
		$this->join_table[] = array('table'=>$table, 'glue'=>$glue, 'alias'=>$alias, 'type'=>$type);
	}
	
	public function limit($value) {
		$this->limit = $value;
	}
	
	public function order($column, $orderBy = 'ASC') {
		$this->order = array($column, $orderBy);
	}
	
	public function where($column, $value, $operand = '=', $logic = 'AND', $sep = true) {
		$this->where[] = array('column'=>$column, 'value'=>$value, 'operand'=>$operand, 'logic'=>$logic, 'sep'=>$sep);
	}
	
	public function align($field, $replace) {
		$this->align[$field] = $replace;	    	
	}
	public function formater($field, $replace) {
		$this->format[$field] = $replace;	    	
	}
	
	// public function 
	
	public function width($field, $replace) {
		$this->width[$field] = $replace;	    	
	}
	
	public function dbField($value) {
		$numargs = func_num_args();
	    $arg_list = func_get_args();
	    for ($i = 0; $i < $numargs; $i++) {
	    	$this->dbFields[] = $arg_list[$i];	    	
	    }
		return $this->dbFields;
	}
	
	public function lbField($value) {
		$numargs = func_num_args();
	    $arg_list = func_get_args();
	    for ($i = 0; $i < $numargs; $i++) {
	    	$this->lbFields[] = $arg_list[$i];	    	
	    }
		return $this->lbFields;
	}
	
	private function getBbField() {
		return $this->dbFields;
	}
	
	private function getLbField() {
		return $this->lbFields;
	}
	
	public function customSql($sql) {
		$this->customSql = $sql;
	}
	
	private function runSql() {
		if($this->customSql != '') {
			$rows = $this->conn->query($this->customSql)->fetchAll(PDO::FETCH_ASSOC);
			if(strtolower($this->mode) == 'debug') {
				echo '<div style="font-size: 12px; font-family: tahoma; padding: 5px; border: 1px solid #dddddd; background: #FFD801; color: #000000">'.$this->customSql.'</div>';
			}
			return $rows;
		}
		else {
			$fieldSelect = implode(',', $this->dbFields);
			$limit = $this->limit == '' ? '' : 'limit '.$this->limit;
			$order = $this->order == '' ? '' : 'ORDER BY '.implode(' ', $this->order);
			$join = '';
			foreach ($this->join_table as $r) {
				$alias = isset($r['alias']) ? $r['alias'] : '';
				$join .= $r['type'].' JOIN '.$r['table'].' '.$alias.' ON '.$r['glue'].' ';
			}
			
			$where = 'WHERE 1 '; foreach ($this->where as $r) {
				if($r['sep'])
					$value = '\''.$r['value'].'\'';
				else
					$value = $r['value'];
				$where .= ' '.$r['logic'].' '.$r['column'].' '.$r['operand'].' '.$value.' ';			
			}
			$sql = 'SELECT 
					'.$fieldSelect.' 
					FROM '.implode(' ', $this->table).'
					'.$join.'
					'.$where.'
					'.$order.'
					'.$limit.'
					';
			
			if(strtolower($this->mode) == 'debug') {
				echo '<div style="font-size: 12px; font-family: tahoma; padding: 5px; border: 1px solid #dddddd; background: #FFD801; color: #000000">'.$sql.'</div>';
			}
			$rows = $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
			return $rows;	
		}	
	}
	public function draw($print_type = 'html', $orient = 'p', $sizepaper = 'A4') {
		//$this->orient = $orient;		
		$rows = $this->runSql();
		$echo = '<style type="text/css">
					body {
					    page: main;    					
					}
					table {
						margin-top: 0px;
				    	font-family:Trebuchet MS;
					    font-size: 10pt;
						color:#808282;
					    font-style: normal;
					}
					table.bordered {
				    	border-collapse: collapse;
				    	border: 1px solid black;				    	
				    }
				    table.bordered th{
				    	border: 1px solid #bcc7c9;
				    	padding: 5px;
				    	/*background: #bbbbbb;*/
					}
					table.bordered td{
				    	border: 1px solid #bcc7c9;
				    	padding: 5px;
					}
					
					
					
					header.divHeader {
						position: fixed; top: 0; left: 0; width: 100%; height: 2em;
						padding-bottom: 1em;
						border-bottom: 1px solid;
						margin-bottom: 10px;
					}
					img.left {
						float: left;
						margin-right: 10px;
					}
					@media screen {

				    }
				    @media all {

                    }


				    @media print {
				    	tr.breakme {page-break-before: always !important}



						@page {
				        	size: landscape;
					        counter-increment: page;
							@bottom-right {
								padding-right:20px;
						        content: "Page " counter(page);
							}				 
					    }
						
				    	thead { display: table-header-group; }
				        footer.divFooter {
				            position: fixed;
				            bottom: 0;
				        }
						header.divHeaderScreen {
							display: none;
						}
						header.divHeader {
				            display: block;
				        }
						div#content_print {
							margin-top: 3em;
							thead { display: table-header-group; }
						}						
				    }					
				</style>';
		$classBorder = $this->border ? 'bordered' : '';
		$echo .= '<table class="'.$classBorder.'" style="width: 100%;">';
		$echo .= '<thead>';
		$echo .= '<tr style="font-weight: bold;">';
		if($this->rownumber == TRUE) {
			if($this->color == TRUE) {
				$echo .= '<th style="color:#FFF; background-color:#14b0cc; width: 40px;">No</th>';
			}
			else {
				$echo .= '<th style="color:#000000; width: 40px;">No</th>';
			}
		}
		
		foreach($this->lbFields as $k => $v) {
			$style = '';
			$ixArray = array_values($rows[0]);
			if($this->color == TRUE) {
				$style='style="color:#FFF; background-color:#14b0cc;';
			}
			else {
				$style='style="color:#000;';
			}			
			$newarr = array();
			foreach($rows[0] as $key => $val) {
				$newarr[] = $key; 
			}
			if($this->array_key_exists_r($newarr[$k], $this->width)) {
				$style .= 'width: '.$this->width[$newarr[$k]].';';
			}
			$style.='"';
			$echo .= '<th '.$style.'>'.$v.'</th>';
		}
		$echo .= '</tr>';
		$echo .= '</thead>';
		$echo .= '<tbody>';
		$no = 1;
        $tot_in_page = 0;
		foreach($rows as $row) {
			
			$orientRow = strtolower($this->orient) == 'p' ? 25 : 15;
			
            if ($tot_in_page>0 && $tot_in_page==$orientRow) { $echo .= "<tr class='breakme'>"; $tot_in_page=0; } else $echo .= '<tr>';
            $tot_in_page++;

            if($this->rownumber == TRUE) {
            	if($this->color == TRUE) {
	                if($no%2 == 0) {
						$echo .= '<td align="right" bgcolor="#ebeded" style="width: 40px;">'.$no.'</td>';
					}
					else {
						$echo .= '<td align="right" style="width: 40px;">'.$no.'</td>';
					}
				}
				else {
					$echo .= '<td align="right" style="width: 40px;">'.$no.'</td>';
				}
			}
			foreach($row as $k => $v) {
				$style='style="';
				if($this->array_key_exists_r($k, $this->align)) {
					$style .= 'text-align: '.$this->align[$k].';';
				}
				if($this->array_key_exists_r($k, $this->width)) {
					$style .= 'width: '.$this->width[$k].';';
				}
				$style.='"';
				
				if($this->array_key_exists_r($k, $this->format)) {
					switch ($this->format[$k]) {
						case 'number':
							$v = number_format($v,0);
							break;
						case 'num':
							$v = number_format($v,0);
							break;
						case 'date':
							$v = date('d/m/Y', strtotime($v));
							break;
						case 'datetime':
							$v = date('d/m/Y H:i:s', strtotime($v));
							break;
						case 'curr':
							$v = 'Rp. '.number_format($v,2);
							break;
						default:
							
							break;
					}					
				}
				if($this->color == TRUE) {
					if($no%2 == 0)
					{
						$echo .= '<td bgcolor="#ebeded" '.$style.'>'.$v.'</td>';								
					}	
					else if($no%2 != 0)
					{
						$echo .= '<td '.$style.'>'.$v.'</td>';	
					}
				}
				else {
					$echo .= '<td '.$style.'>'.$v.'</td>';
				}
			}
			$echo .= '</tr>';
			$no++;
		}
		$echo .= '</tbody>';
		$echo .= '</table>';
		switch (strtolower($print_type)) {
			case 'html':
				$pages = '';
				if(strtolower($this->orient) == 'p') {
					$pages = ceil(count($rows) / 25);
				}
				elseif(strtolower($this->orient) == 'l') {
					$pages = ceil(count($rows) / 15);
				}
				//$header_name = nl2br($this->htmlHeader[0]);
				//$header_desc = nl2br($this->htmlHeader[1]);
				//$header_desc = str_replace('\n', '<br />', $header_desc);
				/*$return = '<header class="divHeader">'.$header_name.'<br />'.$header_desc.'<hr /></header>';
				$return .= '<header class="divHeaderScreen">'.$header_name.'<br />'.$header_desc.'<hr /></header>';
				$return .= '<div id="content_print">'.$echo.'</div>';
				$return .= '<footer class="divFooter">Page bla of bla</footer>';*/
				$return = '<html><head><style type="text/css" >
					
					@page {
					    counter-increment: page;
					    counter-reset: page 1;
					    @top-right {
					        content: "Page " counter(page) " of " counter(pages);
					    }
					}
					@media print {
						body {
						    margin: 0;
						    page: main;
						}
						.noprint {
							display: none;
						}	
						#divTotalPages {
							display: table-footer-group;
							text-align: right;
							position: fixed;
							bottom: 0;
							right: 0;
							width: 100%;
							border-top: 1px solid #000000;
							font-size: 8pt;
						}				
						#divFooter {
							display: table-footer-group;
							border-top: 1px solid #000000;
							width: 100%;
							text-align: right;
							position: fixed;
							bottom: 0;
							right: 0;
							margin-right: 13px;
							font-size: 8pt;
						}	
						#divFooter:after {
							counter-increment: page;
    						content: "Halaman "counter(page);
						}	
						#contentTable {
							/*display: table;*/
						}
						table {
						    /*page-break-after:always !important;*/
						    display:table !important;
						    margin:0 auto !important;
						}
					}
					
					@media screen {
						body {
							/*background: #ececec;*/
						}
						#divFooter {
							display: none;
						}
						#divTotalPages {
							display: none;
						}
						#headPrint {
							width: 100%;
							margin-bottom: 10px;							
						}
						#print_btn_box {
							text-align: right;
							width: 100%;
							right: 10px;
							float: right;
						}
						#print_btn_box a {
							margin-right: 10px;
							font-size: 12px;
							font-family: arial;
							text-decoration: none;
							padding: 3px;
							border: 1px solid #888888;
							-webkit-border-radius: 4px;
							-moz-border-radius: 4px;
							border-radius: 4px;
							background: #14B0CC;
							font-weight: bold;
							color: #ffffff;
						}
						#contentTable {
							/*-webkit-border-radius: 4px;
							-moz-border-radius: 4px;
							border-radius: 4px;
							border: 1px solid #aaaaaa;*/
							margin-top: 10px;
							height: 92%;
							overflow-y: auto;
							background: #ffffff;
						}						
					}
				</style>
				</head><body>';
				$return .= '<div class="noprint" id="headPrint">';
				if (strpos("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], '?') !== false) {
					$operand = '&';
				}
				else {
					$operand = '?';
				}
				$return .= '<span id="print_btn_box" class="noprint">';
				$return .= '<a href="#" onclick="window.print()">Print</a>';
				$return .= '<a target="_blank" href="'."http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$operand.'type=pdf">Print to PDF</a>';
				$return .= '<a target="_blank" href="'."http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$operand.'type=xls">Print to XLS</a>';
				$return .= '</span>';
				$return .= '</div><div style="clear: both"></div>';
				$return .= '<div id="contentTable"><table style="width: 100%">';
				if($this->tanggal == true)
					$tgl = 'Tanggal : '.date('d/m/Y');
				else
					$tgl = '';
				$title = '<tr>';
				$title .= '<th align="left" colspan="2" valign="bottom">'.$this->title1.'</th>';
				$title .= '</tr>';
				$title .= '<th align="left" valign="center" >'.$this->title2.'</th>';
				$title .= '<th align="right" valign="center" >'.$this->title3.'</th>';
				$title .= '</tr>';
				$return .= '<thead>
								<tr>
									<th colspan="2" style="border-bottom: 1px solid #222222;" align="left" valign="bottom">
										PT. Setia Busanatex<br />JL Cibaligo Blok Mancong No.161 Cimahi 40534<br />
										Phone : (022) 86062105 <br />
										Fax   : (022) 86062106
									</th>									
								</tr>								
								'.$title.'
							</thead>';
							
				$return .= '<tbody><tr><td colspan="2">'.$echo.'</td></tr></tbody>';
				$return .= '';
				$return .= '</table>';
				$return .= '<div id="divFooter"></div><div id="divTotalPages">/ '.$pages.'</div></div>';
				$return .= '</body></html>';
				return $return;
				break;
			case 'pdf':
				$pdf = new MYPDF($this->orient, 'mm', $sizepaper, true, 'UTF-8', false);
				
				//$pdf->Header($this->title1.'<br />'.$this->title2.'<br />'.$this->title3);
				$pdf->setHtmlHeader($this->title1, $this->title2, $this->title3);
				
				$pdf->Line(15, 28, 282, 28);
				//$pdf->SetHeaderData('', 0, 'test', 'test123');			
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
				
				$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				
				$pdf->SetMargins(15, 27, 15);
				$pdf->SetHeaderMargin(5);
				$pdf->SetFooterMargin(10);
				
				$pdf->SetAutoPageBreak(TRUE, 25);
				
				$pdf->setImageScale(1.25);
						
				$pdf->SetTitle('');
				$pdf->SetSubject('');
				$pdf->SetKeywords('');			
				$pdf->AddPage();
	
				$pdf->writeHTML($echo, true, false, true, false, '');
				
				$pdf->lastPage();
				$name = 'Print_'.date('Y-m-d H:i:s');
				$pdf->Output($name, 'I');
				break;
			case 'xls':
				if($this->tanggal == true)
					$tgl = 'Tanggal : '.date('d/m/Y');
				else
					$tgl = '';
				
				$title = $this->title1.'<br />'.$this->title2.'<br />'.$this->title3;
				
				$name = 'Print_'.date('Y-m-d_H-i-s');
				header("Content-type: application/vnd-ms-excel");				 
				header("Content-Disposition: attachment; filename=".$name.".xls");
				//$header_name = nl2br($this->htmlHeader[0]);
				//$header_desc = nl2br($this->htmlHeader[1]);
				//$header_desc = str_replace('\n', '<br />', $header_desc);
				//$return = $header_name.'<br />'.$header_desc.'<hr />'.$echo;
				$header = '
							<span>PT. Setia Busanatex <br />JL Cibaligo Blok Mancong No.161 Cimahi 40534<br />
							Phone : (022) 86062105 (hunting)<br />
							Fax   : (022) 86062106</span><br /><br />';
				$header .= '<span style="float: right">'.$title.'</span>';
				$return = $header.'<hr />'.$echo;
				echo $return;
				break;
			default:
				
				break;
		}	
	}

	private function array_key_exists_r($needle, $haystack) {
	    $result = array_key_exists($needle, $haystack);
	    if ($result) return $result;
	    foreach ($haystack as $v) {
	        if (is_array($v)) {
	            $result = array_key_exists_r($needle, $v);
	        }
	        if ($result) return $result;
	    }
	    return $result;
	}
	
	private function search_array($needle, $haystack) {
    	if(in_array($needle, $haystack)) {
        	return true;
     	}
     	foreach($haystack as $element) {
        	if(is_array($element) && search_array($needle, $element))
            	return true;
     	}
   		return false;
	}


}

//Sengaja gk di tutup tag PHP nya