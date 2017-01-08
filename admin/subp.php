<?php
	session_start();
// ******************************************************************************
// Software: OPeri - Open Periodical Publishing Platform                        *
// Version:  2.0                                                                *
// Date:     2016-05-01                                                         *
// Author:   Jason C. Simon <jasoncsimon@gmail.com>                             *
// License:  GPL                                                                *
//                                                                              *
// ******************************************************************************

	require_once('../includes/connect.php');
	include_once('../includes/pub.php');
	require_once('includes/loginid.php');


	$article_id = $_GET['article_id'];
	
	$atit = $dbc->prepare("select article_title from article where article_id = ?");
	$atit->bind_param("i", $article_id);
	$atit->execute();
	$atit->bind_result($article_title);
	$atit->fetch();
	$atit->close();

	

//If the "new" submit button is selected (in conditional looking for matches below), this will insert a new row into subject and then an associating row to connect it to the article
	
	if (isset($_POST['new'])) {
		$subject_name = $_POST['subject_name'];
		
		
		$subj = $dbc->prepare("insert into subject (subject_id, subject_name, type_code) values (null, ?, 'PE')");
		$subj->bind_param("s",$subject_name);
		$subj->execute();
		$subj->close();
		
		$subject_id = $dbc->insert_id;
		
		$asub = $dbc->prepare("insert into article_subject (article_id, subject_id) values (?,?)");
		$asub->bind_param("ii",$article_id, $subject_id);
		$asub->execute();
		$asub->close();
	}

	
	//if user chooses any checked subjects after being asked, this will insert the rows into article_subject [come back and edit after first part is working]
	if (isset($_POST['choose'])) {
		foreach ($_POST['subject_id'] as $subject) {
	
			$qri = $dbc->prepare("insert into article_subject (article_id, subject_id)
					values (?, ?)");
			$qri->bind_param("ii",$article_id,$subject);
			$qri->execute();
			$qri->close();
		}			
	}
	
//submitting all subjects, either selected or new

	if (isset($_POST['all_subj']))  {
	
		//first verify subjectname is the one that is submitted
		$subject_name = $_POST['subject_name'];
		
		//check to make sure there is something checked or entered
		
		if (!empty($subject_name)) {
		// get all existing matching subjects
		
			$qsub = $subject_name;
			$param = '%'.$qsub.'%';
			
			$msub = $dbc->prepare("select subject_id, subject_name from subject where subject_name like ?");
			
			$msub->bind_param("s",$param);
			$msub->execute();
			$msub->store_result();
			$msub->bind_result($subject_id, $subject_name);
			
			//if a string like this exists, stop and generate new form
			
			if ($msub->num_rows > 0) {
				echo '<div class= "existing">';
			
				echo '<form method="POST" action="'.$_SERVER['PHP_SELF'].'?article_id='.$article_id.'">';
				echo 'Did you mean to enter one of the following?: <br/>';
				while ($msub->fetch()) {
					//allow to choose existing subject
					echo '<input type="checkbox" id="subject_id" name="subject_id[]" value="'.$subject_id.'"/>';
					echo '<b>'.$subject_name.'</b><br/>';
				}
				//or allow an override to add new one
				
				echo '<input type="submit" name="choose" value="Choose selected entries" /> <p/>';
				echo '<input type="hidden" name="subject_name" value="'. $qsub.'" />';
				echo ' Or Enter: <input type="submit" name="new" value="'. $qsub.'" />';
				echo '</form> </div>';			
			$msub->free_result();
			$msub->close();
			}
			//else if there are no matches go ahead with the insert
			else {
			
			//this will work, but I"m wondering if I can just set $_POST['new'] here.  (note, I may need to change the insert name, as I already used it above)
			
				$subject_name = $_POST['subject_name'];
				
				
				$subj = $dbc->prepare("insert into subject (subject_id, subject_name, type_code) values (null, ?, 'PE')");
				$subj->bind_param("s",$subject_name);
				$subj->execute();
				$subj->close();			
			
			
			}
			
			//connect it with the article

			$subject_id = $dbc->insert_id;
			
			$asub = $dbc->prepare("insert into article_subject (article_id, subject_id) values (?,?)");
			$asub->bind_param("ii",$article_id, $subject_id);
			$asub->execute();
			$asub->close();
		
		
		} 
		//if any existing subjects were chose, then link them all to article
		if (!empty($_POST['subject_id'])) {
		
			foreach (($_POST['subject_id']) as $sub_id) {
			
				$subs = $dbc->prepare("insert into article_subject (article_id, subject_id) values (?,?)");
				$subs->bind_param("ii",$article_id, $sub_id);
				$subs->execute();
				$subs->close();
			
			}
		
		}

	}
	
	//remove subjects from article
	
	if (isset($_POST['remove'])) {
		
		if (!empty($_POST['delete']))  {
			foreach (($_POST['delete']) as $delete) {
				$delsub = $dbc->prepare("delete from article_subject where article_id = ? and subject_id = ?");
				$delsub->bind_param("ii", $article_id, $delete);
				$delsub->execute();
				$delsub->close();
			}
		
		}
	
	}

?>
<!DOCTYPE html>
	<html>
	<head>
	<title><?php echo $pubtitle; ?> - Manage People</title>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<link rel="stylesheet" type="text/css" href="../css/digpub.css" />
	<link rel="stylesheet" type="text/css" href="../css/custom.css" />
	</head>
	<body>
<?php
	require_once('includes/loggedin.php');
	include_once('includes/admintop.php');
?>	
	<div class="left">
		<h3>Current People for "<a href="article_admin.php?article_id=<?php echo $article_id. '">'. $article_title;?> </a>"</h3>
		<form  method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?article_id='.$article_id;    ?>">

<?php
			// get a list of the subjects currently associated with this article

			$sub = $dbc->prepare("select s.subject_id, s.subject_name
									from subject s
									join article_subject sa
									on s.subject_id = sa.subject_id
									join article a
									on a.article_id = sa.article_id
									where a.article_id = ?
									and s.type_code = 'PE'
									order by s.subject_name");
					
			$sub->bind_param("i", $article_id);
			$sub->execute();
			$sub->bind_result($subject_id, $subject_name);
			$sub->store_result();
			$num = $sub->num_rows;
			if ($num > 0) {
				while ($sub->fetch()) {
					echo '<input type="checkbox" value="'.$subject_id.'" name="delete[]" /> ' .$subject_name . '<br/>' ;
				}
				echo '<br/><input type="submit" value="Remove" name="remove"/>';
			}
			else {
				echo 'This article currently has no associated subject terms <p/>';
			}
			$sub->free_result();
			$sub->close();
			
	
?> 
		</form>
	</div>
	<div class="right">
	<p>&nbsp;</p>
	
	<h3>All available People</h3>
		<form  method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?article_id='.$article_id;    ?>"> 
<?php
			//get a list of all subjects not being used
			
			$subn = $dbc->prepare("select subject_id, subject_name
									from subject
									where type_code = 'PE'
									and subject_id not in 
										(select s.subject_id
										 from subject s
										 join article_subject sa
										 on s.subject_id = sa.subject_id
										 where sa.article_id = ?)
									order by subject_name");
			$subn->bind_param("i",$article_id);
			$subn->execute();
			$subn->bind_result($subject_id, $subject_name);
			
			while ($subn->fetch()) {
				echo '<input type="checkbox" name="subject_id[]"value="'.$subject_id.'" />' .$subject_name. '<br/>';
			}	
			$subn->close();
?>	
		<p>Or add a new subject term</p>
		<input type="text" name="subject_name" size="50" placeholder="Add a new subject term" />
		<input type="submit" name="all_subj" value="Add New subject(s)" />
		</form>
	</div>
	
	</body>


</html>	
	