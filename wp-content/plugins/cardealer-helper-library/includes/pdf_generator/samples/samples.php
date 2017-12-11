<?php
/*======================================================================================================================================================
Pdf-1
====================================================================================================================================================*/
function cardealer_pdf_sample_1(){    
    ob_start();
    ?>    
    <table style="background-color: #c2c2c2;" border="0" cellspacing="0" cellpadding="20" align="center">
    	<tbody>
    		<tr>
    			<td>
    				<table border="0" width="600" cellspacing="0" cellpadding="0" align="center">
    					<tbody>
    						<tr>
    							<td>
    								<table border="0" width="100%" cellspacing="0" cellpadding="0">
    									<tbody>
    										<tr>
    											<td align="left" valign="top" width="44%">
    												<table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
    													<tbody>
    														<tr>
    															<td bgcolor="#FFFFFF" height="94" width="265"><img src="{{image}}" alt="" width="265" height="94" /></td>
    														</tr>
    													</tbody>
    												</table>
    											</td>
    											<td align="left" valign="top" width="1%"></td>
    											<td align="left" valign="top" width="54%">
    												<table style="height: 80px;" border="0" width="100%" cellspacing="0" cellpadding="0">
    													<tbody>
    														<tr>
    															<td align="center" bgcolor="#FFFFFF">
    															<div style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;">Potenza Car Dealer Group</div>
    															<div style="font-size: 16px; font-weight: bold;">Adajan Pal-Gam</div>
    															<div style="font-size: 16px; font-weight: bold;">Surat</div></td>
    														</tr>
    													</tbody>
    												</table>
    											</td>
    										</tr>
    									</tbody>
    								</table>
    							</td>
    						</tr>
    						<tr>
    							<td height="15"></td>
    						</tr>
    						<tr>
    							<td>
    								<table border="0" width="100%" cellspacing="0" cellpadding="0">
    									<tbody>
    										<tr>
    											<td bgcolor="#FFFFFF">
    												<table border="0" width="100%" cellspacing="0" cellpadding="0">
    													<tbody>
    														<tr>
    															<td align="left" valign="top" width="100%">
    																<table border="0" width="100%" cellspacing="0" cellpadding="0">
    																	<tbody>
    																		<tr>
    																			<td class="headingbig" style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 30px;" align="center" height="35">{{year}} {{make}} {{model}}</td>
    																		</tr>
    																	</tbody>
    																</table>
    															</td>
    														</tr>
    													</tbody>
    												</table>
    											</td>
    										</tr>
    									</tbody>
    								</table>
    							</td>
    						</tr>
    						<tr>
    							<td height="15"></td>
    						</tr>
    						<tr>
    							<td>
    								<table border="0" width="100%" cellspacing="0" cellpadding="0">
    									<tbody>
    										<tr>
    											<td align="left" valign="top" bgcolor="#FFFFFF" width="45%">
    												<table border="0" width="100%" cellspacing="0" cellpadding="0">
    													<tbody>
    														<tr>
    															<td>
    																<table style="height: 276px;" border="0" cellspacing="2" cellpadding="0" align="center">
    																	<tbody>
    																		<tr>
    																			<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 18px;" align="center">SPECIFICATION</td>
    																		</tr>
    																		<tr>
    																			<td align="left" height="10">
    																				<ul>
    																					<li>
    																						<b>Regular Price :</b> {{currency_symbol}}{{regular_price}}
    																					</li>
    																					<li>
    																						<b>Sale Price :</b> {{currency_symbol}}{{sale_price}}
    																					</li>
    																				    <li>
    																						<b>Year :</b>{{year}}
    																					</li>
    																					<li>
    																						<b>Make :</b>{{make}}
    																					</li>
    																					<li>
    																						<b>Model :</b>{{model}}
    																					</li>
    																					<li>
    																						<b>Body Style :</b>{{body_style}}
    																					</li>
    																					<li>
    																						<b>Condition :</b>{{condition}}
    																					</li>
    																					<li>
    																						<b>Mileage :</b>{{mileage}}
    																					</li>
    																					<li>
    																						<b>Transmission :</b>{{transmission}}
    																					</li>
    																					<li>
    																						<b>Drivetrain :</b>{{drivetrain}}
    																					</li>
    																					<li>
    																						<b>Engine :</b>{{engine}}
    																					</li>
    																					<li>
    																						<b>Fuel Type :</b>{{fuel_type}}
    																					</li>
    																					<li>
    																						<b>Fuel Economy :</b>{{fuel_economy}}
    																					</li>
    																					<li>
    																						<b>Trim :</b>{{trim}}
    																					</li>
    																					<li>
    																						<b>Exterior Color :</b>{{exterior_color}}
    																					</li>
    																					<li>
    																						<b>Interior Color :</b>{{interior_color}}
    																					</li>
    																					<li>
    																						<b>Stock :</b>{{stock_number}}
    																					</li>
    																					<li>
    																						<b>VIN :</b>{{vin_number}}
    																					</li>
    																				</ul>
    																			</td>
    																		</tr>
    																	</tbody>
    																</table>
    															</td>
    														</tr>
    													</tbody>
    												</table>
    												&nbsp;
    												&nbsp;
    												&nbsp;
    												&nbsp;
    												<table style="height: 137px;" border="0" cellspacing="0" cellpadding="0">
    													<tbody>
    														<tr>
    															<td style="height: 35px;" align="center" width="37%"><img src="{{city_image}}" alt="" width="52" height="34" /></td>
    															<td style="height: 35px;" align="center" width="25%"></td>
    															<td style="height: 35px;" align="center" width="38%"><img src="{{hwy_image}}" alt="" width="62" height="27" /></td>
    														</tr>
    														<tr>
    															<td align="center" height="90" style="font-size: 30px;"><b>{{city_mpg}}</b></td>
    															<td align="center"><img src="{{fuel_image}}" alt="" /></td>
    															<td align="center" style="font-size: 30px;"><b>{{high_waympg}}</b></td>
    														</tr>
    													</tbody>
    												</table>
    											</td>
    											<td align="left" valign="top" width="1%"></td>
    											<td align="left" valign="top" width="54%">
    												<table border="0" width="100%" cellspacing="0" cellpadding="0">
    													<tbody>
    														<tr>
    															<td>
    																<table border="0" width="100%" cellspacing="0" cellpadding="0">
    																	<tbody>
    																		<tr>
    																			<td align="left" valign="top" bgcolor="#FFFFFF" height="540">
    																				<table border="0" width="100%" cellspacing="0" cellpadding="0">
    																					<tbody>
    																						<tr>
    																							<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center">FEATURES/ACCESSORIES</td>
    																						</tr>
    																						<tr style="display: none;">
    																							<td height="15"><strong>Certified Pre-owned</strong></td>
    																						</tr>
    																						<tr>
    																							<td>
    																								<ul>
    																									{{features_options}}
    																								</ul>
    																							</td>
    																						</tr>
    																					</tbody>
    																				</table>
    																			</td>
    																		</tr>
    																	</tbody>
    																</table>
    															</td>
    														</tr>
    													</tbody>
    												</table>
    											</td>
    										</tr>
    									</tbody>
    								</table>
    							</td>
    						</tr>
    					</tbody>
    				</table>
    			</td>
    		</tr>
    	</tbody>
    </table>
    <?php
    return ob_get_clean();
}

