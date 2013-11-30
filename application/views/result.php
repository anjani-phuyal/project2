<div class="clear"></div>
<div class="content"> <!-- starts:contents -->
	<div class="container"  <!-- starts:container -->
		<div class="row">
			<div class="col-md-3 col-sm-3 col-xs-12">
				<div class='filter'> <!-- starts:filter -->
					Filter Result
					<pre><?php print_r($result) ?></pre>
				</div><!-- ends:filter -->
			</div>
			<div class="col-md-9 col-sm-9 col-xs-12">
				<div class="result-content"> <!-- starts:result content -->
					<ol class="breadcrumb"> <!-- starts:breadcrumbs -->
					  <li><a href="#">Home</a></li>
					  <li><a href="#">Library</a></li>
					  <li class="active">Data</li>
					</ol> <!-- ends:breadcrumbs -->

					<div class="row">
						<div class="main-sarch-content">
							<div class="col-md-12">
								<div class="hotel-place">
									<h1>Hotels In <?php echo $searchInfo['city']; ?></h1>
								</div>
							</div>
							<div class="col-md-12">
								<div class="result-details">
									<p><span><?php echo count($result); ?> Results </span>for <?php echo $searchInfo['checkInDate']; ?> to <?php echo $searchInfo['checkOutDate']; ?> at <?php echo $searchInfo['city']; ?> </p>
								</div>
							</div>
							<div class="col-md-12"> <!-- starts:sort and per page -->
								<div class="row"> 
									<div class="col-xs-6 col-sm-4 col-md-2">
										<div class="sort-result">
											<label>Sort By:</label>
											<select class="form-control">
												<option>Availability</option>
											</select>
										</div>
									</div>
									<div class="col-xs-6 col-sm-4 col-md-2">
										<div class="result-per-page">
											<label>Per Page:</label>
											<select class="form-control">
												<option>10</option>
												<option>20</option>
												<option>30</option>
											</select>
										</div>
									</div>
								</div>
							</div> <!-- ends:sort and per page -->
							<div class="clear"></div>
							<div class="col-md-12">
								<div class="results-wrap">
									<?php foreach ($result as $aResult) { $url=base_url('details')."/".$aResult['hotel_id']; ?>
										<div class="row result"> <!-- starts:hotel search row -->
												<div class="col-md-2">
													<a href="<?php echo $url;?>"><img src="<?php echo base_url('assets/images/owner.jpg'); ?>"></a>
												</div>
												<div class="col-md-10">
													<div class="hotel-desc">
														<div class="row">
															<div class="col-md-12">
																<div class="hotel-search-title">
																	<h1><a href="<?php echo $url; ?>"><?php echo $aResult['name'] ?></a></h1>
																	<p><?php echo $aResult['address'] ?></p>
																</div>
															</div>
															<div class="col-md-9">
																<div class="short-desc">
																	<p><?php echo $aResult['description'] ?><a href="<?php echo $url; ?>">more</a> </p>
																</div>
																<div class="search-review">
																	<p><span>13</span> Total reviews </p>
																</div>
															</div>
															<div class="col-md-3">
																<div class="price-range">
																	<p><span>Price From: </span> <br /> Nrs 400</p>
																</div>
																<a href="<?php echo $url;?>"><button class="btn btn-default inn-button">Book</button></a>
															</div>
														</div>
													</div>
												</div>
										</div>  <!-- ends:hotel search row -->
										<div class="clear"></div>
									<?php } ?>
								</div>
							</div>


						</div>
					</div>
				</div> <!-- ends:result content -->
			</div>
		</div>
	</div> <!-- ends:container -->
</div>  <!-- ends:contents -->