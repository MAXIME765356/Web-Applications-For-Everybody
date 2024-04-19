<?php
	
session_start();

include 'helpers/pdo_helper.php';

$logged_in = false;
$profiles = [];

if (isset($_SESSION['name']) ) 
{

	$logged_in = true;
	$status = false;

	if ( isset($_SESSION['status']) ) 
	{
		$status = htmlentities($_SESSION['status']);
		$status_color = htmlentities($_SESSION['color']);

		unset($_SESSION['status']);
		unset($_SESSION['color']);
	}

	try 
	{
	    $pdo = pdoHelper();

	    $all_profiles = $pdo->query("SELECT * FROM profile");

		while ( $row = $all_profiles->fetch(PDO::FETCH_OBJ) ) 
		{
		    $profiles[] = $row;
		}
	}
	catch(PDOException $e)
	{
	    echo "Connection failed: " . $e->getMessage();
	    die();
	}
}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>Samarth Srivastava's Resume Registry</title>
		
		<?php include 'helpers/head_helper.php'; ?>
	</head>
	<body>
		<div class="container">
			<h1>Samarth Srivastava's Resume Registry</h1>

			<?php if (!$logged_in) : ?>
				<p>
					<a href="login.php">Please log in</a>
				</p>
				<p>
					Attempt
					<a href="add.php">add data</a> 
					without logging in.
				</p>
			<?php else : ?>

				<?php
	                if ( $status !== false ) 
	                {
	                    // Look closely at the use of single and double quotes
	                    echo(
	                        '<p style="color: ' .$status_color. ';" class="col-sm-10">'.
	                            $status.
	                        "</p>\n"
	                    );
	                }
	            ?>

				<?php if (empty($profiles)) : ?>
					<p>No rows found</p>
				<?php else : ?>
					<div class="row">
						<div class="col-md-8">
							<table class="table">
								<thead>
									<tr>
										<th>Name</th>
										<th>Headline</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach($profiles as $profile) : ?>
				                        <tr>
				                        	<td>
				                        		<a href="view.php?profile_id=<?php echo $profile->profile_id; ?>">
				                        			<?php echo $profile->first_name . ' ' . $profile->last_name; ?>
				                        		</a>
				                        	</td>
											<td><?php echo $profile->headline ?></td>
											<td>
												<a href="edit.php?profile_id=<?php echo $profile->profile_id; ?>">
													Edit
												</a> / 
												<a href="delete.php?profile_id=<?php echo $profile->profile_id; ?>">
													Delete
												</a>
											</td>
				                        </tr>
				                    <?php endforeach; ?>
								</tbody>
							</table>
						</div>
					</div>
				<?php endif; ?>
				<p>
					<a href="add.php">Add New Entry</a>
				</p>
				<p>
					<a href="logout.php">Logout</a>
				</p>
			<?php endif; ?>	
		</div>
	</body>
</html>