/**
* pdf-2
*/
function cardealer_pdf_sample_2(){    
    ob_start();
    ?>
    <table border="1" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 40px;" align="center" height="25">{{year}} {{make}} {{model}}</td>
    </tr>
    <tr>
    <td align="center" height="210">
    <table border="0" width="100%" cellspacing="4" cellpadding="4">
    <tbody>
    <tr>
    <td><img src="{{image}}" width="380" height="200" /></td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    <table border="1" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td align="left" valign="top" width="56%">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
    <tr>
    <td align="left" height="25"><b> VIN :</b> {{vin_number}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Stock :</b> {{stock_number}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Engine :</b> {{engine}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Transmission :</b> {{transmission}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Drivetrain :</b> {{drivetrain}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Mileage :</b> {{mileage}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Int. Color :</b> {{interior_color}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Exterior Color :</b> {{exterior_color}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Fuel Type :</b> {{fuel_type}}</td>
    </tr>
    <tr>
    <td align="left" height="25"><b> Fuel Economoy :</b> {{fuel_economy}}</td>
    </tr>
    </tbody>
    </table>
    </td>
    <td align="left" valign="top" width="44%">
    <table border="0" width="98%" cellspacing="1" cellpadding="1">
    <tbody>
    <tr>
    <td height="25"><b> Regular Price :</b> {{currency_symbol}}{{regular_price}}</td>
    </tr>
    <tr>
    <td height="25"><b> Sale Price :</b> {{currency_symbol}}{{sale_price}}</td>
    </tr>
    <tr>
    <td height="25"><b> Year :</b> {{year}}</td>
    </tr>
    <tr>
    <td height="25"><b> Make :</b> {{make}}</td>
    </tr>
    <tr>
    <td height="25"><b> Model :</b> {{model}}</td>
    </tr>
    <tr>
    <td height="25"><b> Body Style :</b> {{body_style}}</td>
    </tr>
    <tr>
    <td height="25"><b> Condition :</b> {{condition}}</td>
    </tr>
    <tr>
    <td height="25"><b> Trim :  </b> {{trim}}</td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    <tbody>
    <tr>
    <td>
    <table border="0" width="100%" cellspacing="1" cellpadding="1">
    <tbody>
    <tr>
    <td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 24px;" align="center">
    <h3>FUEL EFFICIENCY</h3>
    </td>
    </tr>
    <tr>
    <td></td>
    </tr>
    <tr>
    <td align="center">
    <table border="0" width="100%" cellspacing="1" cellpadding="1">
    <tbody>
    <tr>
    <td><img src="{{city_image}}" alt=""/></td>
    <td rowspan="2"><img src="{{fuel_image}}" /></td>
    <td><img src="{{hwy_image}}" alt=""/></td>
    </tr>
    <tr>
    <td style="font-size: 24px;">{{city_mpg}}</td>
    <td style="font-size: 24px;">{{high_waympg}}</td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    </td>
    <td align="left" valign="top" bgcolor="#FFFFFF" height="340">
    <table border="0" width="100%" cellspacing="1" cellpadding="1">
    <tbody>
    <tr>
    <td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center">FEATURES/ACCESSORIES</td>
    </tr>
    <tr style="display: none;">
    <td height="15"><strong>Certified Pre-owned</strong></td>
    </tr>
    <tr>
    <td>
    <ul>
    {{features_options}}
    </ul>
    </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    <?php
    return ob_get_clean();
} 


/**
* pdf-3
*/
function cardealer_pdf_sample_3(){    
    ob_start();
    ?>

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
    	<tbody>
    		<tr>
    			<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 35px;" align="center" height="25">{{year}} {{make}} {{model}}</td>
    		</tr>
    		<tr>
    			<td align="left" height="180" width="50%">
    				<table border="0" width="100%" cellspacing="4" cellpadding="4">
    					<tbody>
    						<tr>
    							<td><img src="{{image}}" width="320" height="180" /></td>
    						</tr>
    					</tbody>
    				</table>
    			</td>
    			<td align="left" valign="top" width="50%">
    				<table border="0" cellspacing="1" cellpadding="1">
    					<tbody>
    						<tr>
    							<td align="center">
    								<div style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;">Potenza Car Dealer Group</div>
    								<div style="font-size: 20px; font-weight: bold;">Adajan Pal-Gam</div>
    								<div style="font-size: 20px; font-weight: bold;">Surat</div>
    							</td>							
    						</tr>
    					</tbody>
    				</table>
    			</td>
    		</tr>
    	</tbody>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
    	<tbody>
    		<tr>
    			<td align="left" valign="top" width="56%">
    				<table border="0" width="100%" cellspacing="0" cellpadding="0">
    					<tbody>
    					    <tr>
    							<td align="left" height="25"><b> VIN :</b> {{vin_number}}</td>
    						</tr>
    						<tr>
    							<td align="left" height="25"><b> Stock :</b> {{stock_number}}</td>
    						</tr>
    						<tr>
    						    <td align="left" height="25"><b> Engine :</b> {{engine}}</td>
    						</tr>
    						<tr>
    							<td align="left" height="25"><b> Transmission :</b> {{transmission}}</td>
    						</tr>
    						<tr>
    							<td align="left" height="25"><b> Drivetrain :</b> {{drivetrain}}</td>
    						</tr>
    						<tr>
    							<td align="left" height="25"><b> Mileage :</b> {{mileage}}</td>
    						</tr>
    						<tr>
    							<td align="left" height="25"><b> Int. Color :</b> {{interior_color}}</td>
    						</tr>
    						<tr>
    							<td align="left" height="25"><b> Exterior Color :</b> {{exterior_color}}</td>
    						</tr>
    						<tr>
    							<td align="left" height="25"><b> Fuel Type :</b> {{fuel_type}}</td>
    						</tr>
    					</tbody>
    				</table>
    			</td>
    			<td align="left" valign="top" width="44%">
    				<table border="0" width="98%" cellspacing="1" cellpadding="1">
    					<tbody>
    					    <tr>
    							<td height="25"><b> Regular Price :</b> {{currency_symbol}}{{regular_price}}</td>
    						</tr>
    						<tr>
    							<td height="25"><b> Sale Price :</b> {{currency_symbol}}{{sale_price}}</td>
    						</tr>
    						<tr>
    							<td height="25"><b> Year :</b> {{year}}</td>
    						</tr>
    						<tr>
    							<td height="25"><b> Make :</b> {{make}}</td>
    						</tr>
    						<tr>
    							<td height="25"><b> Model :</b> {{model}}</td>
    						</tr>
    						<tr>
    							<td height="25"><b> Body Style :</b> {{body_style}}</td>
    						</tr>
    						<tr>
    							<td height="25"><b> Condition :</b> {{condition}}</td>
    						</tr>
    						<tr>
    							<td height="25"><b> Trim :  </b> {{trim}}</td>
    						</tr>
    					</tbody>
    				</table>
    			</td>
    		</tr>
    	</tbody>
    	<tbody>
    		<tr>
    			<td>
    				<table border="0" width="100%" cellspacing="1" cellpadding="1">
    					<tbody>
    						<tr>
    							<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center">
    								<h3>FUEL ECONOMOY</h3>
    							</td>
    						</tr>
    						<tr>
    							<td style="font-size: 30px;" align="center">
    								{{fuel_economy}}
    							</td>
    						</tr>
    						<tr>
    							<td align="center"></td>
    						</tr>
    						<tr>
    							<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center">
    								<h3>FUEL EFFICIENCY</h3>
    							</td>
    						</tr>
    						<tr>
    							<td align="center">
    								<table border="0" width="100%" cellspacing="1" cellpadding="1">
    									<tbody>
    										<tr>
    											<td><img src="{{city_image}}" alt=""/></td>
    											<td rowspan="2"><img src="{{fuel_image}}" /></td>
    											<td><img src="{{hwy_image}}" alt=""/></td>
    										</tr>
    										<tr>
    											<td style="font-size: 30px;">{{city_mpg}}</td>
    											<td style="font-size: 30px;">{{high_waympg}}</td>
    										</tr>
    									</tbody>
    								</table>
    							</td>
    						</tr>
    					</tbody>
    				</table>
    			</td>
    			<td align="left" valign="top" bgcolor="#FFFFFF" height="380">
    				<table border="0" width="100%" cellspacing="1" cellpadding="1">
    					<tbody>
    						<tr>
    							<td style="font-family: Arial, Helvetica, sans-serif, sans-serif; font-weight: bold; font-size: 20px;" align="center">FEATURES/ACCESSORIES</td>
    						</tr>
    						<tr style="display: none;">
    							<td height="15"><strong>Certified Pre-owned</strong></td>
    						</tr>
    						<tr>
    							<td>
    								<ul>{{features_options}}</ul>
    							</td>
    						</tr>
    					</tbody>
    				</table>
    			</td>
    		</tr>
    	</tbody>
    </table>
    <?php
    return ob_get_clean();
} 
?>