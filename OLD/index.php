<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if(isset($_SESSION["user_name"]))
{
?>
        <!DOCTYPE html>
        <html lang="en">
          <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <title>Godown Stock Management</title>
		
            <!-- Bootstrap -->
            <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
            
            <!-- Main Style -->
            <link rel="stylesheet" type="text/css" href="css/main.css"> 

            <!--Icon Fonts-->
            <link rel="stylesheet" media="screen" href="fonts/font-awesome/font-awesome.min.css" />

						
          </head>

        <body>

		
     	<!-- Store Section -->
        <section id="pricing-table">
            <div class="container">
                <div class="row">
                    <div class="pricing">
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header">
                                    <p class="pricing-title">LOADING</p>
									<br>
                                    <a href="loading/new.php" class="btn btn-custom">Add New Loading</a>
									<br><br>
									<a href="returns/new.php" class="btn btn-custom">Add Return Bag</a>									
								</div>

                                <div class="pricing-list">
                                    <ul>
									<br>
								    <a href="loading/list.php" class="btn btn-custom2">List Loading Data</a>
									<br><br>
									<a href="" class="btn btn-custom2">Standardization</a>
									<br><br><br>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
                                <div class="pricing-header">
                                    <p class="pricing-title">Add to Stock</p>
									<br>
                                    <a href="unloading/new.php" class="btn btn-custom">Add To Stock</a>
									<br><br>
                                    <a href="unloading/list.php" class="btn btn-custom">List</a>
									</div>

                                <div class="pricing-list" align="center">
									<br>	
                                    <a href="stock_transfer.php" class="btn btn-custom2">Transfer Stock</a>									
									<br><br>
                                    <a href="view_transferLog.php" class="btn btn-custom2">Transfer Log</a>																		
									<br><br><br>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12 col-xs-12">
                            <div class="pricing-table">
								<div class="pricing-header">
									<p class="pricing-title">BALANCE STOCK</p>
									<br>
                                    <a href="stock/balanceView.php?" class="btn btn-custom">View Stock</a>
									<br><br>
									<a href="dayBook.php?" class="btn btn-custom">Day Book</a>
									<br>
								</div>

                                <div class="pricing-list" align="center">
                                    <br>
                                    <a href="partyLedger.php?" class="btn btn-custom2">View Party Ledger</a>
									<br><br>
									<a href="stock/clientBalance.php" class="btn btn-custom2">Party Wise Balance</a>
									<br><br><br>
                                </div>
                            </div>
                        </div>
						
                    </div>
                </div>
            </div>
        </section>
		<!-- Pricing Table Section End -->
		</body>
        </html>
<?php
}
else
header("Location:loginPage.php");
?>	